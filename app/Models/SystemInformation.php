<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SystemInformation extends Model
{
    use HasFactory;

    protected $fillable = [

            'ins_name',
            'logo',
            'white_logo',
            'branch_id',
            'designation_id',
            'keyword',
            'description',
            'develop_by',
            'icon',
            'address',
            'email',
            'phone',11,
            'main_url',
            'front_url',
            'tax',
            'charge',
            'usdollar',

    ];
}
