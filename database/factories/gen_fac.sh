#!/bin/bash

# Script de génération des factories Laravel
# À exécuter dans le dossier database/factories

echo "🚀 Génération des factories Laravel..."

# Fonction pour créer ou mettre à jour une factory
create_or_update_factory() {
    local filename="$1"
    local content="$2"
    
    if [ -f "$filename" ]; then
        echo "✏️  Mise à jour de $filename"
    else
        echo "✨ Création de $filename"
    fi
    
    cat > "$filename" << EOF
$content
EOF

EOF
}

# 1. ProduitStockFactory
create_or_update_factory "ProduitStockFactory.php" '<?php

namespace Database\Factories;

use App\Models\ProduitStock;
use App\Models\ProduitFixe;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitStockFactory extends Factory
{
    protected $model = ProduitStock::class;

    public function definition(): array
    {
        return [
            "id_produit" => ProduitFixe::factory(),
            "quantite_en_stock" => $this->faker->numberBetween(0, 1000),
            "quantite_invendu" => $this->faker->numberBetween(0, 100),
            "quantite_avarie" => $this->faker->numberBetween(0, 50),
        ];
    }
}'

# 2. PlanningFactory
create_or_update_factory "PlanningFactory.php" '<?php

namespace Database\Factories;

use App\Models\Planning;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanningFactory extends Factory
{
    protected $model = Planning::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(["tache", "repos"]);
        
        return [
            "libelle" => $this->faker->sentence(3),
            "employe" => User::factory(),
            "type" => $type,
            "date" => $this->faker->date(),
            "heure_debut" => $type === "tache" ? $this->faker->time() : null,
            "heure_fin" => $type === "tache" ? $this->faker->time() : null,
        ];
    }

    public function tache(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "tache",
            "heure_debut" => $this->faker->time(),
            "heure_fin" => $this->faker->time(),
        ]);
    }

    public function repos(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "repos",
            "heure_debut" => null,
            "heure_fin" => null,
        ]);
    }
}'

# 3. EvaluationFactory
create_or_update_factory "EvaluationFactory.php" '<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluationFactory extends Factory
{
    protected $model = Evaluation::class;

    public function definition(): array
    {
        return [
            "user_id" => User::factory(),
            "note" => $this->faker->randomFloat(2, 0, 20),
            "appreciation" => $this->faker->paragraph(),
        ];
    }
}'

# 4. ReposCongeFactory  
create_or_update_factory "ReposCongeFactory.php" '<?php

namespace Database\Factories;

use App\Models\ReposConge;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReposCongeFactory extends Factory
{
    protected $model = ReposConge::class;

    public function definition(): array
    {
        $hasConge = $this->faker->boolean(30);
        
        return [
            "employe_id" => User::factory(),
            "jour" => $this->faker->randomElement(["lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche"]),
            "conges" => $hasConge ? $this->faker->numberBetween(1, 30) : null,
            "debut_c" => $hasConge ? $this->faker->date() : null,
            "raison_c" => $hasConge ? $this->faker->randomElement(["maladie", "evenement", "accouchement", "autre"]) : null,
            "autre_raison" => $hasConge && $this->faker->boolean(20) ? $this->faker->sentence() : null,
        ];
    }
}'

# 5. ExtraFactory
create_or_update_factory "ExtraFactory.php" '<?php

namespace Database\Factories;

use App\Models\Extra;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExtraFactory extends Factory
{
    protected $model = Extra::class;

    public function definition(): array
    {
        return [
            "secteur" => $this->faker->randomElement(["vente", "production", "administration", "logistique", "securite"]),
            "heure_arriver_adequat" => $this->faker->time("H:i:s", "09:00:00"),
            "heure_depart_adequat" => $this->faker->time("H:i:s", "18:00:00"),
            "salaire_adequat" => $this->faker->randomFloat(2, 50000, 500000),
            "interdit" => $this->faker->optional()->paragraph(),
            "regles" => $this->faker->optional()->paragraph(),
            "age_adequat" => $this->faker->numberBetween(18, 65),
        ];
    }
}'

