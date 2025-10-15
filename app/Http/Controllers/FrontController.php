<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Product;
use App\Models\AnimationCategory;
use App\Models\BundleOfferProduct;
use App\Models\SliderControl;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Subcategory;
use App\Models\HighlightProduct;
use App\Models\AssignCategory;
use App\Models\FeaturedCategory;
use App\Models\BundleOffer;
use App\Models\Size;
use App\Models\HomepageSection; 
use App\Models\HeroLeftSlider;
use App\Models\HeroRightSlider;
use App\Models\FooterBanner;
use App\Models\ExtraCategory;
use App\Models\AreaWisePrice; 
use App\Models\HeroSection;
use App\Models\FlashSale;
use App\Models\HomePageDescription;
use Illuminate\Support\Facades\Auth; // Add this at the top
use App\Models\Wishlist; // Add this at the top
class FrontController extends Controller
{

public function getHeaderCounts()
{
    $wishlistCount = 0;
    if (Auth::check()) {
        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
    }
    
    $compareCount = count(session()->get('compare_list', []));

    return response()->json([
        'wishlist_count' => $wishlistCount,
        'compare_count' => $compareCount,
    ]);
}
    private function applyFilters(\Illuminate\Http\Request $request, $query)
{

    // NEW: Handle the search query filter
    if ($request->filled('query')) {
        $query->where('name', 'LIKE', '%' . $request->input('query') . '%');
    }
    // Filter by Category, Subcategory, or Animation Category
    $categoryId = $request->input('category_id') ?: $request->input('subcategory_id');
    if ($categoryId) {
        $productIds = \App\Models\AssignCategory::where('category_id', $categoryId)->where('type', 'product_category')->pluck('product_id');
        $query->whereIn('id', $productIds);
    }
    if ($request->filled('animation_category_id')) {
        $productIds = \App\Models\AssignCategory::where('category_id', $request->animation_category_id)->where('type', 'animation')->pluck('product_id');
        $query->whereIn('id', $productIds);
    }

    // Filter by Price Range
    if ($request->filled('min_price') && $request->min_price > 0) {
        $query->where('base_price', '>=', (float)$request->min_price);
    }
    if ($request->filled('max_price') && $request->max_price < 10000) {
        $query->where('base_price', '<=', (float)$request->max_price);
    }

    // Filter by Stock Status
    if ($request->filled('stock_status')) {
        if ($request->stock_status === 'offer') {
            $query->whereNotNull('discount_price')->where('discount_price', '>', 0);
        } elseif ($request->stock_status === 'in_stock') {
            $query->whereHas('variants', fn($q) => $q->whereJsonLength('sizes', '>', 0));
        }
    }

    // Filter by Size
    if ($request->filled('sizes') && is_array($request->sizes)) {
        $selectedSizeNames = $request->sizes;
        $sizeIds = \App\Models\Size::whereIn('name', $selectedSizeNames)->pluck('id')->toArray();
        if (!empty($sizeIds)) {
            $query->whereHas('variants', function ($variantQuery) use ($sizeIds) {
                $variantQuery->where(function ($q) use ($sizeIds) {
                    foreach ($sizeIds as $sizeId) {
                        $q->orWhereJsonContains('sizes', ['size_id' => (string)$sizeId]);
                    }
                });
            });
        }
    }

    // Sorting Logic
    $sortBy = $request->input('sort_by', 'newest');
    switch ($sortBy) {
        case 'price_asc':
            $query->orderByRaw('ISNULL(discount_price), discount_price ASC, base_price ASC');
            break;
        case 'price_desc':
            $query->orderByRaw('ISNULL(discount_price), discount_price DESC, base_price DESC');
            break;
        case 'name_asc':
            $query->orderBy('name', 'asc');
            break;
        case 'popularity':
        case 'newest':
        default:
            $query->latest();
            break;
    }

    return $query;
}


