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

    /*
    //Querey-2:
        select last_name ,count(*) As count
        from actor
        group by last_name
        order by count desc 
    */

    $results = DB::table('actor')
            ->select(['last_name',DB::raw('count(*) as user_count')])
            ->groupBy('last_name')
            ->get();


    
     return $results;
     
     
});