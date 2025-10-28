<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::ordered()->paginate(10);
        
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|in:USD,AED,SAR,QAR,KWD,BHD,OMR,EUR,GBP,INR,PKR,EGP',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        Package::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency,
            'duration_days' => $request->duration_days,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function show(Package $package)
    {
        return view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|in:USD,AED,SAR,QAR,KWD,BHD,OMR,EUR,GBP,INR,PKR,EGP',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $package->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency,
            'duration_days' => $request->duration_days,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        // Check if package is being used
        $usageCount = DB::table('payment_verifications')
            ->where('package_id', $package->id)
            ->count();

        if ($usageCount > 0) {
            return redirect()->route('admin.packages.index')
                ->with('error', 'Cannot delete package. It is being used by ' . $usageCount . ' payment(s).');
        }

        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }

    public function toggleStatus(Package $package)
    {
        $package->update(['is_active' => !$package->is_active]);
        
        $status = $package->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.packages.index')
            ->with('success', "Package {$status} successfully.");
    }

    public function toggleFeatured(Package $package)
    {
        $package->update(['is_featured' => !$package->is_featured]);
        
        $status = $package->is_featured ? 'featured' : 'unfeatured';
        
        return redirect()->route('admin.packages.index')
            ->with('success', "Package {$status} successfully.");
    }
}
