<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $fillable = [
      'product_id',
      'attribute_type_id',
      'attribute_value_id'
    ];

    public function product(){
      return $this->hasOne('\App\Product', 'id', 'product_id');
    }

    public function attributeType(){
      return $this->hasOne('\App\AttributeType', 'id', 'attribute_type_id');
    }

    public function attributeValue(){
      return $this->hasOne('\App\AttributeValue', 'id', 'attribute_value_id');
    }
}
