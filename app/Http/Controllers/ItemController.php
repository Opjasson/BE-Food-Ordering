<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
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
}
