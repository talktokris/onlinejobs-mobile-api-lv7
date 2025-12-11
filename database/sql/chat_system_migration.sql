-- Chat System Database Migration SQL
-- Run this SQL directly if migrations are not used

-- First, fix created_at column if it has invalid default (common MySQL issue)
ALTER TABLE messages 
MODIFY COLUMN created_at TIMESTAMP NULL DEFAULT NULL,
MODIFY COLUMN updated_at TIMESTAMP NULL DEFAULT NULL;

-- Update messages table - Add new columns one by one to avoid conflicts
ALTER TABLE messages 
ADD COLUMN sender_id INT UNSIGNED NULL AFTER user_id;

ALTER TABLE messages 
ADD COLUMN receiver_id INT UNSIGNED NULL AFTER sender_id;

ALTER TABLE messages 
ADD COLUMN thread_id VARCHAR(255) NULL AFTER receiver_id;

ALTER TABLE messages 
ADD COLUMN parent_message_id INT UNSIGNED NULL AFTER thread_id;

ALTER TABLE messages 
ADD COLUMN message_type ENUM('chat', 'system') DEFAULT 'system' AFTER parent_message_id;

ALTER TABLE messages 
ADD COLUMN job_id INT UNSIGNED NULL AFTER message_type;

-- Add indexes
ALTER TABLE messages 
ADD INDEX idx_thread_id (thread_id);

ALTER TABLE messages 
ADD INDEX idx_sender_receiver (sender_id, receiver_id);

ALTER TABLE messages 
ADD INDEX idx_job_id (job_id);

-- Update users table
ALTER TABLE users
ADD COLUMN expo_push_token VARCHAR(255) NULL AFTER email;

ALTER TABLE users
ADD COLUMN device_id VARCHAR(255) NULL AFTER expo_push_token;

ALTER TABLE users
ADD COLUMN notification_enabled TINYINT(1) DEFAULT 1 AFTER device_id;

ALTER TABLE users
ADD INDEX idx_expo_token (expo_push_token);

