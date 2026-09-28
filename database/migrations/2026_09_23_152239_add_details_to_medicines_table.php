<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->string('composition')->nullable();
            $table->string('fabricant')->nullable();
            $table->string('type_consommation')->nullable();
            $table->string('date_expiration')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['composition', 'fabricant', 'type_consommation', 'date_expiration']);
        });
    }
};