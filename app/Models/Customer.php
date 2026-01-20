<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'document',
        'title',
        'registration',
        'email',
        'implementation_fee',
        'monthly_fee',
        'observation',
        'phone',
        'address',
        'number',
        'complement',
        'city',
        'neighborhood',
        'state',
        'postal_code',
        'notes',
        'status',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function transmission()
    {
        return $this->hasOne(Transmission::class);
    }
}
