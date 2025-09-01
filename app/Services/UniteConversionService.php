<?php

namespace App\Services;

use App\Enums\UniteMinimale;
use Illuminate\Support\Facades\Log;

class UniteConversionService
{
    private array $conversions = [
        'kg' => ['base' => 'g', 'facteur' => 1000],
        'g' => ['base' => 'g', 'facteur' => 1],
        'l' => ['base' => 'ml', 'facteur' => 1000],
        'litre' => ['base' => 'ml', 'facteur' => 1000], // Ajout de 'litre' comme alias de 'l'
        'dl' => ['base' => 'ml', 'facteur' => 100],
        'cl' => ['base' => 'ml', 'facteur' => 10],
        'ml' => ['base' => 'ml', 'facteur' => 1],
        'cc' => ['base' => 'ml', 'facteur' => 5],
        'cs' => ['base' => 'ml', 'facteur' => 15],
        'pincee' => ['base' => 'g', 'facteur' => 1.5],
        'unite' => ['base' => 'unite', 'facteur' => 1]
    ];

    public function convertir(float $quantite, $uniteSource, $uniteCible): float
    {
        Log::info("Début de conversion: {$quantite} de l'unité source à l'unité cible");
        
        // Normaliser les unités en chaînes de caractères
        $uniteSourceString = $this->normaliserUnite($uniteSource);
        $uniteCibleString = $this->normaliserUnite($uniteCible);
        
        Log::info("Unités normalisées: source='{$uniteSourceString}', cible='{$uniteCibleString}'");

        // Vérification si les unités existent dans les conversions
        if (!isset($this->conversions[$uniteSourceString])) {
            Log::error("Unité source '{$uniteSourceString}' non reconnue dans le tableau de conversions");
            Log::error("Unités disponibles: " . implode(", ", array_keys($this->conversions)));
            throw new \InvalidArgumentException("L'unité source '{$uniteSourceString}' n'est pas reconnue.");
        }
        
        if (!isset($this->conversions[$uniteCibleString])) {
            Log::error("Unité cible '{$uniteCibleString}' non reconnue dans le tableau de conversions");
            Log::error("Unités disponibles: " . implode(", ", array_keys($this->conversions)));
            throw new \InvalidArgumentException("L'unité cible '{$uniteCibleString}' n'est pas reconnue.");
        }

        // Si les unités sont identiques, aucune conversion n'est nécessaire
        if ($uniteSourceString === $uniteCibleString) {
            Log::info("Unités identiques, aucune conversion nécessaire");
            return $quantite;
        }

        // Conversion en unité de base
        $baseUniteSource = $this->conversions[$uniteSourceString]['base'];
        $baseUniteCible = $this->conversions[$uniteCibleString]['base'];
        
        Log::info("Base de l'unité source: {$baseUniteSource}");
        Log::info("Base de l'unité cible: {$baseUniteCible}");

        // Vérification de la compatibilité des bases
        if ($baseUniteSource !== $baseUniteCible) {
            Log::error("Bases incompatibles: {$baseUniteSource} vs {$baseUniteCible}");
            throw new \InvalidArgumentException("Les unités source '{$uniteSourceString}' et cible '{$uniteCibleString}' ne sont pas compatibles (bases différentes: {$baseUniteSource} vs {$baseUniteCible}).");
        }

        $facteurSource = $this->conversions[$uniteSourceString]['facteur'];
        $facteurCible = $this->conversions[$uniteCibleString]['facteur'];
        
        Log::info("Facteur source: {$facteurSource}");
        Log::info("Facteur cible: {$facteurCible}");
        
        // Conversion en unité de base
        $quantiteBase = $quantite * $facteurSource;
        Log::info("Quantité convertie en unité de base ({$baseUniteSource}): {$quantiteBase}");
        
        // Conversion de l'unité de base vers l'unité cible
        $quantiteFinale = $quantiteBase / $facteurCible;
        Log::info("Quantité finale convertie en {$uniteCibleString}: {$quantiteFinale}");
        
        return $quantiteFinale;
    }

