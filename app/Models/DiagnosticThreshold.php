<?php

// app/Models/DiagnosticThreshold.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiagnosticThreshold extends Model
{
    use HasFactory;

    protected $fillable = [
        'skin_characteristic_id',
        'min_score',
        'confidence_threshold',
        'zone_requirements',
        'priority_level',
    ];

    protected $casts = [
        'min_score' => 'integer',
        'confidence_threshold' => 'decimal:3',
        'zone_requirements' => 'array',
        'priority_level' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function skinCharacteristic(): BelongsTo
    {
        return $this->belongsTo(SkinCharacteristic::class);
    }

    // Méthodes utilitaires
    public function meetsThreshold(int $score, ?float $confidence = null): bool
    {
        $meetsScore = $score >= $this->min_score;

        if ($confidence !== null) {
            return $meetsScore && $confidence >= $this->confidence_threshold;
        }

        return $meetsScore;
    }

    public function checkZoneRequirements(array $detectedZones): bool
    {
        if (empty($this->zone_requirements)) {
            return true;
        }

        // Vérifier si au moins une zone requise est présente
        return !empty(array_intersect($this->zone_requirements, $detectedZones));
    }
}
