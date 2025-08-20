<?php

namespace App\Http\Controllers;

use App\Mail\SendQrCodeMail;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Interface\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Session;

class QrCodeController extends Controller
{

    private $product;

    public function __construct(
        ProductRepositoryInterface $product,
    )
    {
        $this->product = $product;
    }

    public function showGenerateQrForm()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('qr-cart-suggestion') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.order.qr-order');
    }

    public function generateQrCode(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('qr-cart-suggestion') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product' => 'required|array|min:1',
            'product.*.id' => 'required|exists:products,id',
            'product.*.unit_price' => 'required|numeric|min:0',
            'product.*.quantity' => 'required|integer|min:1',
        ]);

        $payload = [
            'user_id' => $request->user_id,
            'products' => $request->product,
            'expires_at' => now()->addHours(24)->toISOString(),
        ];

        $user = User::find($request->user_id);

        $encryptedPayload = Crypt::encrypt($payload);

        // Generate QR code
        $qrCodeUrl = route('scanQRCode', ['data' => urlencode($encryptedPayload)]);
        $qrCode = new QrCode($qrCodeUrl);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Save the PNG QR code to a temporary file
        $tempFilePath = storage_path('app/public/qr_codes/' . str_replace(' ', '_', $user->name) . '_qr_code.png');
        file_put_contents($tempFilePath, $result->getString());
        // Send QR code via email
        try {
            Mail::to($user->email)->send(new SendQrCodeMail($user, $tempFilePath));
            unlink($tempFilePath);
            return response()->json(['status' => true, 'message' => 'QR Code sent to the user successfully!', 'load' => true,]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to send QR Code: ' . $e->getMessage()]);
        }
    }


    public function scanQRCode(Request $request)
    {
        $encryptedData = $request->query('data');

        try {
            $payload = Crypt::decrypt(urldecode($encryptedData));
            if (isset($payload['expires_at']) && now()->greaterThanOrEqualTo($payload['expires_at'])) {
                return redirect()->route('dashboard')->with('error', 'QR Code has expired');

            }


            $user = User::findOrFail($payload['user_id']);

            if (!Auth::guard('customer')->check()) {
                Auth::guard('customer')->login($user);
            }
            $result = $this->addToCart($payload, $request->ip());
            return redirect()->route($result['redirect'])->with($result['type'], $result['msg']);

        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Invalid QR Code or expired' . $e->getMessage());

        }
    }

    private function addToCart($request, $ip)
    {
        collect($request['products'])->map(function ($productData) use ($request, $ip) {
            $productId = $productData['id'];
            $quantity = $productData['quantity'];
            $itemSubTotalPrice = 0;

            if ($quantity > 100) {
                return [
                    'redirect' => 'dashboard',
                    'type' => 'warning',
                    'msg' => 'This product is not available in the desired quantity or not in stock',
                ];
            }

            $product = Product::find($productId);
            if (!$product) {
                return [
                    'redirect' => 'dashboard',
                    'type' => 'warning',
                    'msg' => 'This product is not available in the desired quantity or not in stock',
                ];
            }

            $ip = Session::get('ip') ?? $ip;
            $currencyId = Session::get('currency_id') ?? 1;
            $userId = $request['user_id'] ?? null;

            $cart = Cart::when(isset($userId), function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            }, function ($query) use ($ip) {
                return $query->where('ip', $ip);
            })->first();

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => $userId,
                    'ip' => $ip,
                    'total_quantity' => 0,
                    'currency_id' => $currencyId,
                ]);
            }

            $cartDetail = CartDetail::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            if ($cartDetail) {
                if (($cartDetail->quantity + $quantity) > 100) {
                    return [
                        'redirect' => 'dashboard',
                        'type' => 'warning',
                        'msg' => 'This product is not available in the desired quantity or not in stock',
                    ];
                }

                $stockResponse = getProductStock($product->id, ($cartDetail->quantity + $quantity));
                if (!$stockResponse['status']) {
                    return response()->json($stockResponse);
                }

                $quantity += $cartDetail->quantity;
                if (($stockResponse['stock']) < $quantity) {
                    return [
                        'redirect' => 'dashboard',
                        'type' => 'warning',
                        'msg' => 'This product is not available in the desired quantity or not in stock',
                    ];
                }

                $cartDetail->quantity = $quantity;
                $cartDetail->save();

            } else {
                if ($quantity > 100) {
                    return [
                        'redirect' => 'dashboard',
                        'type' => 'warning',
                        'msg' => 'This product is not available in the desired quantity or not in stock',
                    ];
                }

                $stockResponse = getProductStock($product->id, $quantity);
                if (!$stockResponse['status']) {
                    return response()->json($stockResponse);
                }

                if (($stockResponse['stock']) < $quantity) {
                    return [
                        'redirect' => 'dashboard',
                        'type' => 'warning',
                        'msg' => 'This product is not available in the desired quantity or not in stock',
                    ];
                }

                $cartDetail = new CartDetail([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ]);
                $cartDetail->save();
            }
        });
        return [
            'redirect' => 'order.checkout',
            'type' => 'success',
            'msg' => 'Products added to cart successfully',
        ];

    }

}
