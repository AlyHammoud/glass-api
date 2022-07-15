<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainInfo extends Model
{
    use  HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'whatsapp',
        'totalProjects',
        'totalCustomers',
        'totalExperience',
        'aboutUsEnglish',
        'aboutUsArabic',
        'facebook',
        'youtube',
        'instagram',
    ];

    public function mainImages(){
        return $this->hasMany(MainImage::class,'main_infos_id');
    }
    public function CustomersReviews(){
        return $this->hasMany(CustomersReview::class,'main_infos_id');
    }
}
