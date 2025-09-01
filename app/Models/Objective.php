<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Objective extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'target_amount',
        'period_type',
        'start_date',
        'end_date',
        'sector',
        'goal_type',
        'expense_categories',
        'use_standard_sources',
        'custom_users',
        'custom_categories',
        'is_active',
        'is_achieved',
        'is_confirmed'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'target_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_achieved' => 'boolean',
        'is_confirmed' => 'boolean',
        'use_standard_sources' => 'boolean',
        'expense_categories' => 'json',
        'custom_users' => 'json',
        'custom_categories' => 'json'
    ];

    /**
     * Get the user that owns the objective.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the expense categories associated with this objective.
     */
    public function expenseCategories()
    {
        if (!$this->expense_categories) {
            return collect();
        }
        
        return Category::whereIn('id', $this->expense_categories)->get();
    }

    /**
     * Get the custom users associated with this objective.
     */
    public function customUsers()
    {
        if (!$this->custom_users) {
            return collect();
        }
        
        return User::whereIn('id', $this->custom_users)->get();
    }

    /**
     * Get the custom categories associated with this objective.
     */
    public function customCategories()
    {
        if (!$this->custom_categories) {
            return collect();
        }
        
        return Category::whereIn('id', $this->custom_categories)->get();
    }

    /**
     * Get the progress records for this objective.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(ObjectiveProgress::class);
    }

    /**
     * Get the sub-objectives for this objective.
     */
    public function subObjectives(): HasMany
    {
        return $this->hasMany(SubObjective::class);
    }

    /**
     * Calculate the current progress percentage.
     */
    public function getCurrentProgressAttribute()
    {
        $latestProgress = $this->progress()->latest()->first();
        
        if (!$latestProgress) {
            return 0;
        }
        
        return $latestProgress->progress_percentage;
    }

    /**
     * Calculate the current amount collected.
     */
    public function getCurrentAmountAttribute()
    {
        $latestProgress = $this->progress()->latest()->first();
        
        if (!$latestProgress) {
            return 0;
        }
        
        return $latestProgress->current_amount;
    }

    /**
     * Calculate the remaining amount to reach the objective.
     */
    public function getRemainingAmountAttribute()
    {
        return max(0, $this->target_amount - $this->getCurrentAmountAttribute());
    }

    /**
     * Get the formatted target amount.
     */
    public function getFormattedTargetAmountAttribute()
    {
        return number_format($this->target_amount, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Get the formatted current amount.
     */
    public function getFormattedCurrentAmountAttribute()
    {
        return number_format($this->getCurrentAmountAttribute(), 0, ',', ' ') . ' FCFA';
    }

    /**
     * Get the formatted remaining amount.
     */
    public function getFormattedRemainingAmountAttribute()
    {
        return number_format($this->getRemainingAmountAttribute(), 0, ',', ' ') . ' FCFA';
    }

    /**
     * Get the period type in French.
     */
    public function getFormattedPeriodTypeAttribute()
    {
        switch ($this->period_type) {
            case 'daily':
                return 'Journalier';
            case 'weekly':
                return 'Hebdomadaire';
            case 'monthly':
                return 'Mensuel';
            case 'yearly':
                return 'Annuel';
            default:
                return $this->period_type;
        }
    }
    
    /**
     * Get the sector name in French.
     */
    public function getFormattedSectorAttribute()
    {
        switch ($this->sector) {
            case 'alimentation':
                return 'Alimentation';
            case 'boulangerie-patisserie':
                return 'Boulangerie-Pâtisserie';
            case 'glace':
                return 'Glaces';
            case 'global':
                return 'Global (Toute entreprise)';
            default:
                return $this->sector;
        }
    }
    
    /**
     * Get the description of standard sources for this sector.
     */
    public function getStandardSourcesDescriptionAttribute()
    {
        switch ($this->sector) {
            case 'alimentation':
                return 'Versements faits par les caissier(ère)s (rôle "caissiere")';
            case 'boulangerie-patisserie':
                return 'Versements faits par les chefs de production (rôle "chef_production") et vendeurs (secteur "vente")';
            case 'glace':
                return 'Versements faits par les responsables glace (rôle "glace")';
            case 'global':
                return 'Toutes les transactions de type "income" (entrée d\'argent)';
            default:
                return 'Sources par défaut';
        }
    }
    
    /**
     * Get the goal type in French.
     */
    public function getFormattedGoalTypeAttribute()
    {
        return $this->goal_type === 'revenue' ? 'Chiffre d\'affaires' : 'Bénéfice';
    }
    
    /**
     * Get the color class based on the sector.
     */
    public function getSectorColorAttribute()
    {
        switch ($this->sector) {
            case 'alimentation':
                return 'bg-blue-100 text-blue-800';
            case 'boulangerie-patisserie':
                return 'bg-yellow-100 text-yellow-800';
            case 'glace':
                return 'bg-purple-100 text-purple-800';
            case 'global':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
    
    /**
     * Get the progress color based on the percentage.
     */
    public function getProgressColorAttribute()
    {
        $progress = $this->current_progress;
        
        if ($progress >= 100) {
            return 'bg-green-500';
        } elseif ($progress >= 70) {
            return 'bg-blue-500';
        } elseif ($progress >= 40) {
            return 'bg-yellow-500';
        } else {
            return 'bg-red-500';
        }
    }
    
    /**
     * Get the total amount of all sub-objectives.
     */
    public function getTotalSubObjectivesAmountAttribute()
    {
        return $this->subObjectives()->sum('target_amount');
    }
    
    /**
     * Check if the total sub-objectives amount exceeds the objective amount.
     */
    public function getSubObjectivesExceedLimitAttribute()
    {
        return $this->getTotalSubObjectivesAmountAttribute() > $this->target_amount;
    }
    
    /**
     * Get the remaining amount that can be allocated to sub-objectives.
     */
    public function getSubObjectivesRemainingAllocationAttribute()
    {
        return max(0, $this->target_amount - $this->getTotalSubObjectivesAmountAttribute());
    }
    
    /**
     * Détecte une incohérence dans les ventes des sous-objectifs vs l'objectif principal
     * Une incohérence est définie comme une différence de plus de 1000 FCFA
     */
    public function getHasInconsistencyAttribute()
    {
        if ($this->sector !== 'boulangerie-patisserie' || !$this->subObjectives()->exists()) {
            return false;
        }
        
        $subObjectiveTotalSales = $this->subObjectives()->sum('current_amount');
        $mainObjectiveAmount = $this->getCurrentAmountAttribute();
        
        return abs($subObjectiveTotalSales - $mainObjectiveAmount) > 1000;
    }
    
    /**
     * Calcule le montant de l'incohérence
     */
    public function getInconsistencyAmountAttribute()
    {
        if (!$this->getHasInconsistencyAttribute()) {
            return 0;
        }
        
        $subObjectiveTotalSales = $this->subObjectives()->sum('current_amount');
        $mainObjectiveAmount = $this->getCurrentAmountAttribute();
        
        return abs($subObjectiveTotalSales - $mainObjectiveAmount);
    }
    
    /**
     * Obtient le montant formaté de l'incohérence
     */
    public function getFormattedInconsistencyAmountAttribute()
    {
        return number_format($this->getInconsistencyAmountAttribute(), 0, ',', ' ') . ' FCFA';
    }
}
