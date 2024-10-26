<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $cartItems = $cart ? $cart->cartProducts : [];

        return view('cart', compact('cartItems'));
    }
}
