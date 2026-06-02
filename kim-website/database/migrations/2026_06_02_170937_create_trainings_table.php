<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // ========================================
        // 1. TABEL PELATIHAN
        // ========================================
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location'); // tempat pelaksanaan
            $table->date('training_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('trainer_name')->nullable(); // nama pemateri/fasilitator
            $table->string('organizer')->nullable(); // penyelenggara
            $table->string('thumbnail')->nullable();

            // Link ke seminar (untuk pre/post test & sertifikat)
            $table->foreignId('seminar_id')->nullable()->constrained('seminars')->onDelete('set null');

            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // ========================================
        // 2. TABEL PESERTA PELATIHAN
        // ========================================
        Schema::create('training_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->onDelete('cascade');

            // Data peserta
            $table->string('name');
            $table->string('nip')->nullable(); // NIP/NIKKI
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('institution')->nullable(); // asal instansi/sekolah

            // Token akses (dikirim via email, tanpa login)
            $table->string('access_token')->unique();
            $table->boolean('token_sent')->default(false);
            $table->timestamp('token_sent_at')->nullable();

            // Absensi
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();

            // Progress pre/post test (link ke seminar_enrollment)
            $table->foreignId('seminar_enrollment_id')->nullable()->constrained('seminar_enrollments')->onDelete('set null');

            // Sertifikat
            $table->string('certificate_number')->nullable()->unique();
            $table->string('certificate_path')->nullable();
            $table->timestamp('certificate_issued_at')->nullable();

            $table->timestamps();

            $table->unique(['training_id', 'email']);
        });

        // ========================================
        // 3. TABEL PENGUMPULAN TUGAS
        // ========================================
        Schema::create('training_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('training_participants')->onDelete('cascade');

            // Tugas berupa link Google Drive
            $table->string('drive_link');
            $table->text('notes')->nullable(); // catatan dari peserta

            // Review oleh admin
            $table->enum('status', ['submitted', 'reviewed', 'approved', 'revision'])->default('submitted');
            $table->text('feedback')->nullable(); // feedback dari admin
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('training_submissions');
        Schema::dropIfExists('training_participants');
        Schema::dropIfExists('trainings');
    }
};