<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeType extends Model
{
    protected $fillable = [
      'name',
      'code'
    ];

    public function possibleAttributeValues(){
      return $this->hasMany('\App\AttributeValue', 'attribute_type_id', 'id');
    }

}
