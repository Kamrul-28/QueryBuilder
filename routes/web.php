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


    /*
    //Querey-3:
    
        select country_id , country from country
        where country in ('Afghanistan','Bangladesh','China')
        order by country_id desc
     */
    $results = DB::table('country')
        ->select(['country_id','country'])
        ->whereIn('country',['Afghanistan','Bangladesh','China'])
        ->orderBy('country_id','desc')
        ->get();


    /*  
      //Querey-4:  
          
        select film_id ,title,special_features,replacement_cost 
        from film
        where replacement_cost between 19.99 and 28.99
        order by film_id
        limit 10 
        
     */

    $results = DB::table('film')
            ->select(['film_id','title','special_features','replacement_cost'])
            ->whereBetween('replacement_cost',[19.99,28.99])
            ->orderBy('film_id','desc')
            ->limit(10)
            ->get();

    /*  
      //Querey-5:  
          
        select film_id ,title,special_features,replacement_cost 
        from film
        where replacement_cost between 19.99 and 28.99
        order by film_id
        limit 10 
        
     */

    $results = DB::table('film')
            ->select(['film_id','title','special_features','replacement_cost'])
            ->whereNotBetween('replacement_cost',[18.99,28.99])
            ->orderBy('film_id','desc')
            ->limit(10)
            ->get();


     return $results;
     
     
});