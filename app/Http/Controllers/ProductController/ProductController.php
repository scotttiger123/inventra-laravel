<?php 
namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller; 
use App\Models\Product;
use App\Models\UOM;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    
    public function create()
    {
        $brands = Brand::all(); 
        $categories = Category::all(); 
        $uoms = Uom::all(); 
        $taxes = Tax::all();
    
        return view('products.create', compact('brands', 'categories', 'uoms','taxes'));
    }
    

    public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'product_code' => 'required|string|max:255|unique:products,product_code,NULL,id,parent_user_id,' . auth()->user()->parent_id . ',created_by,' . auth()->id(),
        'product_name' => 'required|string|max:255',
        'brand_id' => 'nullable|integer',
        'category_id' => 'nullable|integer',
        'cost' => 'required|numeric',
        'price' => 'required|numeric',
        'uom' => 'required|string',
        'initial_stock' => 'nullable|integer',
        'alert_quantity' => 'nullable|integer',
        'tax_id' => 'nullable|integer',
        'product_image' => 'nullable|image'
    ]);

   
    // Create a new product instance
    $product = new Product();
    $product->product_code = $request->input('product_code');
    $product->product_name = $request->input('product_name');
    $product->brand_id = $request->input('brand_id');
    $product->category_id = $request->input('category_id');
    $product->cost = $request->input('cost');
    $product->price = $request->input('price');
    $product->uom_id = $request->input('uom');
    $product->alert_quantity = $request->input('alert_quantity',0);
    $product->tax_id = $request->input('tax_id');
    $product->initial_stock = $request->input('initial_stock',0);
    $product->product_details = $request->input('product_details');
    
    $product->created_by = auth()->id(); 
    $product->parent_user_id = auth()->user()->parent_id;

    // Handle image upload if necessary
    if ($request->hasFile('product_image')) {
        $imagePath = $request->file('product_image')->store('products', 'public');
        $product->image_path = $imagePath; // Store image path
    }

    try {
        $product->save();
        return redirect()->route('products.create')->with('success', 'Product added successfully!');
    } catch (QueryException $e) {
        // Check for duplicate entry error
        if ($e->getCode() === '23000') {
            if (strpos($e->getMessage(), 'unique_product_code_per_user_and_parent') !== false) {
                return redirect()->back()->withErrors(['product_code' => 'The product code has already been used for this user.'])->withInput();
            }
        }
        // Log the error for debugging
        Log::error('Error saving product: ' . $e->getMessage());
        
        return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.'])->withInput();
    }
}

    

    public function index()
    {   
        $totalProducts = Product::count();
        $products = Product::with(['tax', 'uom', 'creator'])->get(); 
        return view('products.index', compact('products','totalProducts'));
    }


        public function loadProducts()
        {
            
            $products = Product::all();
            $currencySymbol = \App\Models\Setting::where('name', 'currency-symbol')->value('value');
            foreach ($products as $product) {
                $product->currency_symbol = $currencySymbol;
            }
            return response()->json($products);
        }



    public function destroy($id)
    {
        $product = Product::find($id);

            if ($product) {
                $product->delete();
                return response()->json(['success' => true, 'message' => 'Product deleted successfully!']);
            }

        return response()->json(['success' => false, 'message' => 'Product not found.']);
    }


    public function edit(Product $product)
    {
        $brands = Brand::all();  
        $categories = Category::all();  
        $taxes = Tax::all();  
        $uoms = Uom::all();  
    
        return view('products.edit', compact('product', 'brands', 'categories', 'taxes', 'uoms'));
    }
    


    public function update(Request $request, Product $product)
{
    // Validate the incoming request data
    $request->validate([
        'product_code' => 'required|string|max:255|unique:products,product_code,' . $product->id . ',id,parent_user_id,' . auth()->user()->parent_id . ',created_by,' . auth()->id(),
        'product_name' => 'required|string|max:255',
        'brand_id' => 'nullable|integer',
        'category_id' => 'nullable|integer',
        'cost' => 'required|numeric',
        'price' => 'required|numeric',
        'uom' => 'required|string',
        'initial_stock' => 'nullable|integer',
        'alert_quantity' => 'nullable|integer',
        'tax_id' => 'nullable|integer',
        'product_image' => 'nullable|image|max:2048'
    ]);

    // Update the product instance
    $product->product_code = $request->input('product_code');
    $product->product_name = $request->input('product_name');
    $product->brand_id = $request->input('brand_id');
    $product->category_id = $request->input('category_id');
    $product->cost = $request->input('cost');
    $product->price = $request->input('price');
    $product->uom_id = $request->input('uom');
    $product->alert_quantity = $request->input('alert_quantity', 0);
    $product->tax_id = $request->input('tax_id');
    $product->initial_stock = $request->input('initial_stock', 0);
    $product->product_details = $request->input('product_details');

    $product->updated_by = auth()->id(); // You can track who updated the product

    // Handle image upload if provided
    if ($request->hasFile('product_image')) {
        // Delete the old image if a new one is uploaded
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        // Store the new image
        $imagePath = $request->file('product_image')->store('products', 'public');
        $product->image_path = $imagePath; // Update image path
    }

    try {
        // Save the updated product
        $product->save();
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    } catch (QueryException $e) {
        // Handle database exceptions (e.g., duplicate entries)
        if ($e->getCode() === '23000') {
            if (strpos($e->getMessage(), 'unique_product_code_per_user_and_parent') !== false) {
                return redirect()->back()->withErrors(['product_code' => 'The product code has already been used for this user.'])->withInput();
            }
        }
        // Log the error for debugging
        Log::error('Error updating product: ' . $e->getMessage());

        return redirect()->back()->withErrors(['error' => 'An unexpected error occurred.'])->withInput();
    }
}

        public function getProductDetails($id)
        {
            $product = Product::where('id', $id)->first(); // Search by `id`

            if ($product) {
                return response()->json([
                    'success' => true,
                    'product' => [
                        'cost' => $product->cost,
                        'price' => $product->price,
                        'uom_abbreviation' => $product->uom->abbreviation ?? '', 
                        'uom_id' => $product->uom->id ?? '', 
                        
                    ],
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Product not found.']);
        }

}
