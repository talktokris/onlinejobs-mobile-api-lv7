-- Simple Verification SQL for Chat System Tables
-- Run this if you get errors with the main verification script
-- Make sure you've selected your database first: USE your_database_name;

-- Quick check: List all tables
SHOW TABLES;

-- Check if messages table exists and show structure
SHOW CREATE TABLE messages;

-- Check if users table exists and show structure  
SHOW CREATE TABLE users;

-- Simple column check for messages table
SHOW COLUMNS FROM messages LIKE 'sender_id';
SHOW COLUMNS FROM messages LIKE 'receiver_id';
SHOW COLUMNS FROM messages LIKE 'thread_id';
SHOW COLUMNS FROM messages LIKE 'parent_message_id';
SHOW COLUMNS FROM messages LIKE 'message_type';
SHOW COLUMNS FROM messages LIKE 'job_id';

-- Simple column check for users table
SHOW COLUMNS FROM users LIKE 'expo_push_token';
SHOW COLUMNS FROM users LIKE 'device_id';
SHOW COLUMNS FROM users LIKE 'notification_enabled';

-- Check indexes
SHOW INDEXES FROM messages;
SHOW INDEXES FROM users;

