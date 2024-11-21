<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thực hiện chức năng này']);
        }

        $productId = $request->input('product_id');
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Sản phẩm không tồn tại']);
        }

        // Lấy giỏ hàng của người dùng hiện tại
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Thêm sản phẩm vào giỏ hàng (nếu đã có thì tăng số lượng)
        $cartProduct = CartProduct::where('cart_id', $cart->cart_id)->where('product_id', $productId)->first();
        if ($cartProduct) {
            $cartProduct->quantity += 1;
            $cartProduct->save();
        } else {
            $cart->cartProducts()->create([
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Thêm sản phẩm vào giỏ hàng thành công']);
    }

    public function viewCart()
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Vui lòng đăng nhập để xem giỏ hàng của bạn.');
        }

        // Lấy giỏ hàng của người dùng và các sản phẩm trong giỏ
        $cart = Cart::with('cartProducts.product')
            ->where('user_id', Auth::id())
            ->first();

        // Nếu giỏ hàng rỗng, gửi thông báo
        $cartItems = $cart ? $cart->cartProducts : collect();

        return view('cart', compact('cartItems'));
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request)
    {
        Log::info('Update cart request received', [
            'request' => $request->all(),
            'user' => Auth::id()
        ]);

        $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng không tồn tại'
            ], 404);
        }

        $cartProduct = CartProduct::where('cart_id', $cart->cart_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartProduct) {
            $cartProduct->update(['quantity' => $request->quantity]);

            // Tính toán lại tổng tiền của sản phẩm và tổng giỏ hàng
            $itemTotal = $cartProduct->quantity * $cartProduct->product->price;
            $cartTotal = $cart->cartProducts->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật số lượng thành công',
                'itemTotal' => $itemTotal,
                'cartTotal' => $cartTotal, // Trả về tổng tiền mới
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Sản phẩm không tồn tại trong giỏ hàng'
        ], 404);
    }



    public function remove(Request $request)
    {
        Log::info('Remove from cart request received', [
            'request' => $request->all(),
            'user' => Auth::id()
        ]);

        try {
            $request->validate([
                'product_id' => 'required|exists:product,product_id'
            ]);

            $cart = Cart::where('user_id', Auth::id())->first();

            if (!$cart) {
                Log::error('Cart not found for user', ['user_id' => Auth::id()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng không tồn tại'
                ], 404);
            }

            // Xóa sản phẩm khỏi giỏ hàng
            $deleted = CartProduct::where('cart_id', $cart->cart_id)
                ->where('product_id', $request->product_id)
                ->delete();

            Log::info('Product removed from cart', [
                'deleted' => $deleted,
                'cart_id' => $cart->cart_id,
                'product_id' => $request->product_id
            ]);

            // Tính toán lại tổng tiền giỏ hàng sau khi xóa sản phẩm
            $cartTotal = $cart->cartProducts->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
                'cartTotal' => $cartTotal, // Trả về tổng tiền mới
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing from cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
