<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\File;

class Sellers extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'level_education',
        'picture',
        'observations'
    ];

    /**
     * Valida si el vendedor tiene sus datos e identificación completos.
     *
     * @param int $sellerId
     * @return bool
     */
    public static function checkCompleteness($sellerId)
    {
        $seller = self::find($sellerId);
        if (!$seller) {
            return false;
        }

        $requiredFields = [
            'name',
            'email',
            'address',
            'phone',
            'level_education',
            'picture',
        ];

        foreach ($requiredFields as $field) {
            if (empty($seller->$field)) {
                return false;
            }
        }

        if (!File::exists(public_path($seller->picture))) {
            return false;
        }

        return true;
    }
}