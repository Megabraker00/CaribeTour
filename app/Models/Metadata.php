<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Metadata extends Model {

    protected $table = 'meta_data';

    protected $fillable = ['meta_dataable_type', 'meta_dataable_id', 'meta_data'];

    public function meta_dataable()
    {
        return $this->morphTo();
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'meta_dataable');
    }

    public function clients(): MorphMany
    {
        return $this->morphMany(Client::class, 'meta_dataable');
    }

    public function getMetaDataAttribute($value)
    {
        // Decodifica el campo meta_data de JSON a un array
        return json_decode($value, true);
    }
    
}