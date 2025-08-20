<?php

namespace App\Repositories;

use App\CPU\Images;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interface\AdminRepositoryInterface;
use Spatie\Permission\Models\Permission;

class AdminRepository implements AdminRepositoryInterface
{
    public function getAllAdmins()
    {
        return Admin::select('id', 'name', 'email', 'phone', 'updated_at')->get();
    }

    public function getAdminById($id)
    {
        return Admin::findOrFail($id);
    }

    public function createAdmin($data)
    {
        $data['password'] = Hash::make($data['password']);

        $role = Role::find($data['roles']);
        $admin = Admin::create($data);

        // $permissions = Permission::pluck('id', 'id')->all();

        // $role->syncPermissions($permissions);
        $admin->assignRole([$role->id]);
        $admin->designation = $role->name;
        $admin->save();

        return 1;
    }

    public function updateAdmin($id, array $data)
    {
        $admin = Admin::findOrFail($id);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if(isset($data['avatar'])) {
            $data['avatar'] = Images::upload('admin', $data['avatar']);
        }

        $admin->update($data);

        if(isset($data['roles'])) {
            if(count($admin->roles) > 0) {
                foreach($admin->roles->toArray() as $adminRole) {
                    $admin->removeRole($adminRole['name']);
                }
            }

            $role = Role::findOrFail($data['roles']);
            if($role) {
                $admin->assignRole($role->name);
                $admin->designation = $role->name;
                $admin->save();
            }
        }
        
        return $admin;
    }

    public function deleteAdmin($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();
    }

