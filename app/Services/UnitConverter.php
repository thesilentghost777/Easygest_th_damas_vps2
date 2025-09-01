<?php

namespace App\Services;

class UnitConverter {
    private static $conversionRules = [
        'g' => ['unit' => 'kg', 'factor' => 0.001],
        'kg' => ['unit' => 'kg', 'factor' => 1],
        'ml' => ['unit' => 'litre', 'factor' => 0.001],
        'cl' => ['unit' => 'litre', 'factor' => 0.01],
        'dl' => ['unit' => 'litre', 'factor' => 0.1],
        'l' => ['unit' => 'litre', 'factor' => 1],
        'cc' => ['unit' => 'litre', 'factor' => 0.001],
        'cs' => ['unit' => 'litre', 'factor' => 0.015],
        'pincee' => ['unit' => 'kg', 'factor' => 0.001],
        'unite' => ['unit' => 'unité', 'factor' => 1]
    ];

    public static function convert($value, $unit) {
        // Si l'unité n'est pas dans nos règles, retourner telle quelle
        if (!isset(self::$conversionRules[$unit])) {
            return [$value, $unit];
        }

        $rule = self::$conversionRules[$unit];
        
        // Pour les unités de masse (g)
        if ($unit === 'g') {
            // Si la valeur est supérieure à 1000g (1kg), convertir en kg
            if ($value >= 1000) {
                return [($value * $rule['factor']), $rule['unit']];
            } else {
                // Sinon, garder en grammes
                return [$value, $unit]; 
            }
        } 
        // Pour les unités de volume (ml, cl, dl)
        else if (in_array($unit, ['ml', 'cl', 'dl'])) {
            $litres = $value * $rule['factor'];
            
            // Si la valeur convertie est supérieure ou égale à 1 litre
            if ($litres >= 1) {
                return [$litres, $rule['unit']];
            } else {
                // Sinon, garder l'unité d'origine
                return [$value, $unit];
            }
        }
        // Pour toutes les autres unités, appliquer la conversion standard
        else {
            $convertedValue = $value * $rule['factor'];
            return [$convertedValue, $rule['unit']];
        }
    }
}