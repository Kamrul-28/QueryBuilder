<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
     
    /*
     //Querey 1
     select * from actor
     where last_name = "BERRY";
     
     */

    //  $actors=DB::table('actor')
    //  ->where('last_name','BERRY')
    //  ->where('first_name','karl')
    //  ->get();

    // $actors=DB::table('actor')
    // ->where([
    //     ['last_name','BERRY'],
    //     ['first_name','karl']
    // ])
    // ->get();

    $results=DB::table('actor')
    ->where(function($querey){
           
        $querey->where([
                    ['last_name','BERRY'],
                    ['first_name','karl']
                 ]);
    })
    ->get();



     return $results;
     
     
});