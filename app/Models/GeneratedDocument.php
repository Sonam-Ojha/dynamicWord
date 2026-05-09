<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_id',
        'branch_id',
        'template_id',
        'document_number',
        'form_data',
        'generated_file',
        'status',
    ];

    protected $casts = [
        'form_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(BankBranch::class, 'branch_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where('document_number', 'like', "%{$term}%");
    }
}
