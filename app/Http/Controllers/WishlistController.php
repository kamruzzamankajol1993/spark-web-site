<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product; // Add this
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class WishlistController extends Controller
{


    /**
     * Move an item from the wishlist to the cart.
     */
    public function moveToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid product.'], 422);
        }

        $userId = Auth::id();
        $productId = $request->product_id;

        // 1. Find the product and the wishlist item
        $product = Product::find($productId);
        $wishlistItem = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

        if (!$product || !$wishlistItem) {
            return response()->json(['success' => false, 'message' => 'Item not found in wishlist.'], 404);
        }

        // 2. Add the item to the cart (using your existing cart logic)
        $cart = session()->get('cart', []);
        if(isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->offer_price > 0 ? $product->offer_price : $product->selling_price,
                "image" => $product->images->isNotEmpty() ? $product->images->first()->image_path : null
            ];
        }
        session()->put('cart', $cart);

        // 3. Remove the item from the wishlist
        $wishlistItem->delete();

        // 4. Get updated counts
        $wishlistCount = Wishlist::where('user_id', $userId)->count();
        $cartCount = 0;
        foreach (session()->get('cart', []) as $item) {
            $cartCount += $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'message' => 'Product moved to cart!',
            'wishlist_count' => $wishlistCount,
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with('product.images') // Eager load product and its images
            ->latest()
            ->get();
            
        return view('front.wishlist.index', compact('wishlistItems'));
    }

    /**
     * Add a product to the user's wishlist.
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid product.'], 422);
        }

        // Check if the item is already in the wishlist
        $exists = Wishlist::where('user_id', Auth::id())
                           ->where('product_id', $request->product_id)
                           ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Product is already in your wishlist.']);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);
        
        // Return the updated count
        $count = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist!',
            'wishlist_count' => $count
        ]);
    }

    /**
     * Remove a product from the user's wishlist.
     */
    public function remove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid product.'], 422);
        }

        Wishlist::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->delete();

        $count = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist.',
            'wishlist_count' => $count
        ]);
    }
}