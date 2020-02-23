<?php

/*
  Refresh database from files
  //
  Uppdatera databasen från filer
*/
Route::post('/sync', 'ProductController@sync')->name('api.sync');

/*
  Get products. All products unless page and page_size are specified
  //
  Skaffa produkter. Alla produkter om inte page och page_size anges
*/
Route::post('/products', 'ProductController@products')->name('api.products');
