<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;

class CategoriesController extends Controller
{
    public function show(Category $category)
    {
        $topics = Topic::where('category_id', $category->id)->with('user', 'category')->paginate();
        return view('topics.index', compact('topics', 'category'));
    }
}
