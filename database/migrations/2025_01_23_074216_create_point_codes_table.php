<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the point_codes table for storing point redemption codes.
 */
return new class extends Migration
{
    /**
     * Run the migrations to create the 'point_codes' table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_codes', function (Blueprint $table) {
            $table->id();

            // Ownership: who created the code (staff)
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();

            // Link to a specific card
            $table->foreignId('card_id')
                  ->nullable()
                  ->constrained('cards')
                  ->cascadeOnDelete();

            // The 4-digit code itself
            $table->string('code', 4)->unique();

            // How many points this code grants per redemption
            $table->unsignedInteger('points');

            // Is the code active or not
            $table->boolean('is_active')->default(true);

            // Maximum total times this code can be used
            // (e.g., if set to 10, it can be redeemed by any members combined up to 10 times before expiring)
            // If you only want "unlimited" uses, you can store null or a very high default. 
            $table->unsignedInteger('max_uses')->default(1);

            // How many times the code has already been redeemed so far (global count)
            $table->unsignedInteger('times_redeemed')->default(0);

            // Maximum times a single member can redeem this code
            // (1 means once per member, 2 means each member can use it twice, etc.)
            $table->unsignedInteger('max_uses_per_member')->default(1);

            // When the code expires
            $table->timestamp('expires_at')->nullable();

            // Used by member (for single use)
            $table->foreignId('used_by')->nullable()->constrained('members')->nullOnDelete();
            $table->timestamp('used_at')->nullable();

            // Audit columns: who created/updated the record, plus timestamps
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('staff')
                  ->cascadeOnDelete()
                  ->index()
                  ->name('fk_point_codes_created_by');

            // If you want a deleted_by, you can add it; for now just an updated_by:
            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('staff')
                  ->nullOnDelete()
                  ->index()
                  ->name('fk_point_codes_updated_by');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to drop the 'point_codes' table.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('point_codes', function (Blueprint $table) {
            // Clean up foreign keys
            $table->dropForeign('fk_point_codes_created_by');
            $table->dropForeign('fk_point_codes_updated_by');
            $table->dropForeign(['staff_id']);
            $table->dropForeign(['card_id']);
            $table->dropForeign(['used_by']);
        });

        Schema::dropIfExists('point_codes');
    }
};
