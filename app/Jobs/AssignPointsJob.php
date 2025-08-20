<?php

namespace App\Jobs;

use App\CPU\Helpers;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignPointsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;
    protected $userId;
    public $tries = 3;
    public $sleep = 3;
    public $timeout = 90;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId, $userId)
    {
        $this->orderId = $orderId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $orderDetails = OrderDetail::where('order_id', $this->orderId)->first();

            if (!$orderDetails || !$orderDetails->details) {
                Log::warning("Order details not found for order ID: {$this->orderId}");
                return;
            }

            $products = $this->getProductDetails($orderDetails->details);
            $productIds = $products->pluck('product_id')->toArray();

            $user = User::find($this->userId);
            if (!$user) {
                Log::warning("User not found for user ID: {$this->userId}");
                return;
            }

            $totalPoints = 0;

            // Load products in one query
            $productDetails = Product::with('details')->whereIn('id', $productIds)->get()->keyBy('id');

            DB::transaction(function () use ($products, $productDetails, $user, &$totalPoints) {
                $products->each(function ($model) use ($user, $productDetails, &$totalPoints) {
                    $product = $productDetails->get($model['product_id']);
                    $quantity = $model['quantity'];

                    if ($product && $product->details && $product->details->points > 0) {
                        $points = $quantity * $product->details->points;
                        $totalPoints += $points;

                        UserPoint::create([
                            'user_id' => $user->id,
                            'product_id' => $product->id,
                            'points' => $product->details->points,
                            'quantity' => $quantity,
                            'method' => 'plus',
                        ]);
                    }
                });

                $user->increment('points', $totalPoints); // Update points in one operation
            });

            Helpers::activity($user->id, null, null, 'system', "{$totalPoints} Points assigned successfully for order ID: {$this->orderId} and user: {$user->name}", null);
        } catch (\Exception $e) {
            Helpers::activity($this->userId, null, null, 'system', "Error in AssignPointsJob for order ID: {$this->orderId} and user ID: {$this->userId}: " . $e->getMessage(), null);
            throw $e;
        }
    }

    /**
     * Extract and format product details.
     */
    private function getProductDetails($details)
    {
        $data = json_decode($details, true);

        return collect($data['products'])->map(function ($product) {
            return [
                'product_id' => $product['id'],
                'stock_id' => $product['stock_id'],
                'quantity' => (int)$product['qty'],
            ];
        });
    }
}
