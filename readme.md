<p align="center">
  <img src="public/img/logo.png" alt="Aligarh Logo" width="200">
</p>

<h1 align="center">Aligarh</h1>

<p align="center">
  <strong>Multi-Tenant Educational Institution Management System</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-8.75+-FF2D20?style=flat&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-7.3%20%7C%208.0+-777BB4?style=flat&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/Version-1.4-blue?style=flat" alt="Version">
  <img src="https://img.shields.io/badge/License-TBD-yellow?style=flat" alt="License">
</p>

---

## 📖 About Aligarh

**Aligarh** is a comprehensive, multi-tenant SaaS platform designed for educational institutions to streamline administrative, academic, and financial operations. Built on Laravel 8 with robust multi-tenancy architecture, Aligarh enables schools, colleges, and educational organizations to manage their entire ecosystem from a single, secure platform.

Each institution operates in complete isolation with dedicated databases, custom domains, and role-based access controls—ensuring data privacy, scalability, and flexibility.

### 🎯 Core Capabilities

- **Multi-Tenant Architecture**: Isolated databases per institution with centralized management
- **Academic Session Management**: Year-wise tracking with automatic session scoping
- **Student Lifecycle Management**: Admissions, enrollment, academics, results, and transcripts
- **Fee Management**: Invoicing, collections, discounts, and comprehensive financial reporting
- **Examination System**: Marks entry, grade calculation, result processing, and printable transcripts
- **Staff Management**: Teachers, employees, attendance, and leave tracking
- **Library System**: Book inventory, circulation, and member management
- **Attendance & Scheduling**: Daily tracking, automated routines, and timetables
- **Reports & Analytics**: PDF generation, dashboards, and detailed insights

---

## ✨ Key Features

### 📚 Academic Management
- Academic session management with start/end date tracking
- Classes, sections, and subject configuration
- Flexible routine/timetable scheduling
- Session-scoped data isolation

### 👨‍🎓 Student Management
- Online and offline admission processing
- Student profiles with comprehensive details
- Parent/guardian management (multiple guardians per student)
- Academic history and session transitions
- Active/inactive enrollment status tracking

### 👨‍🏫 Staff Management
- Teacher and employee profiles
- Department and role assignments
- Daily attendance tracking
- Leave management system

### 💰 Fee Management
- Customizable fee structures per class/session
- Monthly invoicing with automated calculations
- Discount management (percentage/fixed amount)
- Payment collection and receipt generation
- Comprehensive fee collection reports
- Outstanding dues tracking

### 📝 Examination System
- Multiple exam categories and types
- Marks entry with subject-wise grading
- Automated grade calculation
- Result processing and verification
- Printable transcripts and certificates
- Exam average and analysis reports

### 📖 Library Management
- Book inventory management
- Member registration and tracking
- Book circulation (issue/return)
- Overdue tracking

### 📅 Attendance & Scheduling
- Student daily attendance
- Employee/teacher attendance tracking
- Routine management with teacher-class-section mapping
- Automated schedule generation

### 📊 Reports & Printables
- PDF generation with DomPDF
- Tenant-specific printable customization
- ID cards, certificates, transcripts
- Fee collection reports
- Attendance summaries
- Exam results and analysis

### 🔐 Security & Permissions
- Role-Based Access Control (RBAC) via Spatie Permission
- Route-level permission enforcement
- User types: Admin, Teacher, Employee, Student, Guardian
- Session-based authentication
- OAuth2 API authentication via Laravel Passport

---

## 🛠️ Technology Stack

