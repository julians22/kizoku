<?php

namespace App\Http\Controllers;

use App\Livewire\Traits\ParfumeCollection;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ParfumeCollection;

    public function index()
    {
        //
    }

    public function show($slug)
    {

        $parfumes = $this->getParfumeCollections();

        $selectedParfume = collect($parfumes)->firstWhere('slug', $slug);

        if ($selectedParfume) {
            return view('products.detail', compact('selectedParfume'));
        } else {
            abort(404);
        }
    }


}
