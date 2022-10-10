<?php

namespace App\Models;


use App\Models\ServicesImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use  HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nameArabic',
        'extraDetails',
        'extraDetailsArabic',
        'user_id'
    ];

    public function servicesImages(){
        return $this->hasMany(ServicesImage::class);
    }
}
