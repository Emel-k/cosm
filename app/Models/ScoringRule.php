<?php

namespace App\Models;


use Illuminate\{Database\Eloquent\Factories\HasFactory, Database\Eloquent\Model, Database\Eloquent\Relations\BelongsTo};

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

public function calculateWeightedScore(string $responseValue): float
{
$baseScore = $this->getScoreForResponse($responseValue);
return $baseScore * $this->zone_weight;
}
}
