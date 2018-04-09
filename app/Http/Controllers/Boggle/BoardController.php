<?php

namespace App\Http\Controllers\Boggle;


use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoggleFormRequest;
use App\Boggle;
use \Ds\Set;

class BoardController extends Controller {

    public function show() {
        $results = new \Ds\Set();
        return \View::make('boggle', array('results' => $results));
    }

    public function submit(BoggleFormRequest $request) {
        $board = [];
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 4; $j++) {
                $str = "c".$i.$j;
                $board[$i][$j] = trim(strtoupper($request->get($str)));
            }
        }
        $boggle = new Boggle($board);
        $results = $boggle->solveIt();
        return \View::make('boggle', array('results' => $results, 'totalSearched' => $boggle->totalTested));

    }

}