  /**
     * Handle AJAX search requests for products.
     */
    public function ajaxSearch(Request $request)
    {
        $query = $request->input('query');

        if (!$query || strlen($query) < 1) {
            return response()->json([]);
        }

        $frontEndData = DB::table('system_information')->first();

        $products = Product::where('status', 1)
                           ->where('name', 'LIKE', "%{$query}%")
                           ->with('images')
                           ->take(10)
                           ->get();

        $formattedProducts = $products->map(function ($product) use ($frontEndData) {
            $imagePath = $product->images->first()->image_path ?? null;
            $imageUrl = $imagePath
                ? $frontEndData->main_url . 'public/' . $imagePath
                : 'https://placehold.co/50x50?text=N/A';
            
            // --- THIS IS THE FIX ---
            // Send raw, unformatted numbers to the JavaScript.
            $basePrice = $product->selling_price;
            $discountPrice = ($product->offer_price > 0 && $product->offer_price < $product->selling_price) 
                ? $product->offer_price 
                : 0;

            return [
                'name' => $product->name,
                'url' => route('product.show', $product->slug),
                'image_url' => $imageUrl,
                'base_price' => $basePrice,
                'discount_price' => $discountPrice,
            ];
        });

        return response()->json($formattedProducts);
    }


    public function offerProduct($id)
    {
        $bundleDeal = BundleOfferProduct::findOrFail($id);
         $bundleDeal->increment('view_count');
        $productIds = $bundleDeal->product_id;
        $productsCollection = collect();
        $allImages = [];
        $totalBasePrice = 0;

        if (!empty($productIds) && is_array($productIds)) {
            $productsCollection = Product::whereIn('id', $productIds)
                ->with([
                    'variants.color', 
                    'productCategoryAssignment.category',
                    'reviews.user',
                    'reviews.images'
                ])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->get();

            // Collect images from all available products for the gallery
            foreach ($productsCollection as $product) {
                if (is_array($product->main_image) && count($product->main_image) > 0) {
                    $allImages = array_merge($allImages, $product->main_image);
                }
            }

            // --- START: CORRECTED PRICE CALCULATION ---
            // Key products by ID for efficient lookup.
            $productsKeyedById = $productsCollection->keyBy('id');

            // Determine how many products to sum based on 'buy_quantity'.
            $quantityToConsider = (isset($bundleDeal->buy_quantity) && $bundleDeal->buy_quantity > 0)
                                  ? (int)$bundleDeal->buy_quantity
                                  : count($bundleDeal->product_id);
            
            // Get the specific number of product IDs from the start of the array.
            $productIdsToSum = array_slice($bundleDeal->product_id, 0, $quantityToConsider);

            // Calculate the total base price for only those products.
            foreach ($productIdsToSum as $pid) {
                if (isset($productsKeyedById[$pid])) {
                    $totalBasePrice += $productsKeyedById[$pid]->base_price;
                }
            }
            // --- END: CORRECTED PRICE CALCULATION ---
        }

        $allImages = array_unique($allImages);
$areaWisePrice = AreaWisePrice::all();
        return view('front.offer.offerproduct', compact(
            'bundleDeal',
            'productsCollection',
            'allImages',
            'totalBasePrice',
            'areaWisePrice'
        ));
    }
public function getBundleViewCount($id)
{
    $bundle = BundleOfferProduct::find($id, ['view_count']);
    
    if ($bundle) {
        return response()->json(['success' => true, 'view_count' => $bundle->view_count]);
    }

    return response()->json(['success' => false, 'message' => 'Bundle not found'], 404);
}
   public function quickView($id)
{
    // 1. Fetch the product without the old 'category' relationship
    $product = Product::with(['variants.color', 'productCategoryAssignment.category'])
        ->findOrFail($id);
    
    // 2. Find the category assignment from the pivot table
    $assignedCategory = AssignCategory::where('product_id', $product->id)->first();
    
    // 3. If an assignment exists, load the full Category model
    if ($assignedCategory) {
        $category = Category::find($assignedCategory->category_id);
        if ($category) {
            // Manually set the 'category' relation on the product object
            // This allows the view to still use `$product->category`
            $product->setRelation('category', $category);
        }
    }
    
    return view('front.include.quick_view_modal_content', compact('product'));
}

     public function product(Request $request, $slug)
{
    $product = Product::where('slug', $slug)
        ->where('status', 1) // Assuming status 1 is for active products
        ->with([
            'images',
            'category.parents', // Eager load parent category for breadcrumbs
            'brand',
            'stock',
            'attributeValues.attribute', // Eager load the attribute name for each value
            'reviews.user', // Eager load the user who wrote the review
        ])
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->firstOrFail();

    // Fetch related products from the same category
    $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id) // Exclude the current product
                                ->where('status', 1)
                                ->with('images')
                                ->inRandomOrder()
                                ->take(7) // You can adjust the number of related products
                                ->get();

