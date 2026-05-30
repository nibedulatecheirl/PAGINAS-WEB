<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin','secretaria','docente','contador','estudiante') NOT NULL DEFAULT 'secretaria'");
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'personal_id')) {
                $table->foreignId('personal_id')->nullable()->after('role')->constrained('personal')->nullOnDelete();
            }

            if (!Schema::hasColumn('users', 'alumno_id')) {
                $table->foreignId('alumno_id')->nullable()->after('personal_id')->constrained('alumnos')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'alumno_id')) {
                $table->dropConstrainedForeignId('alumno_id');
            }

            if (Schema::hasColumn('users', 'personal_id')) {
                $table->dropConstrainedForeignId('personal_id');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin','secretaria','docente','contador') NOT NULL DEFAULT 'secretaria'");
        }
    }
};
