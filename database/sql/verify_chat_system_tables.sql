-- Verification SQL for Chat System Tables
-- Run this to verify all fields are correctly added
-- IMPORTANT: Make sure you've selected the correct database before running this script

-- Step 1: Check current database
SELECT DATABASE() as current_database;

-- Step 2: Check if tables exist
SELECT 
    TABLE_NAME,
    TABLE_TYPE
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME IN ('messages', 'users')
ORDER BY TABLE_NAME;

-- Step 3: Check messages table structure (if table exists)
SHOW TABLES LIKE 'messages';

-- If messages table exists, show its structure
SHOW CREATE TABLE messages;

-- Step 4: Check for new columns in messages table
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_KEY,
    EXTRA
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'messages'
  AND COLUMN_NAME IN ('sender_id', 'receiver_id', 'thread_id', 'parent_message_id', 'message_type', 'job_id')
ORDER BY ORDINAL_POSITION;

-- Step 5: Check for indexes in messages table
SHOW INDEXES FROM messages WHERE Key_name IN ('idx_thread_id', 'idx_sender_receiver', 'idx_job_id');

-- Step 6: Check users table structure
SHOW TABLES LIKE 'users';

-- If users table exists, show its structure
SHOW CREATE TABLE users;

-- Step 7: Check for new columns in users table
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_KEY,
    EXTRA
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'users'
  AND COLUMN_NAME IN ('expo_push_token', 'device_id', 'notification_enabled')
ORDER BY ORDINAL_POSITION;

-- Step 8: Check for index in users table
SHOW INDEXES FROM users WHERE Key_name = 'idx_expo_token';

-- Step 9: Summary query - Count columns to verify
SELECT 
    'messages' as table_name,
    COUNT(*) as total_columns,
    SUM(CASE WHEN COLUMN_NAME IN ('sender_id', 'receiver_id', 'thread_id', 'parent_message_id', 'message_type', 'job_id') THEN 1 ELSE 0 END) as chat_columns_count,
    CASE 
        WHEN SUM(CASE WHEN COLUMN_NAME IN ('sender_id', 'receiver_id', 'thread_id', 'parent_message_id', 'message_type', 'job_id') THEN 1 ELSE 0 END) = 6 
        THEN '✓ All 6 chat columns present' 
        ELSE CONCAT('✗ Missing columns. Found: ', SUM(CASE WHEN COLUMN_NAME IN ('sender_id', 'receiver_id', 'thread_id', 'parent_message_id', 'message_type', 'job_id') THEN 1 ELSE 0 END), '/6')
    END as status
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'messages'

UNION ALL

SELECT 
    'users' as table_name,
    COUNT(*) as total_columns,
    SUM(CASE WHEN COLUMN_NAME IN ('expo_push_token', 'device_id', 'notification_enabled') THEN 1 ELSE 0 END) as notification_columns_count,
    CASE 
        WHEN SUM(CASE WHEN COLUMN_NAME IN ('expo_push_token', 'device_id', 'notification_enabled') THEN 1 ELSE 0 END) = 3 
        THEN '✓ All 3 notification columns present' 
        ELSE CONCAT('✗ Missing columns. Found: ', SUM(CASE WHEN COLUMN_NAME IN ('expo_push_token', 'device_id', 'notification_enabled') THEN 1 ELSE 0 END), '/3')
    END as status
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'users';

