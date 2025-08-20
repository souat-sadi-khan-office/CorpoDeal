<?php

namespace App\Repositories;

use App\CPU\Images;
use App\Models\Product;
use App\Models\FlashDeal;
use App\Models\FlashDealType;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\FlashDealRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class FlashDealRepository implements FlashDealRepositoryInterface
{
    public function getAllDeals()
    {
        return FlashDeal::all();
    }

    public function dataTable()
    {
        $models = $this->getAllDeals();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('banner', function ($model) {
                return Images::show($model->image);
            })
            ->editColumn('created_by', function ($model) {
                return $model->admin->name;
            })
            ->editColumn('start_date', function ($model) {
                return get_system_date($model->starting_time);
            })
            ->editColumn('end_date', function ($model) {
                $starting_time = strtotime($model->starting_time);
                $end_date = strtotime('+ ' . $model->deadline_time . ' ' . $model->deadline_type, $starting_time);

                return get_system_date(date('Y-m-d', $end_date));
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';

                if(Auth::guard('admin')->user()->hasPermissionTo('flash-deal.update')) {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.brand.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                } else {
                    $bg = $model->status == 1 ? 'success' : 'warning';
                    $status = $model->status == 1 ? 'Active' : 'Inactive';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                }
            })

            ->addColumn('action', function ($model) {
                return view('backend.deal.action', compact('model'));
            })
            ->rawColumns(['action', 'status', 'created_by', 'end_date', 'start_date', 'banner'])
            ->make(true);
    }

    public function findDealById($id)
    {
        return FlashDeal::findOrFail($id);
    }

    public function createDeal($data)
    {
        $deal = FlashDeal::create([
            'title' => $data->title,
            'admin_id' => Auth::guard('admin')->id(),
            'slug' => $data->slug,
            'short_content' => $data->short_content,
            'status' => 1,
            'starting_time' => date('Y-m-d', strtotime($data->starting_time)),
            'deadline_time' => $data->deadline_time,
            'deadline_type' => $data->deadline_type,
            'description' => $data->description,
            'site_title' => $data->site_title,
            'meta_title' => $data->meta_title,
            'meta_keyword' => $data->meta_keyword,
            'meta_description' => $data->meta_description,
            'meta_article_tag' => $data->meta_article_tag,
            'meta_script_tag' => $data->meta_script_tag,
            'image' => $data->image ? Images::upload('deals', $data->image) : null,
        ]);

        $dealId = $deal->id;

        if (is_array($data['product_id']) && count($data['product_id']) > 0) {

            $productIdArray = $data->product_id;
            $discountArray = $data->discount;
            $discountTypeArray = $data->discount_type;

            $starting_time = strtotime($data->starting_time);
            $end_date = strtotime('+ ' . $data->deadline_time . ' ' . $data->deadline_type, $starting_time);

            foreach ($productIdArray as $id) {
                $i = 0;

                $dealTypes = FlashDealType::create([
                    'flash_deal_id' => $dealId,
                    'product_id' => $id,
                    'discount_amount' => $discountTypeArray[$i] == 'percentage' ? $discountArray[$i]  : covert_to_usd($discountArray[$i]),
                    'discount_type' => $discountTypeArray[$i]
                ]);

                if ($dealTypes) {
                    $discountData = [
                        'discount_type' => $discountTypeArray[$i],
                        'discount' => $discountArray[$i],
                        'starting_date' => date('Y-m-d', strtotime($data->starting_time)),
                        'end_date' => date('Y-m-d', $end_date)
                    ];
                    $this->addDiscountToProduct($id, $discountData);
                }
                $i++;
            }
        }

        $json = ['status' => true, 'goto' => route('admin.flash-deal.index'), 'message' => 'Deal created successfully'];
        return response()->json($json);
    }

    private function addDiscountToProduct($productId, $discountData)
    {
        $product = Product::find($productId);
        $product->is_discounted = 1;
        $product->discount_type = $discountData['discount_type'];
        $product->discount = $discountData['discount_type'] == 'percentage' ? $discountData['discount'] : covert_to_usd($discountData['discount']);
        $product->discount_start_date = $discountData['starting_date'];
        $product->discount_end_date = $discountData['end_date'];
        return $product->save();
    }

    private function removeDiscountProducts($productIds)
    {
        if (count($productIds) > 0) {
            foreach ($productIds as $product) {

                $product = Product::find($product);
                $product->is_discounted = 0;
                $product->discount_type = null;
                $product->discount = null;
                $product->discount_start_date = null;
                $product->discount_end_date = null;
                $product->save();
            }
        }
    }

    public function removeFlashDealItems($id)
    {
        $deals = FlashDealType::where('flash_deal_id', $id)->get();
        foreach ($deals as $deal) {
            $deal->delete();
        }
    }

    public function updateDeal($id, $data)
    {
        $deal = FlashDeal::findOrFail($id);
        $deal->admin_id  = Auth::guard('admin')->id();
        $deal->title = $data->title;
        $deal->short_content = $data->short_content;
        $deal->slug = $data->slug;
        $deal->status = 1;
        $deal->starting_time = date('Y-m-d', strtotime($data->starting_time));
        $deal->deadline_time = $data->deadline_time;
        $deal->deadline_type = $data->deadline_type;
        $deal->description = $data->description;
        $deal->site_title = $data->site_title;
        $deal->meta_title = $data->meta_title;
        $deal->meta_keyword = $data->meta_keyword;
        $deal->meta_description = $data->meta_description;
        $deal->meta_article_tag = $data->meta_article_tag;
        $deal->meta_script_tag = $data->meta_script_tag;

        if ($data->image) {
            $deal->image = Images::upload('deals', $data->image);
        }

        $deal->update();

        $this->removeFlashDealItems($id);

        if (is_array($data['product_id']) && count($data['product_id']) > 0) {

            $this->removeDiscountProducts($data->product_id);

            $productIdArray = $data->product_id;
            $discountArray = $data->discount;
            $discountTypeArray = $data->discount_type;

            $starting_time = strtotime(date('Y-m-d', strtotime($data->starting_time)));
            if ($data->deadline_type == 'day') {
                $data->deadline_type = 'days';
            }

            $end_date = strtotime('+ ' . $data->deadline_time . ' ' . $data->deadline_type, $starting_time);

            $end_date = date('Y-m-d', $end_date);

            for ($i = 0; $i < count($productIdArray); $i++) {
                $dealTypes = FlashDealType::create([
                    'flash_deal_id' => $id,
                    'product_id' => $productIdArray[$i],
                    'discount_amount' => $discountArray[$i],
                    'discount_type' => $discountTypeArray[$i]
                ]);

                if ($dealTypes) {
                    $discountData = [
                        'discount_type' => $discountTypeArray[$i],
                        'discount' => $discountArray[$i],
                        'starting_date' => date('Y-m-d', strtotime($data->starting_time)),
                        'end_date' => $end_date
                    ];
                    $this->addDiscountToProduct($productIdArray[$i], $discountData);
                }
            }
        }

        return response()->json(['status' => true, 'goto' => route('admin.flash-deal.index'), 'message' => 'Deal updated successfully.']);
    }

    public function deleteDeal($id)
    {
        $brand = FlashDeal::findOrFail($id);
        return $brand->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $flashDeal = FlashDeal::find($id);

        if (!$flashDeal) {
            return response()->json(['success' => false, 'message' => 'Flash deal not found.'], 404);
        }

        $flashDeal->status = $request->input('status');
        $flashDeal->save();

        return response()->json(['success' => true, 'message' => 'Flash deal status updated successfully.']);
    }
}
