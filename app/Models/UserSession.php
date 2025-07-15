<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    /**
     * Nom de la table dans la base de données
     */
    protected $table = 'user_sessions';

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'session_token',
        'current_step',
        'started_at',
        'expires_at',
        'ip_address',
        'user_agent',
    ];

    /**
     * Cast automatique des types de données
     */
    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * RELATIONS
     */

    /**
     * Relation One-to-Many avec UserResponse
     * Une session peut avoir plusieurs réponses
     */
    public function userResponses()
    {
        return $this->hasMany(UserResponse::class, 'session_id');
    }

    /**
     * Relation One-to-Many avec Assessment
     * Une session peut générer plusieurs évaluations
     */
    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'session_id');
    }

    /**
     * SCOPES
     */

    /**
     * Scope pour les sessions actives (non expirées)
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope pour les sessions expirées
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * MÉTHODES UTILITAIRES
     */

    /**
     * Vérifie si la session est expirée
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Prolonge la session
     */
    public function extend($hours = 24)
    {
        $this->expires_at = now()->addHours($hours);
        $this->save();
    }
}
