<?php


namespace App\Http\Controllers\BrandController;

use App\Http\Controllers\Controller;
use App\Models\PaymentHead;
use App\Models\Brand;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BrandController extends Controller
{
        
    public function index()
    {
        $brands = Brand::all(); // Fetch all brands
        $totalBrands = $brands->count(); // Total number of brands
        return view('brands.index', compact('brands', 'totalBrands'));
    }
    


    public function create()
    {
        return view('brands.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
    
        Brand::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    
        return redirect()->route('brand.index')->with('success', 'Brand created successfully.');
    }
    
    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }
    
    
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
    
        // Update the brand with the new values
        $brand->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    
        return redirect()->route('brand.index')->with('success', 'Brand updated successfully.');
    }
    
    

    public function destroy(Brand $brand)
    {
        // Delete the brand
        $brand->delete();
    
        return redirect()->route('brand.index')->with('success', 'Brand deleted successfully.');
    }
    

}
