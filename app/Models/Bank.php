<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'bank_code',
        'logo',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function templates(): HasMany
    {
        return $this->hasMany(Template::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(BankBranch::class);
    }

    public function activeBranches(): HasMany
    {
        return $this->hasMany(BankBranch::class)->where('status', true);
    }

    public function generatedDocuments(): HasMany
    {
        return $this->hasMany(GeneratedDocument::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('bank_name', 'like', "%{$term}%")
              ->orWhere('bank_code', 'like', "%{$term}%");
        });
    }
}
