<?php

namespace App\Models;

use App\Enums\AdStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;

    protected $casts = [
        'status' =>  AdStatus::class,
    ];
    protected $fillable = [
        'title',
        'description',
        'url',
        'status',
    ];

    public function templates()
    {
        return $this->hasMany(AdTemplates::class);
    }
}
