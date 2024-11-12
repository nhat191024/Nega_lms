<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->boolean('status')->default(1); // Thêm cột status với kiểu boolean và giá trị mặc định là 1 (active)
        });
    }

    public function down()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

};
