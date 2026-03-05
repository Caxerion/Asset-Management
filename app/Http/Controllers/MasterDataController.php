<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Floor;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $floors = Floor::all();
        
        return view('masterdata.index', compact('categories', 'floors'));
    }
}