    return view('front.product.show', compact('product', 'relatedProducts'));
}

public function getProductViewCount($id)
{
    $product = Product::find($id, ['view_count']);
    
    if ($product) {
        return response()->json(['success' => true, 'view_count' => $product->view_count]);
    }

    return response()->json(['success' => false, 'message' => 'Product not found'], 404);
}
    public function offers()
{
    // Find the 'discount' category details to pass to the view for context
    $extraCategory = ExtraCategory::where('slug', 'discount')->firstOrFail();

    // The rest of the function remains the same...
    $getAllid = AssignCategory::where('category_name', 'discount')->pluck('product_id');

    $products = Product::where('status', 1)
        ->whereIn('id', $getAllid)
        ->with(['category', 'variants', 'productCategoryAssignment.category']) ->withCount('reviews')
    ->withAvg('reviews', 'rating')
        ->latest()
        ->paginate(12);

    $productIdsOnPage = $products->pluck('id');
    if ($productIdsOnPage->isNotEmpty()) {
        $assignments = AssignCategory::whereIn('product_id', $productIdsOnPage)->get()->keyBy('product_id');
        $categoryIds = $assignments->pluck('category_id')->unique();
        $categories = Category::whereIn('id', $categoryIds)->get()->keyBy('id');

        foreach ($products as $product) {
            $assignment = $assignments->get($product->id);
            if ($assignment) {
                $category = $categories->get($assignment->category_id);
                $product->setRelation('category', $category);
            }
        }
    }
        
    $sizes = Size::where('status', 1)->get();

    // Pass the new $extraCategory variable to the view
    return view('front.discount.list', compact('products', 'sizes', 'extraCategory'));
}


public function extra_category_offer(Request $request, $slug)
{
    // 1. Find the details of the extra category itself.
    $extraCategory = ExtraCategory::where('slug', $slug)->firstOrFail();

    // 2. Get all product IDs assigned to this extra category.
    $productIds = AssignCategory::where('category_name', $slug)->pluck('product_id');

    // 3. Create the base query for these products.
    $query = Product::whereIn('id', $productIds)->where('status', 1);

    // 4. IMPORTANT: Apply all additional filters from the URL.
    $query = $this->applyFilters($request, $query);

    // 5. Paginate the final, filtered results.
    $products = $query->with(['variants', 'productCategoryAssignment.category'])
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->paginate(12);
        
    // 6. Fetch available sizes for the filter sidebar.
    $sizes = Size::where('status', 1)->get();

    return view('front.discount.list', compact('products', 'sizes', 'extraCategory'));
}

public function ajaxDiscountFilter(Request $request)
{
    // Get the category slug from the request.
    $slug = $request->input('extra_category_slug');

    // 1. Get the base product IDs for this extra category.
    $productIds = AssignCategory::where('category_name', $slug)->pluck('product_id');
    
    // 2. Start the query from the correct set of products.
    $query = Product::whereIn('id', $productIds)->where('status', 1);
    
    // 3. Use the central helper to apply all other filters.
    $query = $this->applyFilters($request, $query);

    $products = $query->with(['variants', 'productCategoryAssignment.category'])
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->paginate(12);

    // Manually load categories for the current page of products
    // ... (your existing manual category loading logic can remain here) ...

    $html = view('front.category.product_card_partial', compact('products'))->render();

    return response()->json([
        'html' => $html,
        'hasMorePages' => $products->hasMorePages(),
    ]);
}

  public function index()
{
    // Fetch the hero section data from the database
    $heroSection = HeroSection::first();

      $featuredCategories = Category::where('status', 1)
                                      ->where('is_featured', 1) // This gets only featured categories
                                      ->get();

    // --- START: FLASH SALE LOGIC ---
        // Find the currently active flash sale
        $activeFlashSale = FlashSale::where('status', true)
                                      ->where('start_date', '<=', now())
                                      ->where('end_date', '>=', now())
                                      ->orderBy('start_date', 'desc') // Get the most recent one if multiple are active
                                      ->first();

        // Initialize an empty collection for products
        $flashSaleProducts = collect();

        // If an active sale is found, get its latest 8 products
        if ($activeFlashSale) {
            $flashSaleProducts = $activeFlashSale->products()
            ->with('images') 
                                               ->latest('flash_sale_product.created_at') // Order by when they were added to the sale
                                               ->take(8)
                                               ->get();
        }
        // --- END: FLASH SALE LOGIC ---
    $homePageDescription = HomePageDescription::first();
        // Pass the new variables to the view

        // --- ADD THIS LOGIC ---
    $wishlistProductIds = [];
    if (Auth::check()) {
        $wishlistProductIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
    }
    // --- END ADDED LOGIC ---
        return view('front.index', compact('heroSection', 'featuredCategories', 'activeFlashSale', 'flashSaleProducts', 'homePageDescription', 'wishlistProductIds'));
    }



    
