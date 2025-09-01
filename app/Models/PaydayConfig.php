<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaydayConfig extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'salary_day',
        'advance_day'
    ];
    
    /**
     * Récupère la configuration actuelle ou crée une configuration par défaut
     */
    public static function getCurrentOrCreate(): self
    {
        $config = self::first();
        
        if (!$config) {
            $config = self::create([
                'salary_day' => 25,
                'advance_day' => 15
            ]);
        }
        
        return $config;
    }
}
