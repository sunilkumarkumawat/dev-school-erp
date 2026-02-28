<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RefactorNotificationTokensForAttendanceUniqueId extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('notification_tokens')) {
            return;
        }

        Schema::table('notification_tokens', function (Blueprint $table) {
            if (!Schema::hasColumn('notification_tokens', 'attendance_unique_id')) {
                $table->string('attendance_unique_id', 100)->nullable()->after('session_id');
            }
            if (!Schema::hasColumn('notification_tokens', 'entity_type')) {
                $table->string('entity_type', 20)->nullable()->after('attendance_unique_id');
            }
        });

        DB::statement("UPDATE notification_tokens nt
            LEFT JOIN users u ON u.id = nt.user_id
            SET nt.attendance_unique_id = COALESCE(NULLIF(u.attendance_unique_id, ''), nt.attendance_unique_id),
                nt.entity_type = COALESCE(nt.entity_type, 'teacher')
            WHERE nt.user_id IS NOT NULL AND nt.user_id > 0");

        DB::statement("UPDATE notification_tokens nt
            LEFT JOIN admissions a ON a.id = nt.admission_id
            SET nt.attendance_unique_id = COALESCE(NULLIF(a.attendance_unique_id, ''), nt.attendance_unique_id),
                nt.entity_type = COALESCE(nt.entity_type, 'student')
            WHERE nt.admission_id IS NOT NULL AND nt.admission_id > 0");

        DB::statement("UPDATE notification_tokens
            SET entity_type = 'teacher'
            WHERE entity_type IS NULL OR TRIM(entity_type) = ''");

        if (Schema::hasColumn('notification_tokens', 'user_id')) {
            DB::statement('ALTER TABLE notification_tokens DROP COLUMN user_id');
        }

        if (Schema::hasColumn('notification_tokens', 'admission_id')) {
            DB::statement('ALTER TABLE notification_tokens DROP COLUMN admission_id');
        }

        DB::statement('CREATE INDEX notification_tokens_uid_entity_idx ON notification_tokens (attendance_unique_id, entity_type(10))');
    }

    public function down()
    {
        if (!Schema::hasTable('notification_tokens')) {
            return;
        }

        Schema::table('notification_tokens', function (Blueprint $table) {
            if (!Schema::hasColumn('notification_tokens', 'user_id')) {
                $table->integer('user_id')->default(0)->after('id');
            }
            if (!Schema::hasColumn('notification_tokens', 'admission_id')) {
                $table->integer('admission_id')->nullable()->after('session_id');
            }
        });

        if (Schema::hasColumn('notification_tokens', 'entity_type')) {
            DB::statement('ALTER TABLE notification_tokens DROP COLUMN entity_type');
        }

        if (Schema::hasColumn('notification_tokens', 'attendance_unique_id')) {
            DB::statement('ALTER TABLE notification_tokens DROP COLUMN attendance_unique_id');
        }
    }
}
