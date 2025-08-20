<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory ,IstiyakTraitLog;

    protected $fillable = [
        'name',
        'slug',
        'admin_id',
        'category_id',
        'brand_id',
        'brand_type_id',
        'product_type',
        'thumb_image',
        'unit_price',
        'sku',
        'status',
        'in_stock',
        'minus_stock',
        'is_featured',
        'low_stock',
        'is_discounted',
        'discount_type',
        'discount',
        'discount_start_date',
        'discount_end_date',
        'is_returnable',
        'return_deadline',
        'stock_types'
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // budgets
    public function budgets()
    {
        return $this->hasOne(ProductBudget::class);
    }

    public function purposes()
    {
        return $this->hasMany(ProductPurpose::class);
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function screenSizes()
    {
        return $this->hasOne(ProductScreenSize::class);
    }

    public function pc_builder()
    {
        return $this->hasOne(PcBuilderItem::class, 'product_id');
    }

    public function portabilites()
    {
        return $this->hasOne(ProductPortability::class);
    }

    // Relation with taxes
    public function taxes()
    {
        return $this->hasMany(ProductTax::class);
    }

    // Relation with category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relation with brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Relation with brand type
    public function brandType()
    {
        return $this->belongsTo(BrandType::class);
    }

    // Relation with product details
    public function details()
    {
        return $this->hasOne(ProductDetail::class);
    }

    // Relation with product image
    public function image()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Relation with question
    public function question()
    {
        return $this->hasMany(ProductQuestion::class);
    }

    // Relation with stock purchase
    public function purchase()
    {
        return $this->hasMany(StockPurchase::class, 'product_id');
    }

    // Relation with product stock
    public function stock()
    {
        return $this->hasMany(ProductStock::class);
    }

    // Relation with banner
    public function banner()
    {
        return $this->hasMany(Banner::class);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }
    public function specificationsWithDetails()
    {
        return $this->hasMany(ProductSpecification::class)
            ->with(['specificationKey', 'specificationKeyType', 'specificationKeyTypeAttribute']);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class)->orderBy('id', 'desc');
    }

    // Method to get the average rating
    public function averageRating()
    {
        return $this->ratings()->avg('rating') ?: 0;
    }

    // // Relation with review
    public function review()
    {
        return $this->hasMany(Reviews::class, 'id', 'product_id');
    }

    public function orderDetails()
    {
        return DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereJsonContains('order_details.product_ids', $this->id)
            ->select('order_details.id', 'order_details.order_id', 'order_details.product_ids', 'order_details.details', 'orders.payment_status', 'orders.status', 'orders.is_cod', 'orders.is_refund_requested','orders.created_at');
//        dd($query->toSql(), $query->getBindings());
    }

    /**
     * Method to fetch the sales report for the product with optimized filtering.
     */
    public function getSalesReport(array $filters = [], $perPage = 15)
    {
        $query = $this->orderDetails()
            ->when(isset($filters['payment_status']) && $filters['payment_status'] !== null, function ($query) use ($filters) {
                $query->where('orders.payment_status', $filters['payment_status']);
            })
            ->when(isset($filters['status']) && $filters['status'] !== null, function ($query) use ($filters) {
                $query->where('orders.status', $filters['status']);
            })
            ->when(isset($filters['is_cod']), function ($query) use ($filters) {
                $query->where('orders.is_cod', $filters['is_cod']);
            })
            ->when(isset($filters['is_refund_requested']) && $filters['is_refund_requested'] !== null, function ($query) use ($filters) {
                $query->where('orders.is_refund_requested', $filters['is_refund_requested']);
            })->when(isset($filters['date_range']), function ($query) use ($filters) {
                if (!empty($filters['date_range']['from']) && !empty($filters['date_range']['to'])) {
                    $query->whereBetween('orders.created_at', [$filters['date_range']['from'], $filters['date_range']['to']]);
                }
            })->when(isset($filters['between']), function ($query) use ($filters) {
                // Apply 'between' filter only if 'date_range' is not set
                if (!isset($filters['date_range']['from'])) {
                    switch ($filters['between']) {
                        case 'last_day':
                            $query->whereDate('orders.created_at', Carbon::now()->subDay()->format('Y-m-d'));
                            break;

                        case 'last_week':
                            $query->whereBetween('orders.created_at', [
                                Carbon::now()->subWeek()->startOfDay()->format('Y-m-d'),
                                Carbon::now()->endOfDay()->format('Y-m-d')
                            ]);
                            break;

                        case 'last_month':
                            $query->whereBetween('orders.created_at', [
                                Carbon::now()->subMonth()->startOfDay()->format('Y-m-d'),
                                Carbon::now()->endOfDay()->format('Y-m-d')
                            ]);
                            break;

                        case 'last_year':
                            $query->whereBetween('orders.created_at', [
                                Carbon::now()->subYear()->startOfDay()->format('Y-m-d'),
                                Carbon::now()->endOfDay()->format('Y-m-d')
                            ]);
                            break;

                        default:
                            break;
                    }

                }
            });

        $orderDetails = $query->get();

        // Efficiently process order details to calculate total quantity sold and total revenue
        $reportData = $orderDetails->flatMap(function ($orderDetail) {
            $products = collect(json_decode($orderDetail->details, true)['products']);
            return $products->filter(fn($product) => $product['id'] == $this->id);
        })->reduce(function ($totals, $product) {
            $totals['total_quantity_sold'] += $product['qty'];
            $totals['total_revenue'] += $product['total_price'];
            return $totals;
        }, ['total_quantity_sold' => 0, 'total_revenue' => 0]);

        // Return the paginated results along with additional report data and filters
        return [
            'product_id'=>$this->id,
            'product_name' => $this->name,
            'image' => $this->thumb_image,
            'unit_price' => $this->unit_price,
            'stock' => $this->stock()->sum('stock') ?? 0,
            'purchase' => $this->purchase()->sum('quantity') ?? 0,
            'purchase_total' => $this->purchase()->sum('purchase_total_price') ?? 0,
            'total_quantity_sold' => $reportData['total_quantity_sold'],
            'total_revenue' =>$reportData['total_revenue'],
        ];
    }

}
