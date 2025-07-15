<?php

// app/Models/SkinCharacteristic.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SkinCharacteristic extends Model
{
use HasFactory;

protected $fillable = [
'name',
'display_name',
'description',
'care_recommendations',
'severity_levels',
'is_active',
];

protected $casts = [
'severity_levels' => 'array',
'is_active' => 'boolean',
'created_at' => 'datetime',
'updated_at' => 'datetime',
];

// Relations
public function scoringRules(): HasMany
{
return $this->hasMany(ScoringRule::class);
}

public function diagnosticThresholds(): HasMany
{
return $this->hasMany(DiagnosticThreshold::class);
}

public function assessments(): BelongsToMany
{
return $this->belongsToMany(Assessment::class, 'assessment_skin_characteristics')
->withPivot('severity_level', 'confidence_score')
->withTimestamps();
}
}

// app/Models/ScoringRule.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoringRule extends Model
{
use HasFactory;

protected $fillable = [
'question_id',
'skin_characteristic_id',
'zone_weight',
'score_jamais',
'score_rarement',
'score_parfois',
'score_souvent',
'score_toujours',
'is_active',
];

protected $casts = [
'zone_weight' => 'decimal:2',
'score_jamais' => 'integer',
'score_rarement' => 'integer',
'score_parfois' => 'integer',
'score_souvent' => 'integer',
'score_toujours' => 'integer',
'is_active' => 'boolean',
'created_at' => 'datetime',
'updated_at' => 'datetime',
];

// Relations
public function question(): BelongsTo
{
return $this->belongsTo(Question::class);
}

public function skinCharacteristic(): BelongsTo
{
return $this->belongsTo(SkinCharacteristic::class);
}

// Méthodes utilitaires
public function getScoreForResponse(string $responseValue): int
{
$scoreMap = [
'jamais' => $this->score_jamais,
'rarement' => $this->score_rarement,
'parfois' => $this->score_parfois,
'souvent' => $this->score_souvent,
'toujours' => $this->score_toujours,
];

return $scoreMap[$responseValue] ?? 0;
}
}

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
