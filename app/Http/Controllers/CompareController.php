<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class CompareController extends Controller
{
    private $limit = 4; // Max products to compare

    /**
     * Display the compare page.
     */
   public function index()
    {
        $productIds = session()->get('compare_list', []);

        // Eager load all necessary relationships for the compare table.
        // The 'brand' relationship has been removed as requested.
        $products = Product::whereIn('id', $productIds)
            ->with([
                'images',
                'attributeValues.attribute' // This correctly loads all generic attributes, including Brand
            ])
            ->get();
            
        return view('front.compare.index', compact('products'));
    }


    /**
     * Add a product to the compare list.
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid product.'], 422);
        }

        $compareList = session()->get('compare_list', []);
        $productId = $request->product_id;

        if (in_array($productId, $compareList)) {
            return response()->json(['success' => false, 'message' => 'Product is already in the compare list.']);
        }

        if (count($compareList) >= $this->limit) {
            return response()->json(['success' => false, 'message' => 'You can only compare up to ' . $this->limit . ' products.'], 403);
        }

        $compareList[] = $productId;
        session()->put('compare_list', $compareList);

        return response()->json([
            'success' => true,
            'message' => 'Product added to compare list!',
            'compare_count' => count($compareList)
        ]);
    }

    /**
     * Remove a product from the compare list.
     */
    public function remove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid product.'], 422);
        }
        
        $compareList = session()->get('compare_list', []);
        $productId = $request->product_id;

        // Find and remove the product ID
        $index = array_search($productId, $compareList);
        if ($index !== false) {
            unset($compareList[$index]);
        }
        
        session()->put('compare_list', array_values($compareList)); // Re-index array

        return response()->json([
            'success' => true,
            'message' => 'Product removed from compare list.',
            'compare_count' => count(session()->get('compare_list', []))
        ]);
    }

    public function clear()
    {
        session()->forget('compare_list');

        return response()->json([
            'success' => true,
            'message' => 'Compare list has been cleared!',
            'compare_count' => 0
        ]);
    }
}