# 🚀 FullTimez.com - Job Portal Application

A complete Laravel-based job portal application designed for the UAE market with advanced features including role-based authentication, payment processing, document verification, and comprehensive job management.

## 📋 Table of Contents

- [Features](#-features)
- [Technology Stack](#-technology-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [User Roles](#-user-roles)
- [Database Structure](#-database-structure)
- [API Documentation](#-api-documentation)
- [Deployment](#-deployment)
- [Contributing](#-contributing)
- [License](#-license)

## ✨ Features

### 🔐 Authentication & Security
- **Role-based Access Control** (Admin, Employer, Job Seeker)
- **Email Verification** required for all users
- **Password Reset** functionality
- **Failed Login Attempt** tracking
- **CSRF Protection** and security middleware
- **Document Verification** system

### 💼 Job Management
- **Advanced Job Search** with filters
- **Featured Jobs** (7, 15, 30 days) with Stripe payment
- **Recommended Jobs** (Free 7-day listings)
- **Job Categories** and location-based search
- **Application Tracking** system
- **Admin Approval** workflow

### 💰 Payment System
- **Stripe Integration** for featured job payments
- **Payment Verification** workflow
- **Package Management** system
- **Admin Payment Approval**

### 🔔 Notification System
- **Real-time Notifications** for all user actions
- **Email Notifications** for important events
- **Dashboard Alerts** for pending actions
- **Comprehensive Notification Types**

### 📊 Admin Dashboard
- **User Management** (approve/reject users)
- **Job Approval** workflow
- **Document Verification** system
- **Payment Verification** management
- **Analytics Dashboard** with statistics
- **Package Management**

## 🛠 Technology Stack

- **Backend:** Laravel 12.x (PHP 8.2+)
- **Frontend:** Blade templates, Bootstrap 5, jQuery
- **Database:** SQLite (dev) / MySQL (production)
- **Payment:** Stripe API integration
- **PDF Generation:** DomPDF
- **Build Tools:** Vite with Tailwind CSS 4.0

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL/SQLite
- Git

### Step 1: Clone Repository
```bash
git clone git@github.com:YOUR_USERNAME/fulltimez-job-portal.git
cd fulltimez-job-portal
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 3: Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Configuration
```bash
# For SQLite (Development)
touch database/database.sqlite

# For MySQL (Production)
# Update .env file with your database credentials
```

### Step 5: Run Migrations & Seeders
```bash
# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

### Step 6: Storage Setup
```bash
# Create storage link
php artisan storage:link
```

### Step 7: Build Assets
```bash
# Build frontend assets
npm run build
```

### Step 8: Start Development Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## ⚙️ Configuration

### Environment Variables
```env
APP_NAME="FullTimez"
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls

# Stripe Configuration
STRIPE_KEY=your-stripe-public-key
STRIPE_SECRET=your-stripe-secret-key
STRIPE_WEBHOOK_SECRET=your-webhook-secret
```

## 👥 User Roles

### 🔧 Admin
- **Full system access**
- User management (approve/reject)
- Job approval workflow
- Document verification
- Payment verification
- Package management
- Analytics dashboard

### 🏢 Employer
- **Company profile management**
- Job posting (Recommended/Featured)
- Document verification required
- Payment processing
- Application management
- Candidate search

### 👨‍💼 Job Seeker
- **Profile creation** with CV upload
- Job search and application
- Application tracking
- Education/experience records
- Certificate management

## 🗄️ Database Structure

### Core Tables
- `users` - User accounts with role-based access
- `roles` - Admin, Employer, Seeker roles
- `seeker_profiles` - Job seeker information
- `employer_profiles` - Company information
- `job_postings` - Job listings with premium features
- `job_categories` - Job categorization
- `job_applications` - Application tracking
- `education_records` - Educational background
- `experience_records` - Work experience
- `certificates` - Professional certificates
- `employer_documents` - Document verification
- `payment_verifications` - Payment tracking
- `packages` - Pricing packages
- `notifications` - System notifications

## 🔑 Default Login Credentials

### Admin Account
- **Email:** info@fulltimez.com
- **Password:** password

### Employer Accounts
- **Email:** hr@techsolutions.com
- **Password:** password
- **Company:** Tech Solutions LLC

### Job Seeker Accounts
- **Email:** ahmed.hassan@example.com
- **Password:** password
- **Name:** Ahmed Hassan

*All accounts use password: `password` for testing*

## 📊 Sample Data

The application comes with comprehensive sample data:
- **1 Admin** user
- **5 Employer** companies with complete profiles
- **10 Job Seekers** with detailed profiles
- **12+ Job Postings** across various categories
- **14 Job Categories** covering all industries

## 🚀 Deployment

### Production Deployment Steps

1. **Set Environment Variables**
```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
# Add your production database credentials
```

2. **Optimize Application**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Set File Permissions**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

4. **Configure Web Server**
- Set document root to `/public` directory
- Configure Apache/Nginx virtual host
- Enable HTTPS with SSL certificate

## 🔒 Security Features

- **Password Hashing** with bcrypt
- **CSRF Protection** on all forms
- **Role-based Access Control**
- **File Upload Validation**
- **SQL Injection Protection** (Eloquent ORM)
- **XSS Protection** (Blade templating)
- **Failed Login Attempt** tracking
- **Email Verification** required

## 📱 API Endpoints

### Public Routes
- `GET /` - Homepage
- `GET /jobs` - Browse jobs
- `GET /jobs/{slug}` - View job details
- `GET /jobs/search` - Search jobs

### Authentication Routes
- `GET /jobseeker/login` - Jobseeker login
- `POST /jobseeker/login` - Process jobseeker login
- `GET /employer/login` - Employer login
- `POST /employer/login` - Process employer login

### Protected Routes
- `GET /dashboard` - User dashboard
- `GET /profile` - User profile
- `POST /jobs/{job}/apply` - Apply for job

## 🛠 Development

### Code Style
- Follow PSR-12 coding standards
- Use Laravel conventions
- Write comprehensive tests
- Document all methods

### Testing
```bash
# Run tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## 📈 Business Model

### Revenue Streams
1. **Featured Job Listings:** AED 49-149 per listing
2. **Premium Packages:** Extended visibility
3. **Document Verification:** Processing fees
4. **Additional Services:** CV services, etc.

### Pricing Structure
- **Featured 7 days:** AED 49
- **Featured 15 days:** AED 89
- **Featured 30 days:** AED 149

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is proprietary software. All rights reserved.

## 📞 Support

For support and questions:
- **Email:** info@fulltimez.com
- **Documentation:** [Full Documentation](docs/)
- **Issues:** [GitHub Issues](https://github.com/YOUR_USERNAME/fulltimez-job-portal/issues)

## 🎯 Roadmap

### Upcoming Features
- [ ] Mobile app (React Native)
- [ ] Advanced analytics dashboard
- [ ] Multi-language support
- [ ] Video interview integration
- [ ] AI-powered job matching
- [ ] Social media integration

---

**Built with ❤️ for the UAE job market**

*Last updated: January 2025*