| Category | Technologies |
|----------|-------------|
| **Backend Framework** | Laravel 8.75+ |
| **PHP Version** | 7.3 / 8.0+ |
| **Database** | MySQL (Multi-tenant architecture) |
| **Multi-Tenancy** | [Stancl/Tenancy v3.6](https://tenancyforlaravel.com/) |
| **Authentication** | Laravel Sanctum + Passport (OAuth2) |
| **Permissions** | Spatie Laravel Permission v6.20 |
| **Frontend** | Vue 2 (as library), Bootstrap 3, jQuery |
| **PDF Generation** | Barryvdh Laravel DomPDF |
| **DataTables** | Yajra Laravel DataTables Oracle |
| **SMS Integration** | BulkSMS API (queued jobs) |
| **Localization** | Laravel i18n (English base, extensible) |

---

## 📋 Prerequisites

Before installing Aligarh, ensure you have the following:

- **PHP** >= 7.3 or 8.0+ with required extensions (see `composer.json`)
- **Composer** 2.x
- **MySQL/MariaDB** 5.7+ or 8.0+
- **Node.js** 14+ and npm/yarn (for frontend assets)
- **Laravel Valet** (recommended for local development with SSL and subdomain routing)
- **Docker & Docker Compose** (optional, for containerized setup)

---

## 🚀 Installation

### Method 1: Traditional Setup (with Laravel Valet - Recommended)

#### Step 1: Clone the Repository
```bash
git clone https://github.com/yourusername/aligarh.git
cd aligarh
```

#### Step 2: Install Dependencies
```bash
composer install
npm install
```

#### Step 3: Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file and configure your landlord database:
```env
# Application
APP_NAME=Aligarh
APP_ENV=local
APP_DEBUG=true
APP_URL=http://aligarh.test

# Central/Landlord Domain
CENTRAL_DOMAIN=aligarh.test

# Landlord Database
DB_CONNECTION=mysql_landlord
LANDLORD_DB_HOST=127.0.0.1
LANDLORD_DB_PORT=3306
LANDLORD_DB_DATABASE=aligarh_landlord
LANDLORD_DB_USERNAME=root
LANDLORD_DB_PASSWORD=

# Tenant Database Template (will be auto-created per tenant)
TENANT_DB_HOST=127.0.0.1
TENANT_DB_PORT=3306
TENANT_DB_USERNAME=root
TENANT_DB_PASSWORD=
```

#### Step 4: Database Setup
```bash
# Run landlord migrations
php artisan migrate --database=mysql_landlord

# Seed default data (roles, permissions, admin user)
php artisan db:seed
```

#### Step 5: Laravel Passport Setup (API Authentication)
```bash
php artisan passport:install
```

#### Step 6: Storage Linking
```bash
php artisan storage:link
```

#### Step 7: Laravel Valet Setup (for Multi-Tenancy with SSL)
```bash
# Install Valet if not already installed
composer global require laravel/valet
valet install

# Navigate to the project directory
cd /path/to/aligarh

# Park the directory (allows *.test domain access)
valet park

# Secure with SSL
valet secure aligarh

# Your landlord app is now accessible at: https://aligarh.test
# Tenant subdomains will automatically work: https://tenant1.aligarh.test
```

#### Step 8: Create Your First Tenant
```bash
php artisan tenants:create tenant1 tenant1.aligarh.test
# Follow the prompts to set up tenant database
```

#### Step 9: Access the Application
- **Landlord**: `https://aligarh.test`
- **Tenant**: `https://tenant1.aligarh.test`

---

### Method 2: Docker Setup

#### Step 1: Clone and Configure
```bash
git clone https://github.com/yourusername/aligarh.git
cd aligarh
cp .env.example .env
```

#### Step 2: Configure Environment for Docker
Edit `.env` and update database hosts to use Docker service names:
```env
LANDLORD_DB_HOST=mysql
TENANT_DB_HOST=mysql
```

#### Step 3: Build and Start Containers
```bash
docker-compose up -d
```

#### Step 4: Install Dependencies Inside Container
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --database=mysql_landlord
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan passport:install
docker-compose exec app php artisan storage:link
```

#### Step 5: Access Application
- Application will be available at `http://localhost:8080` (or configured port)
- For multi-tenant subdomain testing, configure local hosts file or use Nginx proxy

---

## 🏗️ Multi-Tenancy Architecture

Aligarh uses **[Stancl/Tenancy](https://tenancyforlaravel.com/)** for automatic multi-tenant isolation:

### Database Structure
- **Landlord Database** (`aligarh_landlord`): 
  - Stores tenant information, domains, and central system data
  - Manages tenant provisioning and domain mapping
  
- **Tenant Databases** (`tenant_*`): 
  - Each institution gets an isolated database
  - Contains students, teachers, fees, exams, library data, etc.
  - Automatically created and migrated on tenant creation

### How Tenancy Works
1. User accesses `https://school1.aligarh.test`
2. Tenancy middleware identifies tenant by domain
3. Database connection switches to `tenant_school1` database
4. All queries automatically scoped to that tenant
5. Complete data isolation—no cross-tenant access

### Key Files
- `config/tenancy.php` - Tenancy configuration
- `routes/tenant_web.php` - Tenant-specific routes
- `routes/web.php` - Landlord routes
- `database/migrations/tenant/` - Tenant database migrations

---

## ⚙️ Configuration

### Important Environment Variables

```env
# Application
APP_NAME=Aligarh
APP_ENV=production
APP_DEBUG=false
CENTRAL_DOMAIN=your-domain.com

# Databases
DB_CONNECTION=mysql_landlord
LANDLORD_DB_DATABASE=aligarh_landlord
# ... (other DB settings)

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null

# SMS Configuration (BulkSMS)
BULKSMS_API_KEY=your_api_key

# Queue Configuration
QUEUE_CONNECTION=database

# Debug (dev only)
DEBUGBAR_ENABLED=true
```

### Permission System
- Permissions are route-based (e.g., `students.index`, `fees.create`)
- Configured in `config/permission.php`
- Middleware: `route_has_permission` enforces access control
- Seeded via `database/seeders/PermissionsSeeder.php`

---

## 📱 Usage

### Creating a New Tenant

**Via Artisan Command:**
```bash
php artisan tenants:create school_name school.domain.com
```

**Via Admin Panel:**
1. Login to landlord domain as admin
2. Navigate to Tenant Management
3. Click "Create New Tenant"
4. Fill in institution details and domain
5. System automatically provisions database and runs migrations

### User Roles & Permissions

| Role | Description | Default Permissions |
|------|-------------|-------------------|
| **Super Admin** | Landlord system admin | Full system access |
| **Admin** | Institution admin | All tenant operations |
| **Teacher** | Teaching staff | Student marks, attendance, class management |
| **Employee** | Non-teaching staff | Limited access based on role |
| **Student** | Enrolled students | View own records, results, fees |
| **Guardian** | Parent/Guardian | View ward's records |

---

## 👨‍💻 Development Guidelines

### Project Structure
```
app/
├── Http/Controllers/Admin/    # 40+ domain controllers
├── Model/                      # Core models (Student, Teacher, etc.)
├── Helpers/                    # Helper classes (PrintableViewHelper, etc.)
├── Observers/                  # Model lifecycle hooks
routes/
├── tenant_web.php             # Tenant routes (400+ lines)
├── web.php                    # Landlord routes
├── tenant_api.php             # Tenant API endpoints
resources/
├── views/admin/               # Blade templates
│   ├── printable/             # PDF report templates
│   │   └── {tenant_id}/       # Tenant-specific overrides
├── lang/en/                   # Localization files
```

### Adding a New Feature

1. **Create Migration**
   ```bash
   php artisan make:migration create_feature_table --path=database/migrations/tenant
   ```

2. **Generate Model**
   ```bash
   php artisan make:model FeatureName
   ```

3. **Register Route** (in `routes/tenant_web.php`)
   ```php
   Route::get('/features', 'FeaturesController@index')->name('features.index');
   ```

4. **Create Controller**
   ```bash
   php artisan make:controller Admin/FeaturesController
   ```

5. **Add Permission** (in `config/permission.php` or via seeder)
   ```php
   'features.index',
   'features.create',
   'features.edit',
   'features.delete',
   ```

6. **Build View** (in `resources/views/admin/features/`)

### Tenant-Specific Printables
Use `PrintableViewHelper::resolve()` for tenant-specific customization:

```php
use App\Helpers\PrintableViewHelper;

// Automatically resolves to tenant-specific view if exists
return PDF::loadView(PrintableViewHelper::resolve('exam_transcript'), $data)
    ->download('transcript.pdf');
```

Structure:
```
resources/views/admin/printable/
├── exam_transcript.blade.php           # Default
└── tenant_school1/
    └── exam_transcript.blade.php       # School-specific override
```

### Localization
Translation files in `resources/lang/en/`:
- `messages.php` - UI messages, alerts
- `labels.php` - Form labels, headers
- `modules.php` - Module-specific strings
- `common.php` - Shared strings
- `validation.php` - Validation messages

**Usage:**
```php
// In Blade
{{ __('messages.success') }}

// In Controllers
return redirect()->with('message', __('messages.created_success'));
```

---

## 🧪 Testing

Run the test suite:
```bash
# All tests
php artisan test

# Specific test file
php artisan test tests/Feature/StudentTest.php

# With coverage
php artisan test --coverage
```

Tests are located in:
- `tests/Feature/` - Feature/integration tests
- `tests/Unit/` - Unit tests

---

## 📡 API Documentation

### Authentication
The API uses **Laravel Passport** for OAuth2 authentication.

**Get Access Token:**
```bash
POST /oauth/token
{
  "grant_type": "password",
  "client_id": "your_client_id",
  "client_secret": "your_client_secret",
  "username": "user@example.com",
  "password": "password",
  "scope": "*"
}
```

### API Endpoints
Tenant API routes defined in `routes/tenant_api.php`

**Example Requests:**
```bash
# Get students (requires authentication)
GET https://tenant.domain.com/api/students
Authorization: Bearer {access_token}

# Create student
POST https://tenant.domain.com/api/students
Authorization: Bearer {access_token}
Content-Type: application/json
```

For complete API documentation, see [AJAX_ROUTES_DOCUMENTATION.md](docs/AJAX_ROUTES_DOCUMENTATION.md)

---

## 📸 Screenshots

> _Screenshots will be added here to showcase the platform's key features_

### Dashboard
![Dashboard](docs/screenshots/dashboard.png)

### Student Management
![Student Management](docs/screenshots/students.png)

### Fee Collection
![Fee Collection](docs/screenshots/fees.png)

### Examination System
![Exams](docs/screenshots/exams.png)

---

## 🤝 Contributing

We welcome contributions! To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'feat(scope): add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Commit Message Format
Follow conventional commits:
```
<type>(<scope>): <subject>

- Key change 1
- Key change 2

Examples:
feat(students): add bulk import functionality
fix(fees): correct discount calculation logic
docs(readme): update installation instructions
```

### Code Style
- Follow PSR-12 coding standards
- Use Laravel best practices
- Write descriptive variable and method names
- Add PHPDoc blocks for complex methods

---

## 📄 License

**License information to be determined.**

_This section will be updated with license details._

---

## 🙏 Credits & Support

**Aligarh** is developed and maintained with dedication to improving educational institution management.

### Support
For questions, issues, or feature requests:
- **Email**: [your-email@domain.com](mailto:your-email@domain.com)
- **GitHub Issues**: [Create an issue](https://github.com/yourusername/aligarh/issues)

### Acknowledgments
- Laravel Framework and Community
- Stancl/Tenancy for multi-tenant architecture
- Spatie for Laravel Permission package
- All contributors and users

---

<p align="center">
  Made with ❤️ for Educational Institutions
</p>

<p align="center">
  <strong>Aligarh v1.4</strong> | Streamlining Education Management
</p>