public function category(Request $request, $slug)
{

    // --- ADD THIS LOGIC ---
    $wishlistProductIds = [];
    if (Auth::check()) {
        $wishlistProductIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
    }
    // --- END ADDED LOGIC ---
    $category = Category::where('slug', $slug)->firstOrFail();

    // Get attributes specifically linked to this category that have options
    $attributeIds = \DB::table('attribute_category')
                       ->where('category_id', $category->id)
                       ->distinct()
                       ->pluck('attribute_id');

    $filterableAttributes = Attribute::whereIn('id', $attributeIds)
                                     ->whereHas('options')
                                     ->with('options')
                                     ->get();

    // Start the query for products in this category
    $query = Product::where('category_id', $category->id)
                        ->where('status', 1);

    // --- APPLY FILTERS FROM REQUEST ON INITIAL LOAD ---

    // Filter by Price Range
    if ($request->filled('min_price') && $request->filled('max_price')) {
        $minPrice = $request->min_price;
        $maxPrice = $request->max_price;
        $isDefaultPriceRange = ($minPrice == 1000 && $maxPrice == 500000);
        if (!$isDefaultPriceRange) {
            $query->where(function ($q) use ($minPrice, $maxPrice) {
                $q->where(function ($sq) use ($minPrice, $maxPrice) {
                    $sq->where('offer_price', '>', 0)
                       ->whereBetween('offer_price', [$minPrice, $maxPrice]);
                })->orWhere(function ($sq) use ($minPrice, $maxPrice) {
                    $sq->where(fn($ssq) => $ssq->where('offer_price', '=', 0)->orWhereNull('offer_price'))
                       ->whereBetween('selling_price', [$minPrice, $maxPrice]);
                });
            });
        }
    }

    // Filter by Availability
    if ($request->filled('availability') && is_array($request->availability)) {
        if (in_array('in_stock', $request->availability)) {
            $query->whereHas('stock', function ($q) {
                $q->where('quantity', '>', 0);
            });
        }
    }

    // Filter by Dynamic Attributes
    $attributes = $request->input('attributes');
    if (is_array($attributes)) {
        $attributes = array_filter($attributes);
    }
    if (!empty($attributes)) {
        foreach ($attributes as $attributeId => $values) {
            if (is_array($values) && !empty($values)) {
                $query->whereHas('attributeValues', function ($subQuery) use ($attributeId, $values) {
                    $subQuery->where('attribute_id', $attributeId)
                                ->whereIn('value', $values);
                });
            }
        }
    }

    // Apply Sorting
    $sortBy = $request->input('sort_by', 'default');
    switch ($sortBy) {
        case 'price_asc':
            $query->orderByRaw('ISNULL(offer_price), offer_price ASC, selling_price ASC');
            break;
        case 'price_desc':
            $query->orderByRaw('ISNULL(offer_price), offer_price DESC, selling_price DESC');
            break;
        case 'newest':
        default:
            $query->latest();
            break;
    }

    // Paginate the final, filtered results for the initial page load
    $products = $query->with('images')->paginate(20);

    return view('front.category.category', compact('wishlistProductIds', 'category', 'filterableAttributes', 'products'));
}