    public function createOrder($request)
    {
        $this->validateRequest($request);
        $getProductsData = $this->productsdata($request['product']);
        $errorItem = $getProductsData->first(function ($item) {
            return isset($item['status']) && $item['status'] === false;
        });


        $details = [];
        if (!$errorItem && !isset($errorItem)) {
            $productIds = $getProductsData->pluck('details.id')->toArray();

            $details['products'] = $getProductsData->map(function ($item) {
                return $item['details'];
            })->toArray();
            $user = User::find($request->user_id);
            if (!$user) {
                return ['status' => false, 'message' => 'User not found In Database'];
            }
            $details['company_name'] = $request->customer_company;
            $details['user_name'] = $user->name;
            $details['shipping_charge'] = covert_to_usd($request->shipping_charge) ?? 0;
            $paidOptions = ['offline_payment', 'cash'];
            $defaultCurrency=get_system_default_currency();
            try {
                DB::beginTransaction();

                // Step 2: Create the order
                $order = Order::create([
                    'unique_id' => uniqid('#'),
                    'payment_id' => null,
                    'user_id' => $request->user_id,
                    'order_amount' => round(covert_to_usd(($request->total_amount_value + $request->discount) - $request->shipping_charge), 2),
                    'tax_amount' => round($getProductsData->sum('tax'), 2),
                    'discount_amount' => round(covert_to_usd($request->discount), 2),
                    'final_amount' => round(covert_to_usd($request->total_amount_value), 2)+round($getProductsData->sum('tax'), 2),
                    'exchange_rate' => get_exchange_rate($defaultCurrency->code),
                    'currency_id' => $defaultCurrency->id,
                    'payment_status' => in_array($request->payment_option, $paidOptions) ? 'Paid' : 'Not_Paid',
                    'status' => 'pending',
                    'is_delivered' => false,
                    'is_cod' => !in_array($request->payment_option, $paidOptions),
                    'is_admin_order' => true,
                    'admin_id' => Auth::guard('admin')->user()->id,
                    'is_refund_requested' => false,
                ]);

                // Step 4: Create the order details
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_ids' => json_encode($productIds),
                    'details' => json_encode($details),
                    'notes' => 'Admin Created Order behalf of Customer',
                    'shipping_method' => $request->shipping_method ?? 'Default',
                    'shipping_address' => $request->shipping,
                    'billing_address' => $request->billing ?? $request->shipping,
                    'phone' => $user->phone->where('is_default', true)->first()->phone_number ?? null,
                    'email' => $user->email,
                ]);

                // Step 5: Adjust Stock for Paid Orders
                if (!$order->is_cod) {
                    $this->adjustStock($details['products']);
                }


                DB::commit();
                // Return success response
                return [
                    'status' => true,
                    'load' => true,
                    'message' => 'Order Created Successfully!'
                ];
            } catch (\Exception $e) {
                DB::rollBack();

                return [
                    'status' => false,
                    'message' => $e->getMessage(),
                ];
            }
        } else {
            return $errorItem;
        }

    }


    public function adjustStock($products): bool
    {
        $products = collect($products);
        $productIds = collect($products)->pluck('id');  

        $stockIds = ProductStock::whereIn('product_id', $productIds)
            ->where('in_stock', 1)
            ->where('stock', '>', 0)
            ->get()
            ->groupBy('product_id')
            ->map(fn($stocks) => $stocks->first()->id)
            ->values();

        DB::beginTransaction();

        try {
            $productStocks = ProductStock::whereIn('id', $stockIds)
                ->with('productDetail')
                ->get()
                ->keyBy('id');

            $products->map(function ($product) use ($productStocks) {
                $productStock = $productStocks->get($product['stock_id']);

                if ($productStock) {
                    $productDetail = $productStock->productDetail;

                    if ($productDetail) {
                        $productStock->increment('number_of_sale', $product['quantity']);
                        $productStock->decrement('stock', $product['quantity']);
                        $productStock->in_stock = $productStock->stock > 0;
                        $productStock->save();

                        $productDetail->decrement('current_stock', $product['quantity']);
                        $productDetail->increment('number_of_sale', $product['quantity']);
                        $productDetail->save();
                    }
                }
            });

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function validateRequest($request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'shipping' => 'required',
                'billing' => 'nullable',
                'customer_company' => 'nullable',
                'product' => 'required|array',
                'shipping_charge' => 'required|numeric',
                'payment_type' => 'required|numeric',
                'discount' => 'required|numeric',
                'total_amount_value' => 'required|numeric',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
            return;
        }
    }

    private function productsdata($products)
    {
        return collect($products)->map(function ($product) {
            $item = Product::where('id', $product['id'])
                ->select('id', 'slug', 'name', 'unit_price', 'discount', 'discount_type', 'sku', 'stock_types')
                ->with([
                    'stock' => function ($query) {
                        $query->select('id', 'stock', 'number_of_sale', 'in_stock', 'product_id')
                            ->where('in_stock', '>', 0);
                    },
                    'taxes' => function ($query) {
                        $query->whereHas('tax_model', function ($q) {
                            $q->where('status', true);
                        });
                    }
                ])
                ->first();

            if (!$item) {
                return ['status' => false, 'message' => 'Product Not Found'];
            }

            $tax = $item->taxes->map(function ($tax) use ($item) {
                $value = $tax->tax;
                if ($tax->tax_type === "percent") {
                    $value = ($item->unit_price * $tax->tax) / 100;
                }
                return $value;
            })->sum();

            $stockItem = $item->stock->firstWhere('stock', '>=', $product['quantity']);
            if (!$stockItem) {
                return ['status' => false, 'message' => 'Stock Not Available for ' . $item->name];
            }

            return [
                'details' => [
                    'id' => $item->id,
                    'slug' => $item->slug,
                    'name' => $item->name,
                    'unit_price' => $item->unit_price,
                    'discount' => $item->discount,
                    'discount_type' => $item->discount_type,
                    'sku' => $item->sku,
                    'stock_id' => $stockItem->id,
                    'total_price' => $item->unit_price * $product['quantity'],
                    'qty' => $product['quantity'],
                    'tax' => $tax*$product['quantity'],
                ],
                'tax' => $tax * $product['quantity'],
            ];
        })->filter()->values();
    }

}
