<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserResponse extends Model
{
    use HasFactory;

    /**
     * Nom de la table dans la base de données
     */
    protected $table = 'user_responses';

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'session_id',
        'question_id',
        'response_value',
        'response_score',
        'answered_at',
    ];

    /**
     * Cast automatique des types de données
     */
    protected $casts = [
        'session_id' => 'integer',
        'question_id' => 'integer',
        'response_score' => 'integer',
        'answered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * RELATIONS
     */

    /**
     * Relation Many-to-One avec UserSession
     * Une réponse appartient à une session
     */
    public function userSession()
    {
        return $this->belongsTo(UserSession::class, 'session_id');
    }

    /**
     * Relation Many-to-One avec Question
     * Une réponse appartient à une question
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * SCOPES
     */

    /**
     * Scope pour filtrer par session
     */
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope pour filtrer par question
     */
    public function scopeByQuestion($query, $questionId)
    {
        return $query->where('question_id', $questionId);
    }

    /**
     * Scope pour ordonner par date de réponse
     */
    public function scopeOrderedByAnswered($query)
    {
        return $query->orderBy('answered_at');
    }
}
