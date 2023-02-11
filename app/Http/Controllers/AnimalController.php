<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnimalController extends Controller {
    const ITEMS_PER_PAGE = 10;
    const ORDER_BY = 'animal.name';
    const ORDER_TYPE = 'asc';

    public function index() {
        $animals = DB::table('animal')
            ->orderBy(SELF::ORDER_BY)
            ->limit(SELF::ITEMS_PER_PAGE)
            ->get();
        return view('welcome', ['animals' => $animals]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Animal $animal)
    {
        //
    }

    public function edit(Animal $animal)
    {
        //
    }

    public function update(Request $request, Animal $animal)
    {
        //
    }

    public function destroy(Animal $animal)
    {
        //
    }
}