# 6. DeliFactory
create_or_update_factory "DeliFactory.php" '<?php

namespace Database\Factories;

use App\Models\Deli;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliFactory extends Factory
{
    protected $model = Deli::class;

    public function definition(): array
    {
        return [
            "nom" => $this->faker->word(),
            "description" => $this->faker->paragraph(),
            "montant" => $this->faker->randomFloat(2, 1000, 100000),
        ];
    }
}'

# 7. DeliUserFactory
create_or_update_factory "DeliUserFactory.php" '<?php

namespace Database\Factories;

use App\Models\DeliUser;
use App\Models\Deli;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliUserFactory extends Factory
{
    protected $model = DeliUser::class;

    public function definition(): array
    {
        return [
            "deli_id" => Deli::factory(),
            "user_id" => User::factory(),
            "date_incident" => $this->faker->date(),
        ];
    }
}'

# 8. CategoryFactory
create_or_update_factory "CategoryFactory.php" '<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            "name" => $this->faker->randomElement([
                "sac", "salaire", "as", "materiel", "reparation", 
                "matiere_premiere", "transport", "supermarche", "vente", "autre"
            ]),
        ];
    }
}'

# 9. TransactionFactory
create_or_update_factory "TransactionFactory.php" '<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            "type" => $this->faker->randomElement(["income", "outcome"]),
            "category_id" => Category::factory(),
            "amount" => $this->faker->randomFloat(2, 100, 1000000),
            "date" => $this->faker->dateTime(),
            "description" => $this->faker->optional()->paragraph(),
        ];
    }

    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "income",
        ]);
    }

    public function outcome(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "outcome",
        ]);
    }
}'

# 10. StagiaireFactory
create_or_update_factory "StagiaireFactory.php" '<?php

namespace Database\Factories;

use App\Models\Stagiaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class StagiaireFactory extends Factory
{
    protected $model = Stagiaire::class;

    public function definition(): array
    {
        $dateDebut = $this->faker->dateTimeBetween("-6 months", "now");
        $dateFin = $this->faker->dateTimeBetween($dateDebut, "+6 months");
        
        return [
            "nom" => $this->faker->lastName(),
            "prenom" => $this->faker->firstName(),
            "email" => $this->faker->unique()->safeEmail(),
            "telephone" => $this->faker->phoneNumber(),
            "ecole" => $this->faker->company(),
            "niveau_etude" => $this->faker->randomElement(["Licence", "Master", "BTS", "DUT", "Doctorat"]),
            "filiere" => $this->faker->randomElement(["Informatique", "Gestion", "Marketing", "Comptabilité", "RH"]),
            "date_debut" => $dateDebut->format("Y-m-d"),
            "date_fin" => $dateFin->format("Y-m-d"),
            "departement" => $this->faker->randomElement(["IT", "RH", "Comptabilité", "Marketing", "Production"]),
            "nature_travail" => $this->faker->paragraph(),
            "remuneration" => $this->faker->randomFloat(2, 0, 100000),
            "appreciation" => $this->faker->optional()->paragraph(),
            "type_stage" => $this->faker->randomElement(["academique", "professionnel"]),
            "rapport_genere" => $this->faker->boolean(),
        ];
    }
}'

# 11. ProduitRecu1Factory
create_or_update_factory "ProduitRecu1Factory.php" '<?php

namespace Database\Factories;

use App\Models\ProduitRecu1;
use App\Models\ProduitFixe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitRecu1Factory extends Factory
{
    protected $model = ProduitRecu1::class;

    public function definition(): array
    {
        return [
            "produit_id" => ProduitFixe::factory(),
            "quantite" => $this->faker->numberBetween(1, 1000),
            "producteur_id" => User::factory(),
            "pointeur_id" => User::factory(),
            "date_reception" => $this->faker->dateTime(),
            "remarques" => $this->faker->optional()->paragraph(),
        ];
    }
}'

# 12. BagAssignmentFactory
create_or_update_factory "BagAssignmentFactory.php" '<?php

namespace Database\Factories;

