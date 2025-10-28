<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'duration_days',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Scope for active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured packages
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for ordering by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        $currency = $this->currency ?? 'USD';
        $symbol = $this->getCurrencySymbol($currency);
        return $symbol . number_format($this->price, 2);
    }

    /**
     * Get currency symbol
     */
    private function getCurrencySymbol($currency)
    {
        $symbols = [
            'USD' => '$',
            'AED' => 'د.إ',
            'SAR' => 'ر.س',
            'QAR' => 'ر.ق',
            'KWD' => 'د.ك',
            'BHD' => 'د.ب',
            'OMR' => 'ر.ع.',
            'EUR' => '€',
            'GBP' => '£',
            'INR' => '₹',
            'PKR' => '₨',
            'EGP' => 'ج.م',
        ];
        
        return $symbols[$currency] ?? '$';
    }

    /**
     * Get duration in readable format
     */
    public function getDurationTextAttribute()
    {
        if ($this->duration_days == 1) {
            return '1 Day';
        } elseif ($this->duration_days < 30) {
            return $this->duration_days . ' Days';
        } elseif ($this->duration_days == 30) {
            return '1 Month';
        } elseif ($this->duration_days < 365) {
            $months = round($this->duration_days / 30);
            return $months . ' Month' . ($months > 1 ? 's' : '');
        } else {
            $years = round($this->duration_days / 365);
            return $years . ' Year' . ($years > 1 ? 's' : '');
        }
    }

}
