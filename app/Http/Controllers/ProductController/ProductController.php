<?php 
namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller; 
use App\Models\Product;
use App\Models\UOM;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tax;
use App\Models\Warehouse;
use App\Models\OrderItem;
use App\Models\PurchaseItem;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


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
            $product = Product::where('id', $id)->first(); 

            if ($product) {
                return response()->json([
                    'success' => true,
                    'product' => [
                        
                        'id' => $product->id,
                        'product_name' => $product->product_name,
                        'product_code' => $product->product_code,
                        'cost' => $product->cost,
                        'price' => $product->price,
                        'uom_abbreviation' => $product->uom->abbreviation ?? '', 
                        'uom_id' => $product->uom->id ?? '', 
                        
                    ],
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Product not found.']);
        }


        
        
        private function calculateStockForProducts()
        {
            $products = Product::with(['category', 'uom', 'warehouse'])->get();
    
            foreach ($products as $product) {
                $purchasedQuantity = PurchaseItem::where('product_id', $product->id)
                    ->whereHas('purchase', function ($query) {
                        $query->where('status', '!=', 1);
                    })
                    ->sum('quantity');
    
                $returnedPurchaseQuantity = PurchaseItem::where('product_id', $product->id)
                    ->whereHas('purchase', function ($query) {
                        $query->where('status', 1);
                    })
                    ->sum('quantity');
    
                $orderedQuantity = OrderItem::where('product_id', $product->id)
                    ->whereHas('order', function ($query) {
                        $query->where('status', '!=', 1);
                    })
                    ->sum('quantity');
    
                $returnedOrderQuantity = OrderItem::where('product_id', $product->id)
                    ->whereHas('order', function ($query) {
                        $query->where('status', 1);
                    })
                    ->sum('quantity');
    
                $product->current_stock = $purchasedQuantity - $orderedQuantity + $returnedOrderQuantity - $returnedPurchaseQuantity;
            }
    
            return $products;
        }
    
        /**
         * Display stock report.
         */
        public function stockReport()
        {
            $products = $this->calculateStockForProducts();
            $warehouses = Warehouse::all();
            $totalProducts = $products->count();
    
            return view('products.stock-report', compact('products', 'warehouses', 'totalProducts'));
        }
    
        /**
         * Display products with alert quantity.
         */
        public function quantityAlerts(Request $request)
        {
            $products = $this->calculateStockForProducts();
    
            $alertProducts = $products->filter(function ($product) {
                return $product->alert_quantity > $product->current_stock;
            });
    
            return view('dashboard.product-alert-quantity', ['products' => $alertProducts]);
        }
        
        public function stockHistory(Product $product)
        {
            // Sales history
            $salesHistory = OrderItem::with(['order', 'warehouse'])
                ->where('product_id', $product->id)
                ->whereHas('order', function($query) {
                    $query->where('status', '!=', 1); // Regular sale (status != 1)
                })
                ->get();
        
            // Purchase history
            $purchaseHistory = PurchaseItem::with(['purchase', 'warehouse'])
                ->where('product_id', $product->id)
                ->whereHas('purchase', function($query) {
                    $query->where('status', '!=', 1); // Regular purchase (status != 1)
                })
                ->get();
        
            // Sales return history
            $salesReturnHistory = OrderItem::with(['order', 'warehouse'])
                ->where('product_id', $product->id)
                ->whereHas('order', function($query) {
                    $query->where('status', 1); // Sales return (status = 1)
                })
                ->get();
        
            // Purchase return history
            $purchaseReturnHistory = PurchaseItem::with(['purchase', 'warehouse'])
                ->where('product_id', $product->id)
                ->whereHas('purchase', function($query) {
                    $query->where('status', 1); // Purchase return (status = 1)
                })
                ->get();
        
            // Warehouse transfer history
            $transfersHistory = Transfer::with(['fromWarehouse', 'toWarehouse'])
                ->where('product_id', $product->id)
                ->get();
        
            // Calculate remaining stock
            $purchased = $purchaseHistory->sum('quantity');
            $sales = $salesHistory->sum('quantity');
            $salesReturns = $salesReturnHistory->sum('quantity');
            $purchaseReturns = $purchaseReturnHistory->sum('quantity');
            
            
            $remainingStock = ($purchased - $sales) + ($salesReturns - $purchaseReturns);
        
            // Pass all data to the view
            return view('products.stock-history', compact(
                'product', 'salesHistory', 'purchaseHistory', 
                'salesReturnHistory', 'purchaseReturnHistory', 
                'transfersHistory', 'remainingStock'
            ));
        }
        


        public function productSoldReport(Request $request)
            {
                $warehouses = Warehouse::all();

                // Build query
                $query = Product::with(['orderItems' => function ($q) use ($request) {
                    if ($request->has('warehouse_id') && $request->warehouse_id != '') {
                        $q->where('exit_warehouse', $request->warehouse_id);
                    }
                }, 'orderItems.order.customer', 'orderItems.warehouse']);

                if ($request->has('warehouse_id') && $request->warehouse_id != '') {
                    $query->whereHas('orderItems', function ($q) use ($request) {
                        $q->where('exit_warehouse', $request->warehouse_id);
                    });
                }

                if ($request->has('start_date') && $request->has('end_date') && $request->start_date != '' && $request->end_date != '') {
                    $query->whereHas('orderItems.order', function ($q) use ($request) {
                        $q->whereBetween('order_date', [$request->start_date, $request->end_date]);
                    });
                }

                $products = $query->get();

                $totalQuantitySold = $products->sum(function ($product) {
                    return $product->orderItems->sum('quantity');
                });

                $totalRevenue = $products->sum(function ($product) {
                    return $product->orderItems->sum(function ($orderItem) {
                        return $orderItem->quantity * $orderItem->unit_price;
                    });
                });

                return view('products.product-sold-report', compact('products', 'totalQuantitySold', 'totalRevenue', 'warehouses'));
            }



            public function productSoldReportPDF(Request $request)
            {
                $warehouses = Warehouse::all();

                // Build query
                $query = Product::with(['orderItems' => function ($q) use ($request) {
                    if ($request->has('warehouse_id') && $request->warehouse_id != '') {
                        $q->where('exit_warehouse', $request->warehouse_id);
                    }
                }, 'orderItems.order.customer', 'orderItems.warehouse']);

                if ($request->has('warehouse_id') && $request->warehouse_id != '') {
                    $query->whereHas('orderItems', function ($q) use ($request) {
                        $q->where('exit_warehouse', $request->warehouse_id);
                    });
                }

                if ($request->has('start_date') && $request->has('end_date') && $request->start_date != '' && $request->end_date != '') {
                    $query->whereHas('orderItems.order', function ($q) use ($request) {
                        $q->whereBetween('order_date', [$request->start_date, $request->end_date]);
                    });
                }

                $products = $query->get();

                $totalQuantitySold = $products->sum(function ($product) {
                    return $product->orderItems->sum('quantity');
                });

                $totalRevenue = $products->sum(function ($product) {
                    return $product->orderItems->sum(function ($orderItem) {
                        return $orderItem->quantity * $orderItem->unit_price;
                    });
                });

                // Create the JSON response
                return response()->json([
                    'products' => $products,
                    'totalQuantitySold' => $totalQuantitySold,
                    'totalRevenue' => $totalRevenue,
                    'warehouses' => $warehouses,
                ]);
            }

        
            public function productPurchasedReport(Request $request)
            {
                $warehouses = Warehouse::all();
            
                $query = Product::with(['purchaseItems' => function ($q) use ($request) {
                    if ($request->has('warehouse_id') && $request->warehouse_id != '') {
                        $q->where('inward_warehouse_id', $request->warehouse_id);
                    }
                }, 'purchaseItems.purchase.supplier', 'purchaseItems.warehouse']);
            
                if ($request->has('warehouse_id') && $request->warehouse_id != '') {
                    $query->whereHas('purchaseItems', function ($q) use ($request) {
                        $q->where('inward_warehouse_id', $request->warehouse_id);
                    });
                }
            
                if ($request->has('start_date') && $request->has('end_date') && $request->start_date != '' && $request->end_date != '') {
                    $query->whereHas('purchaseItems.purchase', function ($q) use ($request) {
                        $q->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
                    });
                }
            
                $products = $query->get();
            
                $totalQuantityPurchased = $products->sum(function ($product) {
                    return $product->purchaseItems->sum('quantity');
                });
            
                $totalExpenditure = $products->sum(function ($product) {
                    return $product->purchaseItems->sum(function ($purchaseItem) {
                        return $purchaseItem->quantity * $purchaseItem->rate;
                    });
                });
            
                $groupedByProduct = $products->mapWithKeys(function ($product) {
                    return [
                        $product->id => [
                            'product_name' => $product->name,
                            'total_quantity' => $product->purchaseItems->sum('quantity'),
                            'total_expenditure' => $product->purchaseItems->sum(function ($item) {
                                return $item->quantity * $item->rate;
                            }),
                        ],
                    ];
                });
            
                return view('products.product-purchased-report', compact('products', 'totalQuantityPurchased', 'totalExpenditure', 'groupedByProduct', 'warehouses'));
            }
            

            public function productPurchasedReportPDF(Request $request)
            {
                $warehouses = Warehouse::all();
            
                $query = Product::with(['purchaseItems' => function ($q) use ($request) {
                    if ($request->has('warehouse_id') && $request->warehouse_id != '') {
                        $q->where('inward_warehouse_id', $request->warehouse_id);
                    }
                }, 'purchaseItems.purchase.supplier', 'purchaseItems.warehouse']);
            
                if ($request->has('warehouse_id') && $request->warehouse_id != '') {
                    $query->whereHas('purchaseItems', function ($q) use ($request) {
                        $q->where('inward_warehouse_id', $request->warehouse_id);
                    });
                }
            
                if ($request->has('start_date') && $request->has('end_date') && $request->start_date != '' && $request->end_date != '') {
                    $query->whereHas('purchaseItems.purchase', function ($q) use ($request) {
                        $q->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
                    });
                }
            
                $products = $query->get();
            
                $totalQuantityPurchased = $products->sum(function ($product) {
                    return $product->purchaseItems->sum('quantity');
                });
            
                $totalExpenditure = $products->sum(function ($product) {
                    return $product->purchaseItems->sum(function ($purchaseItem) {
                        return $purchaseItem->quantity * $purchaseItem->rate;
                    });
                });
            
                $groupedByProduct = $products->mapWithKeys(function ($product) {
                    return [
                        $product->id => [
                            'product_name' => $product->name,
                            'total_quantity' => $product->purchaseItems->sum('quantity'),
                            'total_expenditure' => $product->purchaseItems->sum(function ($item) {
                                return $item->quantity * $item->rate;
                            }),
                        ],
                    ];
                });
            
                
                return response()->json([
                    'products' => $products,
                    'totalQuantityPurchased' => $totalQuantityPurchased,
                    'totalExpenditure' => $totalExpenditure,
                    'warehouses' => $warehouses,
                ]);
            }
            

                        
        
}
