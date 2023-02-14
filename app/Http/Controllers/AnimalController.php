<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class AnimalController extends Controller {
    const ITEMS_PER_PAGE = 10;
    const ORDER_BY = 'animal.name';
    const ORDER_TYPE = 'asc';

    const RAZAS = [
        'Pastor Alemán',
        'Bulldog',
        'Samoyendo',
        'Chihuahua',
        'Coli',
        'Husky',
        'Bodeguero'
    ];

    public function index(Request $request) {

        //Query
        $q = $request->input('q', null);

        $orderby = $this->getOrder($this->orderByList(), $request->input('orderby'), self::ORDER_BY);
        $ordertype = $this->getOrder($this->orderTypeList(), $request->input('ordertype'), self::ORDER_TYPE);
        $from = !$request->input('from') ? "0" : $request->input('from');
        $to = !$request->input('to') ? "15" : $request->input('to');
        $race = $request->input('race');

        $animals = DB::table('animal')
            ->orderBy($orderby, $ordertype);

        if( $from || $to ) {
            $animals->whereBetween('animal.age', [$from, $to]);
        }

        if( $race ) {
            $animals->where('animal.race', 'like', '%' . $race . '%');
        }

        if($q) {
            $animals->where('animal.name', 'like', '%' . $q . '%')
                ->orWhere('animal.description', 'like', '%' . $q . '%')
                ->orWhere('animal.age', 'like', '%' . $q . '%')
                ->orWhere('animal.race', 'like', '%'. $q . '%');
        }

        $animals = $animals->paginate(self::ITEMS_PER_PAGE);

        $url = $request->fullUrl();

        if($request->input('page')) {
            $url = explode('&page=', $request->fullUrl());
            $url = $url[0];
        }

        $animals->withPath($url);

        return view('animals.index', [
            'animals' => $animals,
            'order' => $this->getOrderUrls($orderby, $ordertype, $q, 'animal.index'),
            'q' => $q,
            'url' => $url,
            'races' => self::RAZAS,
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

    public function cambiarDatos() {
        $animals = Animal::all();

        $this->cambiarRaza($animals);

    }

    private function cambiarRaza($animales) {
        $length = count(self::RAZAS) - 1;

        foreach($animales as $animal) {
            $raza = fake()->numberBetween(0, $length);
            $raza = self::RAZAS[$raza];

            $animal->race = $raza;
            $animal->save();
        }
    }

    /*
     * Función para hacer una petición a Dall-e y recoger una foto
     * generada por IA dependiendo de la raza del perro.
     *
     * Ya no tiene modelo gratuito y hay que pagar.
     */
    private function dalle() {

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env("OPENAI_KEY"),
        ])->get("https://api.openai.com/v1/models");


        $modelos = $response->json()['data'];
        $davinci = '';

        foreach($modelos as $modelo) {
            if($modelo['id'] == 'text-ada-001') {
                $davinci = $modelo;
            }
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env("OPENAI_KEY"),
        ])->post("https://api.openai.com/v1/images/generations", [
            'prompt' => 'German Shepard',
            'n' => 1,
            'size' => "512x512"
        ]);
    }

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

    public function show($id) {
        $animal = Animal::find($id);

        return view('animals.show', [
            'animal' => $animal
        ]);
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
