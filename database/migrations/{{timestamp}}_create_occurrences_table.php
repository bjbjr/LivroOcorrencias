<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('occurrences', function (Blueprint $table) {
            $table->id(); // Ticket único
            $table->foreignId('user_id')->constrained('users')->comment('User who registered the occurrence');
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('service_point_id')->constrained('service_points');
            $table->string('title')->nullable()->comment('Title might be generated or optional based on questions');
            $table->text('description')->nullable()->comment('General description if not covered by questions');
            $table->string('occurrence_type')->nullable()->comment('Categorization of the occurrence');
            $table->string('status')->default('open')->comment('e.g., open, in_progress, resolved, closed');
            $table->string('internal_reporter_name')->nullable()->comment('Name of the person involved/reporting, if different from user');
            $table->json('involved_parties')->nullable()->comment('JSON array of involved people details');
            $table->timestamp('occurred_at')->useCurrent()->comment('Timestamp of when the event happened, can be set by user');
            $table->timestamps(); // created_at will be when it was registered in the system
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occurrences');
    }
};
