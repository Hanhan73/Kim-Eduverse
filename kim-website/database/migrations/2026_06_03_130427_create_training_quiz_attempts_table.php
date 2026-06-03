<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('training_quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('training_participants')->onDelete('cascade');
            $table->enum('type', ['pre', 'post']);
            $table->json('question_order');
            $table->json('shuffled_options');
            $table->json('answers')->nullable();
            $table->decimal('score', 5, 2)->default(0);
            $table->boolean('is_passed')->default(false);
            $table->boolean('is_submitted')->default(false);
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::table('training_participants', function (Blueprint $table) {
            $table->boolean('pre_test_passed')->default(false)->after('checked_out_at');
            $table->decimal('pre_test_score', 5, 2)->nullable()->after('pre_test_passed');
            $table->boolean('material_viewed')->default(false)->after('pre_test_score');
            $table->boolean('post_test_passed')->default(false)->after('material_viewed');
            $table->decimal('post_test_score', 5, 2)->nullable()->after('post_test_passed');
        });
    }

    public function down()
    {
        Schema::table('training_participants', function (Blueprint $table) {
            $table->dropColumn(['pre_test_passed','pre_test_score','material_viewed','post_test_passed','post_test_score']);
        });
        Schema::dropIfExists('training_quiz_attempts');
    }
};