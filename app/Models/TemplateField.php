<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateField extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'field_name',
        'label',
        'field_type',
        'placeholder',
        'default_value',
        'options',
        'validation_rules',
        'is_required',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public static function fieldTypes(): array
    {
        return [
            'text' => 'Text',
            'textarea' => 'Textarea',
            'select' => 'Select',
            'checkbox' => 'Checkbox',
            'radio' => 'Radio',
            'date' => 'Date',
            'image' => 'Image',
            'signature' => 'Signature',
            'number' => 'Number',
            'email' => 'Email',
        ];
    }
}
