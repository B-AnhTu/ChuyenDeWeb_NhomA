<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Order extends Model
{

    protected $fillable = [
        'order_id',
        'user_id',
        'total_amount',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'payment_method',
        'note',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Scope để lọc và tìm kiếm đơn hàng.
     */
    public function scopeFilterOrders(Builder $query, array $filters)
    {
        // Lọc theo trạng thái
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Tìm kiếm theo mã đơn hàng, email, hoặc số điện thoại
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhere('shipping_email', 'like', "%{$search}%")
                    ->orWhere('shipping_phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo khoảng thời gian
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query;
    }

    /**
     * Lấy thống kê tổng quan
     */
    public static function getOrdersOverview($dateFrom, $dateTo)
    {
        return [
            'total_orders' => self::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'total_revenue' => self::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'completed')
                ->sum('total_amount'),
            'average_order_value' => self::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'completed')
                ->avg('total_amount'),
            'total_customers' => self::whereBetween('created_at', [$dateFrom, $dateTo])
                ->distinct('user_id')
                ->count(),
        ];
    }

    /**
     * Lấy thống kê theo trạng thái đơn hàng
     */
    public static function getOrdersByStatus($dateFrom, $dateTo)
    {
        return self::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('status', DB::raw('count(*) as total'), DB::raw('sum(total_amount) as revenue'))
            ->groupBy('status')
            ->get();
    }

    /**
     * Lấy thống kê đơn hàng theo ngày
     */
    public static function getDailyOrderStats($dateFrom, $dateTo)
    {
        return self::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total_orders'),
                DB::raw('sum(total_amount) as daily_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Lấy danh sách top sản phẩm bán chạy
     */
    public static function getTopSellingProducts($dateFrom, $dateTo)
    {
        return DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('product', 'order_details.product_id', '=', 'product.product_id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->where('orders.status', 'completed')
            ->select(
                'product.product_id',
                'product.product_name',
                DB::raw('sum(order_details.quantity) as total_quantity'),
                DB::raw('sum(order_details.quantity * order_details.price) as total_revenue')
            )
            ->groupBy('product.product_id', 'product.product_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
    }
}