use App\Models\BagAssignment;
use App\Models\Bag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BagAssignmentFactory extends Factory
{
    protected $model = BagAssignment::class;

    public function definition(): array
    {
        return [
            "bag_id" => Bag::factory(),
            "user_id" => User::factory(),
            "quantity_assigned" => $this->faker->numberBetween(1, 100),
            "notes" => $this->faker->optional()->sentence(),
        ];
    }
}'

# 13. BagReceptionFactory
create_or_update_factory "BagReceptionFactory.php" '<?php

namespace Database\Factories;

use App\Models\BagReception;
use App\Models\BagAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class BagReceptionFactory extends Factory
{
    protected $model = BagReception::class;

    public function definition(): array
    {
        return [
            "bag_assignment_id" => BagAssignment::factory(),
            "quantity_received" => $this->faker->numberBetween(1, 100),
            "notes" => $this->faker->optional()->sentence(),
        ];
    }
}'

# 14. BagSaleFactory
create_or_update_factory "BagSaleFactory.php" '<?php

namespace Database\Factories;

use App\Models\BagSale;
use App\Models\BagReception;
use Illuminate\Database\Eloquent\Factories\Factory;

class BagSaleFactory extends Factory
{
    protected $model = BagSale::class;

    public function definition(): array
    {
        $quantityReceived = $this->faker->numberBetween(10, 100);
        $quantitySold = $this->faker->numberBetween(0, $quantityReceived);
        
        return [
            "bag_reception_id" => BagReception::factory(),
            "quantity_sold" => $quantitySold,
            "quantity_unsold" => $quantityReceived - $quantitySold,
            "notes" => $this->faker->optional()->sentence(),
            "is_recovered" => $this->faker->boolean(),
        ];
    }
}'

# 15. ConfigurationFactory
create_or_update_factory "ConfigurationFactory.php" '<?php

namespace Database\Factories;

use App\Models\Configuration;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConfigurationFactory extends Factory
{
    protected $model = Configuration::class;

    public function definition(): array
    {
        return [
            "first_config" => $this->faker->boolean(),
            "flag1" => $this->faker->boolean(),
            "flag2" => $this->faker->boolean(),
            "flag3" => $this->faker->boolean(),
            "flag4" => $this->faker->boolean(),
        ];
    }
}'

# 16. SoldeCpFactory
create_or_update_factory "SoldeCpFactory.php" '<?php

namespace Database\Factories;

use App\Models\SoldeCp;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoldeCpFactory extends Factory
{
    protected $model = SoldeCp::class;

    public function definition(): array
    {
        return [
            "montant" => $this->faker->randomFloat(2, 0, 10000000),
            "derniere_mise_a_jour" => $this->faker->date(),
            "description" => $this->faker->optional()->paragraph(),
        ];
    }
}'

# 17. HistoriqueSoldeCpFactory
create_or_update_factory "HistoriqueSoldeCpFactory.php" '<?php

namespace Database\Factories;

use App\Models\HistoriqueSoldeCp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoriqueSoldeCpFactory extends Factory
{
    protected $model = HistoriqueSoldeCp::class;

    public function definition(): array
    {
        $soldeAvant = $this->faker->randomFloat(2, 0, 1000000);
        $montant = $this->faker->randomFloat(2, 100, 100000);
        $typeOperation = $this->faker->randomElement(["versement", "depense", "ajustement"]);
        
        $soldeApres = match($typeOperation) {
            "versement" => $soldeAvant + $montant,
            "depense" => $soldeAvant - $montant,
            default => $this->faker->randomFloat(2, 0, 1000000)
        };
        
        return [
            "montant" => $montant,
            "type_operation" => $typeOperation,
            "operation_id" => $this->faker->optional()->randomNumber(),
            "solde_avant" => $soldeAvant,
            "solde_apres" => $soldeApres,
            "user_id" => User::factory(),
            "description" => $this->faker->optional()->paragraph(),
        ];
    }
}'

# 18. ManquantTemporaireFactory
create_or_update_factory "ManquantTemporaireFactory.php" '<?php

