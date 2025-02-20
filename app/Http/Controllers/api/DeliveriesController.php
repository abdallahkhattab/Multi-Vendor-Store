<?php

namespace App\Http\Controllers\Api;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DeliveriesController extends Controller
{
    //

    public function update(Request $request,Delivery $delivery){

        $request->validate([
            'lng'=> ['required','numeric'],
            'lat'=>['required','numeric'],
        ]);

        $delivery->update([
                'current_location' => DB::raw("POINT({$request->lng} , {$request->lat})"),
        ]);

        return $delivery;

    }
}
