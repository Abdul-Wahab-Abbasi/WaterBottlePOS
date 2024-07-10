<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class InventoryController extends Controller
{
    public function index()
{
    $inventory = Inventory::all();

    return view('inventory.index', compact('inventory'));
}


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'size' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public', $imageName);
            $data['image'] = $imageName;
        }

        Inventory::create($data);

        return redirect()->route('inventory.index')->with('success', ['Product created successfully.','success','check-circle']);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required',
            'size' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public', $imageName);
            $data['image'] = $imageName;
        }

        $inventory->update($data);

        return redirect()->route('inventory.index')->with('success', ['Product updated successfully.','success','check-circle']);
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', ['Product deleted successfully.','success','check-circle']);
    }
}