namespace Database\Factories;

use App\Models\ManquantTemporaire;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManquantTemporaireFactory extends Factory
{
    protected $model = ManquantTemporaire::class;

    public function definition(): array
    {
        return [
            "employe_id" => User::factory(),
            "montant" => $this->faker->numberBetween(0, 1000000),
            "explication" => $this->faker->optional()->paragraph(),
            "statut" => $this->faker->randomElement(["en_attente", "ajuste", "valide"]),
            "commentaire_dg" => $this->faker->optional()->paragraph(),
            "valide_par" => $this->faker->boolean(50) ? User::factory() : null,
        ];
    }
}'

# 19. CashierSessionFactory
create_or_update_factory "CashierSessionFactory.php" '<?php

namespace Database\Factories;

use App\Models\CashierSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashierSessionFactory extends Factory
{
    protected $model = CashierSession::class;

    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween("-1 month", "now");
        $endTime = $this->faker->boolean(70) ? $this->faker->dateTimeBetween($startTime, "now") : null;
        
        $initialCash = $this->faker->randomFloat(2, 10000, 500000);
        $initialChange = $this->faker->randomFloat(2, 5000, 100000);
        $initialMobile = $this->faker->randomFloat(2, 0, 200000);
        
        return [
            "user_id" => User::factory(),
            "start_time" => $startTime,
            "end_time" => $endTime,
            "initial_cash" => $initialCash,
            "initial_change" => $initialChange,
            "initial_mobile_balance" => $initialMobile,
            "final_cash" => $endTime ? $this->faker->randomFloat(2, 0, $initialCash * 2) : null,
            "final_change" => $endTime ? $this->faker->randomFloat(2, 0, $initialChange * 2) : null,
            "final_mobile_balance" => $endTime ? $this->faker->randomFloat(2, 0, $initialMobile * 2) : null,
            "cash_remitted" => $endTime ? $this->faker->randomFloat(2, 0, 1000000) : null,
            "total_withdrawals" => $this->faker->randomFloat(2, 0, 100000),
            "discrepancy" => $endTime ? $this->faker->randomFloat(2, -50000, 50000) : null,
            "notes" => $this->faker->optional()->paragraph(),
            "end_notes" => $endTime ? $this->faker->optional()->paragraph() : null,
        ];
    }
}'

# 20. CashWithdrawalFactory
create_or_update_factory "CashWithdrawalFactory.php" '<?php

namespace Database\Factories;

use App\Models\CashWithdrawal;
use App\Models\CashierSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashWithdrawalFactory extends Factory
{
    protected $model = CashWithdrawal::class;

    public function definition(): array
    {
        return [
            "cashier_session_id" => CashierSession::factory(),
            "amount" => $this->faker->randomFloat(2, 1000, 50000),
            "reason" => $this->faker->randomElement(["Achat fournitures", "Transport", "Urgence", "Maintenance", "Autre"]),
            "withdrawn_by" => $this->faker->name(),
            "created_at" => $this->faker->dateTime(),
        ];
    }
}'

# 21. ProduitFactory
create_or_update_factory "ProduitFactory.php" '<?php

namespace Database\Factories;

use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    public function definition(): array
    {
        return [
            "nom" => $this->faker->word(),
            "reference" => $this->faker->unique()->regexify("[A-Z]{2}[0-9]{4}"),
            "type" => $this->faker->randomElement(["magasin", "boisson"]),
            "quantite" => $this->faker->numberBetween(0, 1000),
            "prix_unitaire" => $this->faker->randomFloat(2, 100, 10000),
            "seuil_alerte" => $this->faker->numberBetween(1, 20),
        ];
    }

    public function magasin(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "magasin",
        ]);
    }

    public function boisson(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "boisson",
        ]);
    }
}'

# 22. MouvementStockFactory
create_or_update_factory "MouvementStockFactory.php" '<?php

namespace Database\Factories;

