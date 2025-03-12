<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateAvailableMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');

        $query = MenuItem::query();

        //apply search if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $menuItems = $query->orderBy('created_at', 'desc')
            ->leftJoin('categories', 'menu_items.category_id', '=', 'categories.id')
            ->select('menu_items.*', 'categories.name as category_name')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('menu_item/Index', [
            'menuItems' => $menuItems,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuItemRequest $request)
    {
        try {
            $validated = $request->validated();

            $validated = $request->safe()->only('name', 'price', 'image_url', 'is_published', 'description');
            $validated['image_url'] = $request->file('image_url')->store('menus');

            MenuItem::create($validated);

            return redirect()->route('menu-items.index')->with('success', 'Menu item created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create menu item: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItem $menuItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuItem $menuItem)
    {
        $data = $menuItem->toArray();
        $data['image_url'] = asset('storage/' . $data['image_url']);
        $data['categories'] = Category::select('id', 'name')->get()->toArray();

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuItemRequest $request, MenuItem $menuItem)
    {
        try {
            $validated = $request->validated();
            $validated = $request->safe()->only('name', 'price', 'image_url', 'is_available', 'description', 'category_id');

            if ($request->hasFile('image_url')) {
                //delete old image
                Storage::delete($menuItem->image_url);

                $validated['image_url'] = $request->file('image_url')->store('menus');
            } else {
                // Remove image_url from data array to prevent overwriting with null
                unset($validated['image_url']);
            }

            $menuItem->update($validated);

            return redirect()->route('menu-items.index')->with('success', 'Menu item updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update menu item: ' . $e->getMessage());
        }
    }

    public function updateAvailable(UpdateAvailableMenuItemRequest $request, $id)
    {
        try {
            $validated = $request->validated();
            $validated = $request->safe()->only('is_available');

            $menuItem = MenuItem::findOrFail($id);
            $menuItem->update($validated);

            return redirect()->route('menu-items.index')->with('success', 'Menu item availability updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update menu item availability: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $menuItem)
    {
        //
    }
}
