<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use App\Models\Wishlist;

class CartController extends Controller
{
    /**
     * Add a product to the cart or update its quantity.
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);
        $productId = $product->id;

        // Check if product already exists in cart, if so, update quantity
        if(isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $request->quantity;
        } else {
            // If not, add as a new item
            $cart[$productId] = [
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->offer_price > 0 ? $product->offer_price : $product->selling_price,
                "image" => $product->images->isNotEmpty() ? $product->images->first()->image_path : null
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart!',
            'cartData' => $this->getCartDataForAjax()
        ]);
    }

    /**
     * Update the quantity of a specific item in the cart.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if(isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $request->quantity;
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated!',
                'cartData' => $this->getCartDataForAjax()
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 404);
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $cart = session()->get('cart');
        $productId = $request->product_id;

        if(isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart!',
                'cartData' => $this->getCartDataForAjax()
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 404);
    }

    /**
     * Display the full cart page.
     */
    public function show()
    {
        // This function now correctly returns all necessary variables to the view.
        $cartData = $this->getCartData();
        return view('front.cart.show', $cartData);
    }

    /**
     * Apply a coupon to the cart.
     */
    public function applyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), ['code' => 'required|string']);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Coupon code is required.'], 422);
        }

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->status || ($coupon->expires_at && $coupon->expires_at->isPast()) || ($coupon->usage_limit !== null && $coupon->times_used >= $coupon->usage_limit)) {
            return response()->json(['success' => false, 'message' => 'This coupon is invalid or has expired.'], 404);
        }
        
        $cartData = $this->getCartData();
        if ($coupon->min_amount > 0 && $cartData['subtotalRaw'] < $coupon->min_amount) {
            return response()->json(['success' => false, 'message' => 'Your order total must be at least ' . $coupon->min_amount . '৳ to use this coupon.'], 403);
        }

        $discount = ($coupon->type === 'percent') ? (($cartData['subtotalRaw'] * $coupon->value) / 100) : $coupon->value;

        session()->put('coupon', ['code' => $coupon->code, 'discount' => $discount]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'cartData' => $this->getCartData()
        ]);
    }

    /**
     * Remove the applied coupon from the session.
     */
    public function removeCoupon()
    {
        session()->forget('coupon');
        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
            'cartData' => $this->getCartData()
        ]);
    }

    /**
     * Get the current state of the cart for initial AJAX updates.
     */
    public function getCartContents()
    {
        return response()->json($this->getCartDataForAjax());
    }

    /**
     * Helper function to calculate totals and format data for the Blade view.
     */
    private function getCartData()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $coupon = session()->get('coupon');
        $discount = $coupon['discount'] ?? 0;
        $total = $subtotal - $discount;

        return [
            'cart' => $cart,
            'subtotalRaw' => $subtotal,
            'discountRaw' => $discount,
            'totalRaw' => $total,
            'subtotal' => number_format($subtotal),
            'discount' => number_format($discount),
            'total' => number_format($total),
            'coupon' => $coupon
        ];
    }

    /**
     * Helper function to get cart data specifically formatted for AJAX responses (like the sidebar).
     */
       private function getCartDataForAjax()
    {
        // Get all calculated totals and cart data
        $cartData = $this->getCartData(); 
        $totalItems = 0;
        foreach ($cartData['cart'] as $details) {
            $totalItems += $details['quantity'];
        }

        // --- ADDED LOGIC: Get other header counts ---
        $wishlistCount = Auth::check() ? Wishlist::where('user_id', Auth::id())->count() : 0;
        $compareCount = count(session()->get('compare_list', []));
        // --- END ADDED LOGIC ---

        // Return a comprehensive data object with all counts
        return [
            'sidebar_html' => view('front.include._cart_items', ['cart' => $cartData['cart']])->render(),
            'main_cart_html' => view('front.cart._main_cart_table_body', ['cart' => $cartData['cart']])->render(),
            'subtotal' => $cartData['subtotal'],
            'discount' => $cartData['discount'],
            'total' => $cartData['total'],
            'totalItems' => $totalItems,
            'coupon' => $cartData['coupon'],
            'wishlist_count' => $wishlistCount, // <-- Add this
            'compare_count' => $compareCount   // <-- Add this
        ];
    }
}