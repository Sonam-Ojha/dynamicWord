<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function templates(): HasMany
    {
        return $this->hasMany(Template::class, 'category_id');
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

        return $query->where('category_name', 'like', "%{$term}%");
    }
}
