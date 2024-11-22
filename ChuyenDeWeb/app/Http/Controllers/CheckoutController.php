<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Rules\NoSpace;
use App\Rules\NoSpecialCharacters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Hiển thị form thanh toán
     */
    public function showCheckoutForm()
    {
        // Lấy giỏ hàng của người dùng
        $cart = Cart::where('user_id', Auth::id())
            ->with('cartProducts.product')
            ->first();

        if (!$cart || $cart->cartProducts->isEmpty()) {
            return redirect()->route('cart.view')
                ->with('error', 'Giỏ hàng của bạn đang trống');
        }

        // Tính tổng tiền
        $total = $cart->cartProducts->sum(function ($item) {
            return optional($item->product)->price * $item->quantity;
        });

        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();

        return view('checkout', compact('cart', 'total', 'user'));
    }

    /**
     * Quá trình thanh toán
     */
    public function processCheckout(Request $request)
    {
        // Validate đầu vào
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email',
            'shipping_phone' => ['required', 'digits:10', 'regex:/^0[0-9]{9}$/', new NoSpecialCharacters, new NoSpace],
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cod,banking',
            'note' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Lấy giỏ hàng
            $cart = Cart::getUserCartWithProducts(Auth::id());

            if (!$cart || $cart->cartProducts->isEmpty()) {
                throw new \Exception('Giỏ hàng trống');
            }

            // Tính tổng tiền
            $total = $cart->calculateTotal();
            // Tạo đơn hàng mới
            $order = Order::createNewOrder(Auth::id(), $validated, $total);

            foreach ($cart->cartProducts as $item) {
                if (!$item->product->isStockAvailable($item->quantity)) {
                    return back()->with('danger', 'Số lượng trong kho không đủ cho sản phẩm: ' . $item->product->product_name);
                }

                OrderDetail::addOrderDetail($order->id, $item);
                $item->product->adjustStock($item->quantity);
            }

            // Xóa giỏ hàng
            $cart->clearCart();

            DB::commit();

            return redirect()->route('cart.view')->with('success', 'Đặt hàng thành công! Mã đơn hàng của bạn là: ' . $order->order_id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị form theo dõi đơn hàng
     */
    public function showTrackingForm()
    {
        return view('order.tracking-form');
    }

    /**
     * Đơn hàng của tôi
     */
    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order.my-orders', compact('orders'));
    }

    /**
     * Xem chi tiết đơn hàng
     */
    public function orderDetail($id)
    {
        $order = Order::with(['orderDetails.product'])
            ->where('order_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('order.detail', compact('order'));
    }

    /**
     * Tra cứu đơn hàng bằng mã đơn và email
     */
    public function trackOrder(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|string|max:20',
            'email' => 'required|email'
        ]);

        $order = Order::with(['orderDetails.product'])
            ->where('order_id', $validated['order_id'])
            ->where('shipping_email', $validated['email'])
            ->first();

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng với thông tin đã nhập');
        }

        return view('order.tracking-result', compact('order'));
    }

    /**
     * Hủy đơn hàng.
     */
    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            // Cập nhật trạng thái đơn hàng
            $order->status = 'cancelled';
            $order->save();

            // Hoàn lại số lượng tồn kho
            foreach ($order->orderDetails as $detail) {
                $product = $detail->product;
                $product->increment('stock_quantity', $detail->quantity);
                $product->decrement('sold_quantity', $detail->quantity);
            }

            DB::commit();
            return back()->with('success', 'Đơn hàng đã được hủy thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi hủy đơn hàng');
        }
    }

    /**
     * Xác nhận đơn hàng.
     */
    public function confirmReceived($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'shipping')
            ->firstOrFail();

        $order->status = 'completed';
        $order->save();

        return back()->with('success', 'Cảm ơn bạn đã xác nhận nhận hàng');
    }
}
