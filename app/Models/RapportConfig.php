<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapportConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_categories',
        'alimentation_categories',
        'production_users',
        'alimentation_users',
        'social_climat',
        'major_problems',
        'recommendations',
        'tax_rate',
        'vat_rate',
        'analyze_product_performance',
        'analyze_waste',
        'analyze_sales_discrepancies',
        'analyze_employee_performance',
        'analyze_theft_detection',
        'analyze_material_usage',
        'analyze_spoilage',
        'analyze_objectives',
        'analyze_hr_data',
        'analyze_orders',
        'analyze_market_trends',
        'analyze_event_impact',
        'analyze_ice_cream_sector'
    ];

    protected $casts = [
        'production_categories' => 'json',
        'alimentation_categories' => 'json',
        'production_users' => 'json',
        'alimentation_users' => 'json',
        'social_climat' => 'json',
        'major_problems' => 'json',
        'recommendations' => 'json',
        'tax_rate' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'analyze_product_performance' => 'boolean',
        'analyze_waste' => 'boolean',
        'analyze_sales_discrepancies' => 'boolean',
        'analyze_employee_performance' => 'boolean',
        'analyze_theft_detection' => 'boolean',
        'analyze_material_usage' => 'boolean',
        'analyze_spoilage' => 'boolean',
        'analyze_objectives' => 'boolean',
        'analyze_hr_data' => 'boolean',
        'analyze_orders' => 'boolean',
        'analyze_market_trends' => 'boolean',
        'analyze_event_impact' => 'boolean',
        'analyze_ice_cream_sector' => 'boolean'
    ];
}
