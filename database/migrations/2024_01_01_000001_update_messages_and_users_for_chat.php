<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMessagesAndUsersForChat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update messages table
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('sender_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('receiver_id')->nullable()->after('sender_id');
            $table->string('thread_id', 255)->nullable()->after('receiver_id');
            $table->unsignedBigInteger('parent_message_id')->nullable()->after('thread_id');
            $table->enum('message_type', ['chat', 'system'])->default('system')->after('parent_message_id');
            $table->unsignedBigInteger('job_id')->nullable()->after('message_type');
            
            $table->index('thread_id');
            $table->index(['sender_id', 'receiver_id']);
            $table->index('job_id');
        });

        // Update users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('expo_push_token', 255)->nullable()->after('email');
            $table->string('device_id', 255)->nullable()->after('expo_push_token');
            $table->tinyInteger('notification_enabled')->default(1)->after('device_id');
            
            $table->index('expo_push_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['job_id']);
            $table->dropIndex(['sender_id', 'receiver_id']);
            $table->dropIndex(['thread_id']);
            $table->dropColumn(['sender_id', 'receiver_id', 'thread_id', 'parent_message_id', 'message_type', 'job_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['expo_push_token']);
            $table->dropColumn(['expo_push_token', 'device_id', 'notification_enabled']);
        });
    }
}

