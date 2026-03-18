<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('car_images', function (Blueprint $table) {
            $table->enum('disk', ['public'])->change();
        });
    }

    public function down(): void
    {
        //
    }
};
