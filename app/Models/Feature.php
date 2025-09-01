<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Vérifie si une fonctionnalité est active
     *
     * @param string $code
     * @return bool
     */
    public static function isActive(string $code): bool
    {
        // Utiliser le cache pour éviter des requêtes db répétées
        return Cache::remember("feature:{$code}", now()->addMinutes(30), function () use ($code) {
            $feature = self::where('code', $code)->first();
            return $feature ? $feature->active : true; // Par défaut actif si non trouvé
        });
    }

    /**
     * Vérifie si les fonctionnalités d'une catégorie sont actives
     *
     * @param string $category
     * @return array
     */
    public static function getActiveByCategory(string $category): array
    {
        return Cache::remember("features:category:{$category}", now()->addMinutes(30), function () use ($category) {
            return self::where('category', $category)
                ->get()
                ->pluck('active', 'code')
                ->toArray();
        });
    }

    /**
     * Réinitialiser le cache d'une fonctionnalité
     * 
     * @param string $code
     * @return void
     */
    public static function resetCache(string $code): void
    {
        Cache::forget("feature:{$code}");
        
        $feature = self::where('code', $code)->first();
        if ($feature) {
            Cache::forget("features:category:{$feature->category}");
        }
    }

    /**
     * Réinitialiser tout le cache des fonctionnalités
     * 
     * @return void
     */
    public static function resetAllCache(): void
    {
        $categories = self::distinct()->pluck('category');
        foreach ($categories as $category) {
            Cache::forget("features:category:{$category}");
        }

        $codes = self::pluck('code');
        foreach ($codes as $code) {
            Cache::forget("feature:{$code}");
        }
    }
}
