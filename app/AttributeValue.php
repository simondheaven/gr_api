<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $fillable = [
      'attribute_type_id',
      'name',
      'code'
    ];

    public function attributeType(){
      return $this->hasOne('\App\AttributeType', 'attribute_type_id', 'id');
    }

    public function buildCategoryString(){
      /*
        Initialise. Split category code into components. //
        Initieras. Dela kategorikoden i komponenter.
      */
      $code = "";
      $categoryString = "";
      $firstCategoryAdded = false;
      $components = explode("_", $this->code);

      foreach($components as $component){
        /*
          For each substring component.
          Concatenate to code query string.
          Query for matching category.

          //

          För varje substringskomponent.
          Sammanfoga till kodfrågesträngen.
          Fråga för matchande kategori.
        */
        $code .= $component;
        $category = \App\AttributeValue::where('code', $code)->first();

        if($category){

          /*

            If a category is found matching the current code query string,
            add the category name to the category string. If it is not the
            first category, prepend the seperator string.

            //

            Om en kategori hittas som matchar den aktuella kodfrågesträngen,
            lägg till kategorinamnet i kategoristrängen. Om det inte är
            första kategorin, beroende på separatorsträngen.

          */

          if($firstCategoryAdded){
            $categoryString .= " > ";
          }

          $firstCategoryAdded = true;
          $categoryString .= $category->name;
        }

        /*
          Reintroduce seperator for next query
          //
          Återinför seperator för nästa fråga
        */
        $code .= "_";
      }

      return $categoryString;
    }
}
