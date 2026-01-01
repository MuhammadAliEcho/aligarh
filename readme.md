# 🎓 Aligarh - School Management System

[![Laravel](https://img.shields.io/badge/Laravel-8.75+-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-Proprietary-blue.svg)](LICENSE)

> **Complete Multi-Tenant Educational Institution Management Platform** | Student Information System (SIS) | School ERP Software

Aligarh is a comprehensive **school management system** built with Laravel 8, designed for educational institutions to manage students, teachers, academics, fees, examinations, and administrative operations. Features **multi-tenant SaaS architecture** allowing multiple schools to operate independently on a single installation.

🌐 **Live Demo**: [hashmanagement.com/aligarh](https://hashmanagement.com/aligarh)

---

## 📖 Overview

Modern **educational institution management software** that streamlines school administration through an integrated platform. Perfect for K-12 schools, colleges, coaching institutes, and educational organizations looking for a robust **student information system** with fee management, grade tracking, and academic operations.

### 🎯 Built For
- **Schools & Colleges** - Complete academic and administrative management
- **Developers** - Open-source Laravel platform for customization and deployment
- **Educational Institutions** - Multi-campus management with tenant isolation
- **EdTech Companies** - SaaS foundation for white-label school software

---

## ✨ Core Features

### 👨‍🎓 Student Management
- **Student enrollment** and admission processing (new/old students)
- Complete student profiles with academic history
- Session-based student tracking
- Student promotion and transfer management
- Guardian/parent information management
- Student ID card generation and printing

### 💰 Fee Management System
- Invoice generation and fee collection
- Multiple fee types (tuition, library, transport, etc.)
- Discount and scholarship management
- Payment tracking and receipts
- Fee collection reports and analytics
- Monthly/yearly fee structure management

### 📝 Examination & Results
- Exam schedule and timetable management
- **Grade management** and mark entry
- Result processing and grade calculation
- Exam transcripts and report cards (PDF)
- Performance analytics and reports
- Exam category configuration

### 👨‍🏫 Teacher & Staff Management
- Teacher profiles and assignments
- Employee records and attendance
- Leave management system
- Subject allocation and class assignments
- Staff performance tracking
- Payroll integration support

### 📅 Academic Operations
- **Academic session management** (yearly/semester)
- Class and section organization
- Subject and curriculum management
- Timetable and routine scheduling
- Attendance tracking (students & staff)
- Academic calendar and events

### 📚 Library Management
- Book inventory and cataloging
- Book issue and return tracking
- Library member management
- Fine calculation for late returns
- Library reports and analytics

### 🔐 Multi-Tenant Architecture
- **SaaS-ready** with complete tenant isolation
- Separate databases per institution
- Custom domain mapping for each school
- Tenant-specific configurations and branding
- Centralized system management

### 👥 User Management & Permissions
- Role-based access control (RBAC)
- Multiple user types (Admin, Teacher, Student, Employee, Guardian)
- Route-based permission system
- Customizable roles and permissions
- Session-based user context

---

## 🏗️ Technology Stack

| Component | Technology |
|-----------|------------|
| **Framework** | Laravel 8.75+ |
| **Language** | PHP 8.0+ |
| **Database** | MySQL (Multi-tenant) |
| **Multi-Tenancy** | Stancl/Tenancy 3.6 |
| **Authentication** | Laravel Passport (OAuth2) |
| **Permissions** | Spatie Laravel-Permission 6.20 |
| **PDF Generation** | DomPDF |
| **DataTables** | Yajra Laravel DataTables |
| **Frontend** | Blade Templates + Vue.js 2 + Bootstrap 3 |
| **API** | RESTful API with Passport tokens |

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7+ / MariaDB 10.3+
- Composer
- Node.js & NPM (for assets)

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/aligarh.git
cd aligarh
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database** (edit `.env`)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aligarh_landlord
DB_USERNAME=root
DB_PASSWORD=

CENTRAL_DOMAIN=aligarh.test
```

5. **Run migrations**
```bash
php artisan migrate --seed
```

6. **Create tenant**
```bash
php artisan tenants:create {tenant-id}
```

7. **Serve application**
```bash
php artisan serve
```

Visit `http://aligarh.test` to access the system.

---

## 📊 Core Modules

### Student Information System (SIS)
Complete student lifecycle management from admission to graduation, including enrollment, academics, attendance, and performance tracking.

### Fee & Invoice Management
Comprehensive billing system with invoice generation, payment collection, discount management, and financial reporting.

### Examination Management
End-to-end exam operations including scheduling, mark entry, grade calculation, result publishing, and transcript generation.

### Academic Session Control
Session-based data segregation allowing schools to manage multiple academic years with proper data isolation and archiving.

### HR & Staff Management
Teacher and employee administration with attendance tracking, leave management, and role assignments.

### Library System
Full library operations including book cataloging, circulation management, member tracking, and fine calculation.

---

## 🔐 Multi-Tenant Architecture

Aligarh uses **Stancl/Tenancy** for robust multi-tenancy:

- **Landlord Database**: Central system for tenant management and domain routing
- **Tenant Databases**: Isolated database per school/institution
- **Domain Mapping**: Each tenant can have custom domain (e.g., school1.yourdomain.com)
- **Data Isolation**: Complete separation ensures privacy and security
- **Shared Codebase**: Single application serves all tenants efficiently

Perfect for:
- **SaaS providers** offering school management to multiple institutions
- **Multi-campus** educational organizations
- **White-label** education software deployments

---

## 📸 Screenshots

*Screenshots and demo videos available at the [live demo](https://hashmanagement.com/aligarh)*

---

## 🛠️ Configuration

### Permissions & Roles
Configure role-based permissions in `config/permission.php`. The system uses route-based permissions where each route name maps to a permission.

### Academic Settings
Customize fee structures, exam categories, and academic parameters in respective config files:
- `config/feeses.php` - Fee types configuration
- `config/examcategories.php` - Exam category setup
- `config/systemInfo.php` - System-wide settings

### Tenant Customization
Each tenant can have custom printable templates in `resources/views/admin/printable/{tenant_id}/`

---

## 📚 Documentation

- [API Documentation](docs/AJAX_ROUTES_DOCUMENTATION.md)
- [Permission System](docs/PHASE_2_PERMISSION_DEPENDENCIES.md)
- [Swagger API Docs](docs/SWAGGER_DOCUMENTATION_STRUCTURE.md)

---

## 🔌 API Access

RESTful API with OAuth2 authentication (Laravel Passport):
- Student data management
- Fee and invoice operations
- Attendance tracking
- Examination results
- User authentication

API endpoints available at `/api/v1/*` (see API documentation)

---

## 🤝 Contributing

We welcome contributions from the community! Whether it's bug fixes, feature enhancements, or documentation improvements.

### How to Contribute
1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

Please ensure your code follows Laravel best practices and includes appropriate tests.

---

## 🐛 Bug Reports & Support

If you discover any security vulnerabilities or bugs, please create an issue in the GitHub repository or contact our support team.

For security-related issues, please email directly rather than using the public issue tracker.

---

## 📄 License

**Copyright © 2026 Aligarh School Management System**

This software is proprietary and confidential. The source code is available for viewing and educational purposes, but commercial use, modification, or distribution requires a commercial license.

### License Options:
- **Community Edition**: Free for educational institutions (non-commercial use)
- **Commercial License**: Required for SaaS providers, businesses, or commercial deployments
- **Enterprise License**: Includes support, customization, and deployment assistance

For licensing inquiries, please contact: [your-licensing-email@domain.com]

---

## 🙏 Acknowledgments

Built with:
- [Laravel Framework](https://laravel.com) - The PHP framework for web artisans
- [Stancl/Tenancy](https://tenancyforlaravel.com) - Multi-tenancy package
- [Spatie Permission](https://spatie.be/docs/laravel-permission) - Role and permission management

---

## 📞 Contact & Demo

🌐 **Live Demo**: [hashmanagement.com/aligarh](https://hashmanagement.com/aligarh)  
📧 **Support**: support@hashmanagement.com    
🐙 **Issues**: [GitHub Issues](https://github.com/AIAndME-coder/aligarh/issues)

---

**Keywords**: school management system, student information system, education ERP, Laravel school software, multi-tenant education platform, academic management system, student enrollment system, fee management software, examination management, attendance tracking, grade management, open source school administration, educational institution software, school ERP, SIS system, learning management, student database, school administration software
