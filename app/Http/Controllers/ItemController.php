<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Display a listing of the items
    public function index()
    {
        // Retrieve all items, including soft-deleted ones
        $items = Item::all();
        return response()->json($items);
    }

    // Store a newly created item
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Item::create($request->all());
        return response()->json(['message' => 'Item created successfully.'], 201);
    }

    // Show the specified item
    public function show(Item $item)
    {
        return response()->json($item);
    }

    // Update the specified item
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $item->update($request->all());
        return response()->json(['message' => 'Item updated successfully.']);
    }

    // Soft delete the specified item
    public function destroy(Item $item)
    {
        $item->delete(); // Soft delete
        return response()->json(['message' => 'Item deleted successfully.']);
    }

    // Hard delete the specified item
    public function hardDelete($id)
    {
        $item = Item::withTrashed()->findOrFail($id);
        $item->forceDelete(); // Hard delete
        return response()->json(['message' => 'Item permanently deleted.']);
    }

    // Optional: Get soft-deleted items
    public function trashedItems()
    {
        $items = Item::onlyTrashed()->get();
        return response()->json($items);
    }

    // Optional: Restore a soft-deleted item
    public function restore($id)
    {
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->restore();
        return response()->json(['message' => 'Item restored successfully.']);
    }
}