use App\Models\MouvementStock;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MouvementStockFactory extends Factory
{
    protected $model = MouvementStock::class;

    public function definition(): array
    {
        return [
            "produit_id" => Produit::factory(),
            "type" => $this->faker->randomElement(["entree", "sortie"]),
            "quantite" => $this->faker->numberBetween(1, 100),
            "user_id" => User::factory(),
            "motif" => $this->faker->sentence(),
        ];
    }

    public function entree(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "entree",
            "motif" => "Réapprovisionnement stock",
        ]);
    }

    public function sortie(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "sortie",
            "motif" => "Vente produit",
        ]);
    }
}'

# 23. InventaireFactory
create_or_update_factory "InventaireFactory.php" '<?php

namespace Database\Factories;

use App\Models\Inventaire;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventaireFactory extends Factory
{
    protected $model = Inventaire::class;

    public function definition(): array
    {
        $quantiteTheorique = $this->faker->numberBetween(0, 1000);
        $quantitePhysique = $this->faker->numberBetween(0, $quantiteTheorique + 50);
        $prixUnitaire = $this->faker->randomFloat(2, 100, 10000);
        $valeurManquant = abs($quantiteTheorique - $quantitePhysique) * $prixUnitaire;
        
        return [
            "date_inventaire" => $this->faker->date(),
            "produit_id" => Produit::factory(),
            "quantite_theorique" => $quantiteTheorique,
            "quantite_physique" => $quantitePhysique,
            "valeur_manquant" => $valeurManquant,
            "user_id" => User::factory(),
            "commentaire" => $this->faker->optional()->paragraph(),
        ];
    }
}'

# 24. ProductGroupFactory
create_or_update_factory "ProductGroupFactory.php" '<?php

namespace Database\Factories;

use App\Models\ProductGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductGroupFactory extends Factory
{
    protected $model = ProductGroup::class;

    public function definition(): array
    {
        return [
            "name" => $this->faker->words(2, true),
            "description" => $this->faker->optional()->paragraph(),
            "user_id" => User::factory(),
        ];
    }
}'

# 25. ProductFactory
create_or_update_factory "ProductFactory.php" '<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            "name" => $this->faker->word(),
            "type" => $this->faker->randomElement(["alimentaire", "boisson", "materiel", "autre"]),
            "price" => $this->faker->randomFloat(2, 100, 50000),
            "product_group_id" => ProductGroup::factory(),
        ];
    }
}'

# 26. MissingCalculationFactory
create_or_update_factory "MissingCalculationFactory.php" '<?php

namespace Database\Factories;

use App\Models\MissingCalculation;
use App\Models\ProductGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MissingCalculationFactory extends Factory
{
    protected $model = MissingCalculation::class;

    public function definition(): array
    {
        return [
            "product_group_id" => ProductGroup::factory(),
            "user_id" => User::factory(),
            "date" => $this->faker->date(),
            "title" => $this->faker->sentence(4),
            "status" => $this->faker->randomElement(["open", "closed"]),
            "total_amount" => $this->faker->randomFloat(2, 0, 1000000),
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            "status" => "open",
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            "status" => "closed",
        ]);
    }
}'

# 27. MissingItemFactory
create_or_update_factory "MissingItemFactory.php" '<?php

namespace Database\Factories;

use App\Models\MissingItem;
use App\Models\MissingCalculation;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class MissingItemFactory extends Factory
{
    protected $model = MissingItem::class;

    public function definition(): array
    {
        $expectedQuantity = $this->faker->numberBetween(1, 100);
        $actualQuantity = $this->faker->numberBetween(0, $expectedQuantity);
        $missingQuantity = $expectedQuantity - $actualQuantity;
        $unitPrice = $this->faker->randomFloat(2, 100, 10000);
        
        return [
            "missing_calculation_id" => MissingCalculation::factory(),
            "product_id" => Product::factory(),
            "expected_quantity" => $expectedQuantity,
            "actual_quantity" => $actualQuantity,
            "missing_quantity" => $missingQuantity,
            "amount" => $missingQuantity * $unitPrice,
        ];
    }
}'

# 28. CashDistributionFactory
create_or_update_factory "CashDistributionFactory.php" '<?php

