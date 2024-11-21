<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'search', 'date_from', 'date_to']);

        $orders = Order::with(['user', 'orderDetails.product'])
            ->filterOrders($filters)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with(['orderDetails.product', 'user'])
            ->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // Cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipping,completed,cancelled',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $oldStatus = $order->status;
            $newStatus = $request->status;

            $order->status = $newStatus;
            $order->save();

            // Xử lý logic khi hủy đơn hàng
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->orderDetails as $detail) {
                    $product = $detail->product;
                    $product->increment('stock_quantity', $detail->quantity);
                    $product->decrement('sold_quantity', $detail->quantity);
                }
            }

            // Xử lý logic khi khôi phục đơn hàng đã hủy
            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->orderDetails as $detail) {
                    $product = $detail->product;
                    $product->decrement('stock_quantity', $detail->quantity);
                    $product->increment('sold_quantity', $detail->quantity);
                }
            }

            DB::commit();

            return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Hiển thị trang thống kê đơn hàng
     */
    public function statistics(Request $request)
    {
        // Xử lý khoảng thời gian
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->get('date_to', Carbon::now());

        // Gọi các phương thức trong model
        $overview = Order::getOrdersOverview($dateFrom, $dateTo);
        $statusStats = Order::getOrdersByStatus($dateFrom, $dateTo);
        $dailyStats = Order::getDailyOrderStats($dateFrom, $dateTo);
        $topProducts = Order::getTopSellingProducts($dateFrom, $dateTo);

        return view('orders.statistics', compact(
            'overview',
            'statusStats',
            'dailyStats',
            'topProducts',
            'dateFrom',
            'dateTo'
        ));
    }

}
