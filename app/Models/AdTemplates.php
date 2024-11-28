<?php

namespace App\Models;

use App\Enums\AdTemplateStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdTemplates extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'canva_url',
        'ad_id',
    ];

    protected $casts = [
        'status' => AdTemplateStatus::class
    ];

    public function ad()
    {
        return $this->belongsTo(Ads::class, 'ad_id');
    }
}