namespace Database\Factories;

use App\Models\CashDistribution;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashDistributionFactory extends Factory
{
    protected $model = CashDistribution::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(["en_cours", "cloture"]);
        $billAmount = $this->faker->randomFloat(2, 50000, 1000000);
        $initialCoinAmount = $this->faker->randomFloat(2, 10000, 200000);
        $salesAmount = $this->faker->randomFloat(2, 0, $billAmount + $initialCoinAmount);
        
        return [
            "user_id" => User::factory(),
            "date" => $this->faker->date(),
            "bill_amount" => $billAmount,
            "initial_coin_amount" => $initialCoinAmount,
            "final_coin_amount" => $status === "cloture" ? $this->faker->randomFloat(2, 0, $initialCoinAmount) : null,
            "deposited_amount" => $status === "cloture" ? $this->faker->randomFloat(2, 0, $salesAmount) : null,
            "sales_amount" => $salesAmount,
            "missing_amount" => $status === "cloture" ? $this->faker->randomFloat(2, -50000, 100000) : null,
            "status" => $status,
            "notes" => $this->faker->optional()->paragraph(),
            "closed_by" => $status === "cloture" ? User::factory() : null,
            "closed_at" => $status === "cloture" ? $this->faker->dateTime() : null,
        ];
    }

    public function enCours(): static
    {
        return $this->state(fn (array $attributes) => [
            "status" => "en_cours",
            "final_coin_amount" => null,
            "deposited_amount" => null,
            "missing_amount" => null,
            "closed_by" => null,
            "closed_at" => null,
        ]);
    }

    public function cloture(): static
    {
        return $this->state(fn (array $attributes) => [
            "status" => "cloture",
            "final_coin_amount" => $this->faker->randomFloat(2, 0, 200000),
            "deposited_amount" => $this->faker->randomFloat(2, 0, 1000000),
            "missing_amount" => $this->faker->randomFloat(2, -50000, 100000),
            "closed_by" => User::factory(),
            "closed_at" => $this->faker->dateTime(),
        ]);
    }
}'

echo "✅ Toutes les factories ont été générées avec succès!"
echo ""
echo "📋 Résumé des factories créées:"
echo "   • ProduitStockFactory.php"
echo "   • PlanningFactory.php" 
echo "   • EvaluationFactory.php"
echo "   • ReposCongeFactory.php"
echo "   • ExtraFactory.php"
echo "   • DeliFactory.php"
echo "   • DeliUserFactory.php"
echo "   • CategoryFactory.php"
echo "   • TransactionFactory.php"
echo "   • StagiaireFactory.php"
echo "   • ProduitRecu1Factory.php"
echo "   • BagAssignmentFactory.php"
echo "   • BagReceptionFactory.php"
echo "   • BagSaleFactory.php"
echo "   • ConfigurationFactory.php"
echo "   • SoldeCpFactory.php"
echo "   • HistoriqueSoldeCpFactory.php"
echo "   • ManquantTemporaireFactory.php"
echo "   • CashierSessionFactory.php"
echo "   • CashWithdrawalFactory.php"
echo "   • ProduitFactory.php"
echo "   • MouvementStockFactory.php"
echo "   • InventaireFactory.php"
echo "   • ProductGroupFactory.php"
echo "   • ProductFactory.php"
echo "   • MissingCalculationFactory.php"
echo "   • MissingItemFactory.php"
echo "   • CashDistributionFactory.php"
echo ""
echo "🔧 Notes importantes:"
echo "   • Vérifiez les noms des modèles dans vos fichiers Model"
echo "   • Adaptez les namespaces si nécessaire"
echo "   • Certaines factories référencent des modèles qui pourraient ne pas exister encore (ex: Bag, ProduitFixe)"
echo "   • Les relations foreign key sont gérées automatiquement"
echo "   • Des méthodes states sont incluses pour certaines factories"
echo ""
echo "🚀 Utilisation:"
echo "   php artisan tinker"
echo "   ModelName::factory()->count(10)->create()"