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
    /**
     * tạo đơn đặt hàng
     */
    public static function createNewOrder($userId, $validatedData, $total)
    {
        $orderId = 'ORD-' . strtoupper(uniqid());
        return self::create([
            'order_id' => $orderId,
            'user_id' => $userId,
            'total_amount' => $total,
            'shipping_name' => $validatedData['shipping_name'],
            'shipping_email' => $validatedData['shipping_email'],
            'shipping_phone' => $validatedData['shipping_phone'],
            'shipping_address' => $validatedData['shipping_address'],
            'payment_method' => $validatedData['payment_method'],
            'note' => $validatedData['note'] ?? null,
            'status' => 'pending',
        ]);
    }
    /**
     * Lấy đơn hàng của người dùng.
     *
     * @param int $userId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getUserOrders($userId)
    {
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /**
     * Lấy chi tiết đơn hàng của người dùng.
     *
     * @param int $orderId
     * @param int $userId
     * @return \App\Models\Order
     */
    public static function getOrderDetail($orderId, $userId)
    {
        return self::with(['orderDetails.product'])
            ->where('order_id', $orderId)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    /**
     * Tìm đơn hàng theo mã đơn và email.
     *
     * @param string $orderId
     * @param string $email
     * @return \App\Models\Order|null
     */
    public static function trackOrderByCodeAndEmail($orderId, $email)
    {
        return self::with(['orderDetails.product'])
            ->where('order_id', $orderId)
            ->where('shipping_email', $email)
            ->first();
    }
    /**
     * Hủy đơn hàng và hoàn lại số lượng sản phẩm vào kho.
     *
     * @return bool
     */
    public function cancelOrder()
    {
        if ($this->status !== 'pending') {
            return false; // Chỉ có thể hủy đơn hàng đang chờ
        }

        DB::beginTransaction();

        try {
            // Cập nhật trạng thái đơn hàng
            $this->status = 'cancelled';
            $this->save();

            // Hoàn lại số lượng tồn kho
            foreach ($this->orderDetails as $detail) {
                $product = $detail->product;
                $product->increment('stock_quantity', $detail->quantity);
                $product->decrement('sold_quantity', $detail->quantity);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Xác nhận nhận hàng và thay đổi trạng thái đơn hàng.
     *
     * @return bool
     */
    public function confirmReceived()
    {
        if ($this->status !== 'shipping') {
            return false; // Chỉ có thể xác nhận đơn hàng đang giao
        }

        // Cập nhật trạng thái đơn hàng thành 'completed'
        $this->status = 'completed';
        $this->save();

        return true;
    }
    public static function findByIdAndUser($id, $userId, $status)
    {
        return self::where('id', $id)
            ->where('user_id', $userId)
            ->where('status', $status)
            ->first();
    }
}
