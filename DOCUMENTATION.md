# 📚 FullTimez.com Documentation

## 🚀 Quick Start Guide

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/SQLite
- Git

### Installation Steps
```bash
# Clone repository
git clone git@github.com:YOUR_USERNAME/fulltimez-job-portal.git
cd fulltimez-job-portal

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Build assets
npm run build

# Start server
php artisan serve
```

## 🔐 Default Login Credentials

### Admin
- **Email:** info@fulltimez.com
- **Password:** password

### Employers
- **Email:** hr@techsolutions.com
- **Password:** password

### Job Seekers
- **Email:** ahmed.hassan@example.com
- **Password:** password

## 🏗️ Project Structure

```
app/
├── Http/Controllers/
│   ├── Admin/          # Admin controllers
│   ├── Employer/       # Employer controllers
│   ├── Auth/           # Authentication controllers
│   └── [Other controllers]
├── Models/             # Eloquent models
├── Notifications/      # Notification classes
└── Middleware/         # Custom middleware

database/
├── migrations/         # Database migrations
└── seeders/           # Database seeders

resources/views/
├── admin/             # Admin views
├── employer/          # Employer views
├── auth/              # Authentication views
└── [Other views]

public/
├── css/               # CSS files
├── js/                # JavaScript files
└── images/            # Images and assets
```

## 🔧 Configuration

### Environment Variables
```env
APP_NAME="FullTimez"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password

# Stripe
STRIPE_KEY=your-stripe-public-key
STRIPE_SECRET=your-stripe-secret-key
```

## 🎯 Features Overview

### User Roles
- **Admin:** Full system control
- **Employer:** Job posting and management
- **Job Seeker:** Job search and applications

### Job Management
- Featured jobs (paid)
- Recommended jobs (free)
- Advanced search and filtering
- Application tracking

### Payment System
- Stripe integration
- Payment verification
- Package management

### Security
- Role-based access control
- Email verification
- CSRF protection
- Failed login tracking

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production`
2. Set `APP_DEBUG=false`
3. Configure database credentials
4. Run `php artisan config:cache`
5. Set proper file permissions
6. Configure web server

### Hosting Requirements
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js
- Web server (Apache/Nginx)

## 📞 Support

For technical support or questions:
- **Email:** info@fulltimez.com
- **Documentation:** [GitHub Wiki](wiki/)
- **Issues:** [GitHub Issues](issues/)

## 📄 License

Proprietary software. All rights reserved.
