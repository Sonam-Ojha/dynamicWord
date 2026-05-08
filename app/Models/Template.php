<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'category_id',
        'template_name',
        'template_code',
        'template_preview',
        'html_content',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TemplateCategory::class, 'category_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(TemplateField::class)->orderBy('sort_order');
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
            $q->where('template_name', 'like', "%{$term}%")
              ->orWhere('template_code', 'like', "%{$term}%");
        });
    }
}