    /**
     * Vérifie si deux unités sont compatibles sans lancer d'exception.
     *
     * @param string|UniteMinimale $uniteSource
     * @param string|UniteMinimale $uniteCible
     * @return array [bool $estCompatible, string|null $messageErreur]
     */
    public function verifierCompatibilite($uniteSource, $uniteCible): array
    {
        try {
            // Enregistrer les types d'entrée pour le débogage
            Log::info("Types d'entrée - uniteSource: " . gettype($uniteSource) . ", uniteCible: " . gettype($uniteCible));
            
            if ($uniteSource instanceof UniteMinimale) {
                Log::info("uniteSource est un objet UniteMinimale avec valeur: " . $uniteSource->value);
            } elseif (is_string($uniteSource)) {
                Log::info("uniteSource est une chaîne: " . $uniteSource);
            }
            
            if ($uniteCible instanceof UniteMinimale) {
                Log::info("uniteCible est un objet UniteMinimale avec valeur: " . $uniteCible->value);
            } elseif (is_string($uniteCible)) {
                Log::info("uniteCible est une chaîne: " . $uniteCible);
            }
            
            // Normaliser les unités en chaînes de caractères
            $uniteSourceString = $this->normaliserUnite($uniteSource);
            $uniteCibleString = $this->normaliserUnite($uniteCible);
            
            Log::info("Unités normalisées pour vérification: source='{$uniteSourceString}', cible='{$uniteCibleString}'");

            // Vérification si les unités existent dans les conversions
            if (!isset($this->conversions[$uniteSourceString])) {
                Log::error("Unité source '{$uniteSourceString}' non reconnue dans le tableau de conversions");
                Log::error("Unités disponibles: " . implode(", ", array_keys($this->conversions)));
                return [false, "L'unité source '{$uniteSourceString}' n'est pas reconnue dans le système de conversion."];
            }
            
            if (!isset($this->conversions[$uniteCibleString])) {
                Log::error("Unité cible '{$uniteCibleString}' non reconnue dans le tableau de conversions");
                Log::error("Unités disponibles: " . implode(", ", array_keys($this->conversions)));
                return [false, "L'unité cible '{$uniteCibleString}' n'est pas reconnue dans le système de conversion."];
            }

            // Si les unités sont identiques, elles sont compatibles
            if ($uniteSourceString === $uniteCibleString) {
                Log::info("Unités identiques, donc compatibles: {$uniteSourceString}");
                return [true, null];
            }

            // Vérification de la compatibilité des bases
            $baseUniteSource = $this->conversions[$uniteSourceString]['base'];
            $baseUniteCible = $this->conversions[$uniteCibleString]['base'];
            
            Log::info("Base de l'unité source: {$baseUniteSource}");
            Log::info("Base de l'unité cible: {$baseUniteCible}");

            if ($baseUniteSource !== $baseUniteCible) {
                $message = "Les unités '{$uniteSourceString}' et '{$uniteCibleString}' ne sont pas compatibles. La première est de type '{$baseUniteSource}' et la seconde de type '{$baseUniteCible}'.";
                Log::error($message);
                return [false, $message];
            }

            Log::info("Les unités '{$uniteSourceString}' et '{$uniteCibleString}' sont compatibles (même base: {$baseUniteSource})");
            return [true, null];
        } catch (\Exception $e) {
            Log::error("Exception pendant la vérification de compatibilité: " . $e->getMessage());
            Log::error("Trace: " . $e->getTraceAsString());
            return [false, "Erreur lors de la vérification de compatibilité: " . $e->getMessage()];
        }
    }

    /**
     * Normalise l'unité en une chaîne de caractères, qu'elle soit une chaîne ou un objet UniteMinimale
     *
     * @param string|UniteMinimale $unite
     * @return string
     */
    private function normaliserUnite($unite): string
    {
        if ($unite instanceof UniteMinimale) {
            return $unite->value;
        } elseif (is_string($unite)) {
            return strtolower($unite); // Assurez-vous que les chaînes sont en minuscules pour correspondre aux clés du tableau
        } else {
            $type = gettype($unite);
            Log::error("Format d'unité non valide: type = {$type}");
            if (is_object($unite)) {
                Log::error("Classe de l'objet: " . get_class($unite));
            }
            throw new \InvalidArgumentException("Format d'unité non valide (type: {$type})");
        }
    }

    public function obtenirConversions(): array
    {
        return $this->conversions;
    }
}