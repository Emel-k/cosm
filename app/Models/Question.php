<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * Nom de la table dans la base de données
     */
    protected $table = 'questions';

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'category',
        'question_text',
        'question_order',
        'question_type',
        'is_active',
    ];

    /**
     * Cast automatique des types de données
     */
    protected $casts = [
        'question_order' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * RELATIONS
     */

    /**
     * Relation One-to-Many avec UserResponse
     * Une question peut avoir plusieurs réponses
     */
    public function userResponses()
    {
        return $this->hasMany(UserResponse::class, 'question_id');
    }

    /**
     * Relation One-to-Many avec ScoringRule
     * Une question peut avoir plusieurs règles de scoring
     */
    public function scoringRules()
    {
        return $this->hasMany(ScoringRule::class, 'question_id');
    }

    /**
     * SCOPES
     */

    /**
     * Scope pour les questions actives seulement
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope pour ordonner par ordre de question
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('question_order');
    }

    /**
     * Scope pour filtrer par type de question
     */
    public function scopeByType($query, $type)
    {
        return $query->where('question_type', $type);
    }
}
