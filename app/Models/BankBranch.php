<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'branch_name',
        'branch_code',
        'ifsc_code',
        'address',
        'city',
        'state',
        'pincode',
        'phone',
        'email',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function generatedDocuments(): HasMany
    {
        return $this->hasMany(GeneratedDocument::class, 'branch_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeForBank($query, $bankId)
    {
        return $query->where('bank_id', $bankId);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('branch_name', 'like', "%{$term}%")
              ->orWhere('branch_code', 'like', "%{$term}%")
              ->orWhere('ifsc_code', 'like', "%{$term}%")
              ->orWhere('city', 'like', "%{$term}%");
        });
    }
}
