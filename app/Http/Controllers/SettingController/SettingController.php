<?php


namespace App\Http\Controllers\SettingController;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Currency;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    
    public function index()
    {
        $settings = Setting::all();
        $currencies = Currency::all();
        return view('settings.index', compact('settings', 'currencies'));
    }

    

    public function create()
    {
        $currency = Setting::where('name', 'currency-symbol')->first();
        $currencies = Currency::all();
        
        return view('settings.create', compact('currencies', 'currency'));
    }
    
    
    

    public function store(Request $request)
    {
        
    
        // Store the currency code setting in the 'settings' table
        Setting::updateOrCreate(
            ['name' => 'currency-symbol'],
            ['value' => $request->currency]  // Save the currency code
        );
    
        return redirect()->route('settings.create')->with('success', 'Currency setting saved successfully');
    }
    


    public function edit($id)
    {
        $setting = Setting::findOrFail($id);
        $currencies = Currency::all(); 
        return view('settings.edit', compact('setting', 'currencies'));
    }
    

    public function update(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if ($key === '_token' || $key === '_method') {
                continue;
            }
    
            $request->validate([
                $key => 'required|string|max:255',
            ]);
    
            $setting = Setting::where('name', $key)->first();
    
            if ($setting) {
                $setting->update([
                    'value' => $value,
                ]);
            }
        }
    
        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
    

    public function destroy(Setting $setting)
    {
        $setting->delete();

        return redirect()->route('settings.index')->with('success', 'Setting deleted successfully.');
    }
}
