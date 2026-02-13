<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transmission extends Model
{
    /** @use HasFactory<\Database\Factories\TransmissionFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'customer_id',
        'amount',
        'status',
        'transmission_date',
        'response_code',
        'response_message',
        'attempts',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => 'string',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
