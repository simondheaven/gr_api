<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Product;
use App\ProductAttribute;
use App\AttributeType;
use App\AttributeValue;

class Sync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $metaFile;
    protected $productFile;

    /**
     * @return void
     */
    public function __construct()
    {
        /*
          Initialise file locations
          //
          Initiera filplatser
        */
        $this->metaFile = public_path('attribute_meta.json');
        $this->productFile = public_path('products.json');
    }

    /**
     * @return void
     */
    public function handle()
    {
        /*
          All products to be supplied in each file, so we can refresh the database
          //
          Alla produkter som ska levereras i varje fil, så vi kan uppdatera databasen
        */
        ProductAttribute::where('id','>',0)->delete();
        AttributeValue::where('id','>',0)->delete();
        AttributeType::where('id','>',0)->delete();
        Product::where('id','>',0)->delete();

        /*
          Insert metadata definitions
          //
          Infoga metadatadefinitioner
        */
        $metaData =  json_decode(file_get_contents($this->metaFile), true);
        foreach($metaData as $attribute){
          $at = AttributeType::create([
            'name' => $attribute["name"],
            'code' => $attribute["code"]
          ]);
          foreach($attribute["values"] as $val){
            AttributeValue::create([
              'attribute_type_id' => $at->id,
              'name' => $val['name'],
              'code' => $val['code']
            ]);
          }
        }

        /*
          Insert products
          //
          Infoga produkter
        */
        $productData = json_decode(file_get_contents($this->productFile), true);
        foreach($productData as $product){

          Product::create([
            'id' => $product["id"],
            'name' => $product["name"]
          ]);

          $pid = $product["id"];

          foreach($product["attributes"] as $key => $value){
            /*
              Insert links between products and attributes
              //
              Infoga länkar mellan produkter och attribut
            */
            $values = explode(",",$value);

            foreach($values as $val){

              $atttype = AttributeType::where('code',$key)->first();
              $attvalue = AttributeValue::where('code',$val)->first();

              ProductAttribute::create([
                'product_id' => $pid,
                'attribute_type_id' => $atttype->id,
                'attribute_value_id' => $attvalue->id
              ]);

            }
          }
        }

    }
}
