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
        // 1. Fix seminars table - make instructor fields nullable
        Schema::table('seminars', function (Blueprint $table) {
            // Add collaborator_id if not exists
            if (!Schema::hasColumn('seminars', 'collaborator_id')) {
                $table->foreignId('collaborator_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
            }
            
            // Make instructor_name and instructor_bio nullable (they're overrides now)
            $table->string('instructor_name')->nullable()->change();
            $table->text('instructor_bio')->nullable()->change();
        });

        // 2. Fix digital_products table - add product_id to seminars instead
        // Seminar belongs to DigitalProduct, not the other way around
        Schema::table('seminars', function (Blueprint $table) {
            if (!Schema::hasColumn('seminars', 'product_id')) {
                $table->foreignId('product_id')->nullable()->after('collaborator_id')->constrained('digital_products')->onDelete('cascade');
            }
        });

        // 3. Remove seminar_id from digital_products if exists (wrong direction)
        if (Schema::hasColumn('digital_products', 'seminar_id')) {
            Schema::table('digital_products', function (Blueprint $table) {
                $table->dropForeign(['seminar_id']);
                $table->dropColumn('seminar_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('seminars', function (Blueprint $table) {
            $table->dropForeign(['collaborator_id']);
            $table->dropColumn('collaborator_id');
            
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
            
            $table->string('instructor_name')->nullable(false)->change();
            $table->text('instructor_bio')->nullable(false)->change();
        });
    }
};