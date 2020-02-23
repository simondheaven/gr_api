<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Product;

class ProductController extends Controller
{
    public function sync(Request $request){

      $job = new \App\Jobs\Sync();
      dispatch($job);

      return response()->json([
        'success' => 1,
        'message' => "success"
      ]);
    }

    public function products(Request $request){
      /*
        Collect the page and page_size arguments if they are given to us. If the attributes are not supplied, then use default values //
        Samla page och page_size om de ges till oss. Om attributen inte levereras använder du standardvärden
      */
      $page = ($request->has('page')) ? $request->input('page') : 1;
      $page_size = ($request->has('page_size')) ? $request->input('page_size') : Product::count();

      /*
        If the "product_count" is divisible by the "page_size", return the divided value.
        If the "product_count" is not divisible by the "page_size", return the divided value +1 as an integer.
        //
        Om "product_count" kan delas med "page_size", returnerar du det delade värdet.
        Om "product_count" inte kan delas med "page_size", returnerar du det delade värdet +1 som ett heltal.
      */
      $total_pages = (Product::count() % $page_size == 0) ? Product::count() / $page_size : floor(Product::count() / $page_size) + 1;

      if($page_size == 0 && Product::count() !== 0){
        /*
          Preventing division by 0 // Förhindrar uppdelning med 0
        */
        $page_size = 1;
      }

      if($page > $total_pages){
        /*
          Preventing request of page beyond array limit // Förhindrar begäran om sida utanför matrisgränsen
        */
        $page = $total_pages;
      } else if ($page < 1){
        /*
          Preventing request of page beyond array limit // Förhindrar begäran om sida utanför matrisgränsen
        */
        $page = 1;
      }

      $products = Product::where('id', '>', 0)
                          ->orderBy('id', 'ASC')
                          ->skip(($page - 1) * $page_size)
                          ->take($page_size)
                          ->get()
                          ->each->formatAttributes();

      return response()->json([
        'products' => $products,
        'page' => intval($page),
        'total_pages' => $total_pages
      ]);
    }
}
