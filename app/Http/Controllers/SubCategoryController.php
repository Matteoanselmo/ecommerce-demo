<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller {
    public function index() {
        $subCategory = SubCategory::all();

        return response()->json($subCategory);
    }
}
