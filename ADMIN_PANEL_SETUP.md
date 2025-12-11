# Admin Panel Setup Guide

## Prerequisites

1. Laravel 7 application running
2. Database configured and migrated
3. Admin user with `role_id = 3` in the `users` table

## Setup Steps

### 1. Create Admin User

Create an admin user in the database with `role_id = 3`:

```sql
INSERT INTO users (name, email, password, role_id, status, created_at, updated_at) 
VALUES ('Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 1, NOW(), NOW());
```

**Note**: The password above is `password`. Change it after first login.

Or use Laravel Tinker:
```php
php artisan tinker
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@example.com';
$user->password = Hash::make('password');
$user->role_id = 3;
$user->status = 1;
$user->save();
```

### 2. Configure Email Settings

Add these to your `.env` file for password reset functionality:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="AP Online Jobs"
```

**For Gmail**: Use App Password (not regular password). Generate it from: https://myaccount.google.com/apppasswords

### 3. Access the Admin Panel

1. Navigate to: `http://your-domain.com/admin/login`
2. Login with admin credentials
3. You'll be redirected to the dashboard

## Features

### Dashboard
- Statistics cards (Job Seekers, Employers, Jobs, Applications, Messages, Active Users)
- Charts showing trends (User Registration, Job Postings, Applications)
- Recent activity tables

### Job Seekers Management
- Search and filter job seekers
- View details with bookmarks and applied jobs
- Edit and delete job seekers

### Employers Management
- Search and filter employers
- View employer details with all job ads
- View job details with applicants
- View applicant details with message chat history
- View resume bookmarks and applied users
- Edit and delete employers

### Jobs Management
- Search and filter job ads
- View job details with all applicants
- View applicant details with message chat history
- Link to employer profile
- Edit and delete jobs

### Settings (Dynamic Fills)
- Manage Countries
- Manage Genders
- Manage Languages
- Manage Religions
- Manage Marital Statuses
- Manage Positions (Options)
- CRUD operations for each category

### Profile Management
- View admin profile
- Edit profile information
- Change password

### Push Notifications
- Send blast notifications to:
  - All Employers
  - All Job Seekers
  - All Users
- Send individual notifications with user search
- Uses Expo Push Notification API

## Routes

All admin routes are prefixed with `/admin`:

- `/admin/login` - Login page
- `/admin/dashboard` - Dashboard
- `/admin/job-seekers` - Job Seekers list
- `/admin/employers` - Employers list
- `/admin/jobs` - Jobs list
- `/admin/settings` - Settings
- `/admin/notifications` - Send Notifications
- `/admin/profile` - Admin Profile

## Security

- All admin routes are protected by `auth` and `admin` middleware
- Only users with `role_id = 3` can access the admin panel
- CSRF protection on all forms
- Password hashing with bcrypt

## Troubleshooting

### Cannot Login
- Verify admin user has `role_id = 3`
- Check user status is `1` (active)
- Verify password is correctly hashed

### Password Reset Not Working
- Check email configuration in `.env`
- Verify `password_resets` table exists
- Check mail logs for errors

### Push Notifications Not Sending
- Verify users have `expo_push_token` in database
- Check Expo Push Notification service is accessible
- Review logs for errors

### Missing Data in Views
- Verify relationships in models are correct
- Check database has required data
- Review controller queries

## Notes

- The admin panel uses Tailwind CSS via CDN (no build step required)
- Chart.js is loaded via CDN for dashboard charts
- Alpine.js is used for interactive elements
- All views are responsive and mobile-friendly

