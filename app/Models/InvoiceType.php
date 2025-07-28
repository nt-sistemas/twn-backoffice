<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceType extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'invoice_type_id');
    }
}
