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
        Schema::table('transmissions', function (Blueprint $table) {
            $table->date('transmission_date')->nullable()->after('status');
            $table->string('response_code')->nullable()->after('transmission_date');
            $table->text('response_message')->nullable()->after('response_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transmissions', function (Blueprint $table) {
            $table->dropColumn(['transmission_date', 'response_code', 'response_message']);
        });
    }
};
