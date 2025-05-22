<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Kyc extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'category',
        'proof_type',
        'kyc_status',
        'kyc_approval_at',
        'kyc_approval_description',
        'remarks',
    ];
}
