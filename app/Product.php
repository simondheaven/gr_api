<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    protected $hidden = [
      'created_at',
      'updated_at'
    ];

    public function productAttribute(){
      return $this->hasMany('App\ProductAttribute', 'product_id', 'id');
    }

    public function formatAttributes(){
      /*
        Function is called to add the attributes object.
        1. Get all attributes for this product.
        2. Set attribute key
        3. Set attribute value, separate handling implements string nesting for categories
        4. Convert to object, push to attributes array
        5. Return product object with attributes

        N.B. 'attributes' in spec replaced with 'attribs', due to conflict with Laravel object attributes

        //

        Funktion kallas för att lägga till attributobjektet.
        1. Få alla attribut för den här produkten.
        2. Ställ attributtangenten
        3. Ställ in attributvärde, separata hantering implementerar strängtäckning för kategorier
        4. Konvertera till objekt, tryck på attribut-array
        5. Returnera produktobjekt med attribut

        OBSERVERA 'attribut' i specifikation ersattes med 'attribs' på grund av konflikt med Laravel-objektattribut
      */
      $attributes = [];

      foreach($this->productAttribute()->get() as $productAttribute){

        $attribute = [];
        $attribute["name"] = $productAttribute->attributeType()->first()->name;

        if($productAttribute->attributeType()->first()->code == "cat"){

          $attribute["value"] = $productAttribute->attributeValue()->first()->buildCategoryString();

        } else {

          $attribute["value"] = $productAttribute->attributeValue()->first()->name;

        }

        $attribute = (object) $attribute;
        array_push($attributes, $attribute);

      }

      $this->attribs = $attributes;
      return $this;
    }

}
