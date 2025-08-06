<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('address')->nullable()->after('phone');
            $table->string('number')->nullable()->after('address');
            $table->string('complement')->nullable()->after('number');
            $table->string('city')->nullable()->after('address');
            $table->string('neighborhood')->nullable()->after('city');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
            $table->text('notes')->nullable()->after('postal_code');
            $table->boolean('is_active')->default(true)->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'city', 'neighborhood', 'state', 'postal_code', 'notes', 'is_active']);
        });
    }
};
