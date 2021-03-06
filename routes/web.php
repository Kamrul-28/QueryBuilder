<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
     
    // /*
    //  //Querey 1
    //  select * from actor
    //  where last_name = "BERRY";
     
    //  */

    // //  $actors=DB::table('actor')
    // //  ->where('last_name','BERRY')
    // //  ->where('first_name','karl')
    // //  ->get();

    // // $actors=DB::table('actor')
    // // ->where([
    // //     ['last_name','BERRY'],
    // //     ['first_name','karl']
    // // ])
    // // ->get();

    // $results=DB::table('actor')
    // ->where(function($querey){
           
    //     $querey->where([
    //                 ['last_name','BERRY'],
    //                 ['first_name','karl']
    //              ]);
    // })
    // ->get();

    // /*
    // //Querey-2:
    //     select last_name ,count(*) As count
    //     from actor
    //     group by last_name
    //     order by count desc 
    // */

    // $results = DB::table('actor')
    //         ->select(['last_name',DB::raw('count(*) as user_count')])
    //         ->groupBy('last_name')
    //         ->get();


    // /*
    // //Querey-3:
    
    //     select country_id , country from country
    //     where country in ('Afghanistan','Bangladesh','China')
    //     order by country_id desc
    //  */
    // $results = DB::table('country')
    //     ->select(['country_id','country'])
    //     ->whereIn('country',['Afghanistan','Bangladesh','China'])
    //     ->orderBy('country_id','desc')
    //     ->get();


    // /*  
    //   //Querey-4:  
          
    //     select film_id ,title,special_features,replacement_cost 
    //     from film
    //     where replacement_cost between 19.99 and 28.99
    //     order by film_id
    //     limit 10 
        
    //  */

    // $results = DB::table('film')
    //         ->select(['film_id','title','special_features','replacement_cost'])
    //         ->whereBetween('replacement_cost',[19.99,28.99])
    //         ->orderBy('film_id','desc')
    //         ->limit(10)
    //         ->get();

    // /*  
    //   //Querey-5:  
          
    //     select film_id ,title,special_features,replacement_cost 
    //     from film
    //     where replacement_cost between 19.99 and 28.99
    //     order by film_id
    //     limit 10 
        
    //  */

    // $results = DB::table('film')
    //         ->select(['film_id','title','special_features','replacement_cost'])
    //         ->whereNotBetween('replacement_cost',[18.99,28.99])
    //         ->orderBy('film_id','desc')
    //         ->skip(3)
    //         ->limit(10)
    //         ->get();


    //  return $results;


    // /*  
    //   //Querey-6:  
          
    //     select film_id ,title,special_features,replacement_cost 
    //     from film
    //     where replacement_cost between 19.99 and 28.99
    //     order by film_id
    //     limit 10 
        
    //  */

    // $values = DB::table('film')
    //         ->select(['film_id','title','special_features','replacement_cost'])
    //         ->where('title','AFRICAN EGG')
    //         ->orWhere('title','Agent Truman')
    //         ->get();


    /* Joins Starts From Here */
    /*
        select
            s.`staff_id`,s.`first_name`,s.`last_name`,s.`email`,
            addr.`address`,addr.`district`,addr.`postal_code`,
            c.`city`,count.`country`
        from staff as s
        left join address as addr
        on s.`address_id` = addr.`address_id`
        left join city as c
        on addr.`city_id` = c.`city_id`
        left join country as count
        on c.`country_id`=count.`country_id`
    */

    // $stuffs = DB::table('staff as s')
    // ->select([
    //     's.staff_id','s.first_name','s.last_name','s.email',
    //     'addr.address','addr.district','addr.postal_code',
    //     'c.city','count.country'
    // ])
    // ->leftJoin('address as addr','s.address_id','addr.address_id')
    // ->leftJoin('city as c','addr.city_id','c.city_id')
    // ->leftJoin('country as count','c.country_id','count.country_id')
    // ->orderBy('staff_id')
    // ->get();

    /* Sub Querey */
    /*
        select film_id ,title from film
        where title like 'K%' or title like 'O%'
        and language_id in(
            select language_id from language
            where name='English'
        )
        order by title
    */

    // $films=DB::table('film')
    //         ->select('film_id','title')
    //         ->where('title','like','K%')
    //         ->orWhere('title','like','O%')
    //         ->whereIn('language_id',function($query){
    //                 $query->select('language_id')
    //                    ->from('language')
    //                    ->where('name','English')
    //                    ->get();             
    //         })
    //         ->orderBy('film_id')
    //         ->get();

    /*
    Complex joins and subquery
    select store_details.* , payment_details.sales
        from (
            select sto.store_id,city.city,count.country
            from store as sto
            left join address as addr
            on sto.address_id=addr.address_id
            join city
            on addr.city_id=city.city_id
            join country as count
            on city.country_id=count.country_id

        ) as store_details
        inner join(
            select cus.store_id,sum(pay.amount) as sales
            from customer as cus
            join payment as pay
            on cus.customer_id = pay.customer_id
            group by cus.store_id
            
        )
        as payment_details

        on store_details.store_id = payment_details.store_id

        order by store_details.store_id
    */
    $store_details=DB::query()
    ->select(['sto.store_id','city.city','count.country'])
    ->from('store as sto')
    ->leftJoin('address as addr','sto.address_id','=','addr.address_id')
    ->join('city','addr.city_id','=','city.city_id')
    ->join('country as count','city.country_id','=','count.country_id');

    $payment_details=DB::query()
    ->select(['cus.store_id',DB::raw('sum(pay.amount) as sales')])
    ->from('customer as cus')
    ->join('payment as pay','cus.customer_id','=','pay.customer_id')
    ->groupBy('cus.store_id');

    $results=DB::query()
            ->select('store_details.*','payment_details.sales')
            ->fromSub($store_details,'store_details' )
            ->joinSub($payment_details,'payment_details','store_details.store_id','=','payment_details.store_id')
            //->toSql();
            ->get();

    /*We Can Use this Also */
    
    // $results=DB::query()
    // ->select('store_details.*','payment_details.sales')
    // ->fromSub(function($query){
    //     $query->select(['sto.store_id','city.city','count.country'])
    //     ->from('store as sto')
    //     ->leftJoin('address as addr','sto.address_id','=','addr.address_id')
    //     ->join('city','addr.city_id','=','city.city_id')
    //     ->join('country as count','city.country_id','=','count.country_id');
        
    // },'store_details' )
    // ->joinSub(function($query){
    //     $query   ->select(['cus.store_id',DB::raw('sum(pay.amount) as sales')])
    //     ->from('customer as cus')
    //     ->join('payment as pay','cus.customer_id','=','pay.customer_id')
    //     ->groupBy('cus.store_id');
        
    // },'payment_details','store_details.store_id','=','payment_details.store_id')
    // ->get();



     /*
        Conditionals in Query
           
        select cat.name,count(f.film_id) as film_count
        from category as cat
        left join film_category as fc
        on cat.category_id = fc.category_id
        join film as f
        on fc.film_id=f.film_id
        join language as lang
        on f.language_id=lang.language_id
        where lang.name='English'
        group by cat.name
        order by film_count desc
    */

 $categories=DB::query()
            ->select('cat.name',DB::raw('count(f.film_id) as film_count'))
            ->from('category as cat')
            ->leftJoin('film_category as fc','cat.category_id','=','fc.category_id')
            ->join('film as f','fc.film_id','=','f.film_id')
            ->join('language as lang',function($join){
                $join->on('f.language_id','=','lang.language_id')
                ->where('lang.name','English');
            })
            ->groupBy('cat.name')
            ->orderBy('film_count','DESC')
            ->get();
            
     return $categories;
     
     
});