public function filterProducts(Request $request)
{
    // Start the base query for active products
    $query = Product::where('status', 1);

    // 1. Filter by the Category
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // 2. Filter by Price Range
    if ($request->filled('min_price') && $request->filled('max_price')) {
        $minPrice = $request->min_price;
        $maxPrice = $request->max_price;
        $isDefaultPriceRange = ($minPrice == 1000 && $maxPrice == 500000);
        if (!$isDefaultPriceRange) {
            $query->where(function ($q) use ($minPrice, $maxPrice) {
                $q->where(function ($sq) use ($minPrice, $maxPrice) {
                    $sq->where('offer_price', '>', 0)
                       ->whereBetween('offer_price', [$minPrice, $maxPrice]);
                })->orWhere(function ($sq) use ($minPrice, $maxPrice) {
                    $sq->where(fn($ssq) => $ssq->where('offer_price', '=', 0)->orWhereNull('offer_price'))
                       ->whereBetween('selling_price', [$minPrice, $maxPrice]);
                });
            });
        }
    }

    // 3. Filter by Availability
    if ($request->filled('availability') && is_array($request->availability)) {
        if (in_array('in_stock', $request->availability)) {
            $query->whereHas('stock', function ($q) {
                $q->where('quantity', '>', 0);
            });
        }
    }

    // 4. Filter by Dynamic Attributes --- THE FIX IS HERE ---
    // We use !empty() for the most reliable check on the incoming array.
    
        // 1. Get the attributes from the request.
    $attributes = $request->input('attributes');

    // 2. Ensure it's an array and clean it by removing any empty/null values.
    if (is_array($attributes)) {
        $attributes = array_filter($attributes);
    }

    // 3. Now, check if the CLEANED array has any data.
    if (!empty($attributes)) {
        foreach ($attributes as $attributeId => $values) {
            // Also check if the inner value array is not empty
            if (is_array($values) && !empty($values)) {
                $query->whereHas('attributeValues', function ($subQuery) use ($attributeId, $values) {
                    $subQuery->where('attribute_id', $attributeId)
                                ->whereIn('value', $values);
                });
            }
        }
    }
    

    // 5. Apply Sorting
    $sortBy = $request->input('sort_by', 'default');
    switch ($sortBy) {
        case 'price_asc':
            $query->orderByRaw('ISNULL(offer_price), offer_price ASC, selling_price ASC');
            break;
        case 'price_desc':
            $query->orderByRaw('ISNULL(offer_price), offer_price DESC, selling_price DESC');
            break;
        case 'newest':
        default:
            $query->latest();
            break;
    }

    // Paginate the final results
    $products = $query->with('images')->paginate(20);

    // Prepare the HTML response
    $html = '';
    if ($products->isNotEmpty()) {
        foreach ($products as $product) {
            $html .= view('front.product._product-card', compact('product'))->render();
        }
    }

    // Return the final JSON response
    return response()->json([
        'html' => $html,
        'hasMorePages' => $products->hasMorePages(),
    ]);
}
 

   public function productSearch(Request $request)
    {
        $searchQuery = $request->input('query');
        if (!$searchQuery) {
            return redirect()->route('shop.show');
        }

        $wishlistProductIds = Auth::check() ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray() : [];

        // Base query for the search
        $query = Product::where('status', 1)->where('name', 'LIKE', '%' . $searchQuery . '%');

        // --- NEW: Find filterable attributes for the search results ---
        $productIdsForFilters = $query->pluck('id');
        $attributeIds = \App\Models\ProductAttributeValue::whereIn('product_id', $productIdsForFilters)
                                                         ->distinct()
                                                         ->pluck('attribute_id');
        $filterableAttributes = Attribute::whereIn('id', $attributeIds)
                                         ->whereHas('options')
                                         ->with('options')
                                         ->get();
        // --- END NEW ---

        // Now apply filters from the URL for the initial page load
        $filteredQuery = $this->applySearchFilters($request, clone $query);
        $products = $filteredQuery->with('images', 'stock')->latest()->paginate(16)->appends($request->all());

        return view('front.main.search_results', compact(
            'products', 
            'searchQuery', 
            'wishlistProductIds',
            'filterableAttributes'
        ));
    }

    /**
     * NEW: Handle AJAX filter requests specifically for the search page.
     */
    public function ajaxSearchFilter(Request $request)
    {
        $searchQuery = $request->input('query');
        if (!$searchQuery) {
            return response()->json(['html' => '', 'hasMorePages' => false]);
        }

        $wishlistProductIds = Auth::check() ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray() : [];

        // Start with the base search query
        $query = Product::where('status', 1)->where('name', 'LIKE', '%' . $searchQuery . '%');

        // Apply additional filters from the sidebar
        $query = $this->applySearchFilters($request, $query);

        $products = $query->with('images', 'stock')->paginate(16);

        $html = '';
        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                // Pass wishlistProductIds to the partial
                $html .= view('front.product._product-card', compact('product', 'wishlistProductIds'))->render();
            }
        }

        return response()->json([
            'html' => $html,
            'hasMorePages' => $products->hasMorePages(),
        ]);
    }

    /**
     * NEW: A dedicated filter helper for search and category pages.
     */
    private function applySearchFilters(Request $request, $query)
    {
        // Price Range
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $minPrice = $request->min_price;
            $maxPrice = $request->max_price;
            if ($minPrice != 1000 || $maxPrice != 500000) { // Check if not default
                 $query->where(function ($q) use ($minPrice, $maxPrice) {
                    $q->whereBetween('selling_price', [$minPrice, $maxPrice])
                      ->orWhere(function($sq) use ($minPrice, $maxPrice) {
                          $sq->where('offer_price', '>', 0)->whereBetween('offer_price', [$minPrice, $maxPrice]);
                      });
                });
            }
        }

        // Availability
        if ($request->filled('availability') && in_array('in_stock', $request->availability)) {
            $query->whereHas('stock', fn($q) => $q->where('quantity', '>', 0));
        }

        // Dynamic Attributes
        $attributes = $request->input('attributes');
        if (is_array($attributes) && !empty(array_filter($attributes))) {
            foreach (array_filter($attributes) as $attributeId => $values) {
                if (is_array($values) && !empty($values)) {
                    $query->whereHas('attributeValues', function ($subQuery) use ($attributeId, $values) {
                        $subQuery->where('attribute_id', $attributeId)->whereIn('value', $values);
                    });
                }
            }
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'default');
        switch ($sortBy) {
            case 'price_asc': $query->orderByRaw('ISNULL(offer_price), offer_price ASC, selling_price ASC'); break;
            case 'price_desc': $query->orderByRaw('ISNULL(offer_price), offer_price DESC, selling_price DESC'); break;
            case 'newest':
            default: $query->latest(); break;
        }

        return $query;
    }

   

     /**
     * NEW dedicated function to handle AJAX filter requests for the shop page.
     */
    public function ajaxShopFilter(Request $request)
{
    $query = Product::where('status', 1);

    // শুধু একটি লাইন দিয়ে সব ফিল্টার প্রয়োগ করুন
    $query = $this->applyFilters($request, $query);

    $products = $query->with(['category', 'variants', 'productCategoryAssignment.category'])->withCount('reviews')
    ->withAvg('reviews', 'rating')->paginate(12);

    $html = view('front.category.product_card_partial', compact('products'))->render();

    return response()->json([
        'html' => $html,
        'hasMorePages' => $products->hasMorePages(),
    ]);
}

        public function subcategory(Request $request, $slug)
{
    $subcategory = Category::where('slug', $slug)->whereNotNull('parent_id')->firstOrFail();
    $category = $subcategory->parent;

    // 1. Get all product IDs for the base subcategory.
    $productIds = AssignCategory::where('category_id', $subcategory->id)->where('type','product_category')->pluck('product_id');

    // 2. Create the initial query for products in this subcategory.
    $query = Product::whereIn('id', $productIds)->where('status', 1);

    // 3. IMPORTANT: Apply all filters from the URL.
    $query = $this->applyFilters($request, $query);

    // 4. Paginate the final, filtered results.
    $products = $query->with(['variants', 'productCategoryAssignment.category'])
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->paginate(12);

    // The rest of the function remains the same.
    $categoryList = Category::where('status', 1)->whereNull('parent_id')->with('children')->get();
    $sizes = Size::where('status', 1)->get();

    return view('front.category.subcategory', compact('subcategory', 'category', 'products', 'categoryList', 'sizes'));
}



   
    
}
