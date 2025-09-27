<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function store()
    {
        return response(["msg" => "success"], 200);
    }
}
