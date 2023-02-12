<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnimalController extends Controller {
    const ITEMS_PER_PAGE = 10;
    const ORDER_BY = 'animal.name';
    const ORDER_TYPE = 'asc';

    public function index(Request $request) {

        //Query
        $q = $request->input('q', '');

        $orderby = $this->getOrder($this->orderByList(), $request->input('orderby'), self::ORDER_BY);

        $ordertype = $this->getOrder($this->orderTypeList(), $request->input('ordertype'), self::ORDER_TYPE);

        //Página actual
        $page = $request->input('page', 1);

        $animals = DB::table('animal')
            ->orderBy($orderby, $ordertype)
            ->limit(self::ITEMS_PER_PAGE)
            ->get();

        return view('welcome', [
            'animals' => $animals,
            'order' => $this->getOrderUrls($orderby, $ordertype, $q, 'animal.index'),
            'q' => $q,
            'url' => url('/')
        ]);
    }

    private function getOrderUrls($oBy, $oType, $q, $route) {
        $urls = [];

        $orderBys = $this->orderByList();
        $orderTypes = $this->orderTypeList();

        foreach($orderBys as $indexBy => $by) {
            foreach($orderTypes as $indexType => $type) {
                if($oBy == $indexBy && $oType == $indexType) {
                    $urls[$indexBy][$indexType] = url()->full() . '#';
                } else {
                    $urls[$indexBy][$indexType] = route($route, [
                        'orderby'   => $by,
                        'ordertype' => $type,
                        'q'         => $q]);
                }
            }
        }

        return $urls;
    }

    /**
     *
    */
    private function getOrder($orderArray, $order, $default) {
        $value = array_search($order, $orderArray);

        if( !$value ) {
            $value = $default;
        }

        return $value;
    }
    private function orderByList() {
        return [
            'animal.name' => 'b1',
            'animal.description' => 'b2',
            'animal.age' => 'b3',
            'animal.race' => 'b4'
        ];
    }

    private function orderTypeList() {
        return [
            'asc'  => 't1',
            'desc' => 't2',
        ];
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
