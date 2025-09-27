<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $data = Item::select('id', 'name', 'price', 'image')->get();
        return response(["data" => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'price' => 'required|integer',
            'image' => 'nullable|mimes:png,jpg',
        ]);

        if ($request->file('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $newName = Carbon::now()->timestamp . '_' . $fileName;

            Storage::disk('public')->putFileAs('items', $file, $newName);

            $data = Item::create([
                "name" => $request->name,
                "price" => $request->price,
                "image" => $newName
            ]);

            return response()->json(['data' => $data]);
        }

        $data = Item::create($request->all());

        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'price' => 'required|integer',
            'image' => 'nullable|mimes:png,jpg',
        ]);

        // Setting create file public/storage/items to saving image file
        if ($request->file('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $newName = Carbon::now()->timestamp . '_' . $fileName;

            Storage::disk('public')->putFileAs('items', $file, $newName);

            $item = Item::findOrFail($id);

            $item->update([
                "name" => $request->name,
                "price" => $request->price,
                "image" => $newName
            ]);

            return response()->json(['data' => $item]);
        }

        $item = Item::findOrFail($id);
        $item->update($request->all());

        return response(["data" => $item], 200);
    }
}
