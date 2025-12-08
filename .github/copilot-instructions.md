# Aligarh - Educational Institution Management System

## Project Overview
**Aligarh** is a multi-tenant **Laravel 8** (v8.75+) school/educational institution management platform with SaaS capabilities. It manages students, teachers, employees, academics, fees, examinations, attendance, and library operations.

**Framework & Requirements**:
- **Laravel**: 8.75+ (stable, modern version with PHP 8 support)
- **PHP**: 7.3 or 8.0+
- **Database**: Multi-tenant MySQL architecture

**Key Architecture**: Multi-tenant system using [Stancl/Tenancy v3.6](https://tenancyforlaravel.com/) with:
- **Landlord database**: Central system for tenant management & domain mapping
- **Tenant databases**: Isolated per-institution data
- **Shared codebase**: Single app serves all tenants via domain routing

## Critical Patterns & Conventions

### 1. Multi-Tenancy Architecture
- **Tenant isolation**: Always operates within an activated tenant context via `tenancy()` middleware
- **Routes**: Web routes split between `routes/web.php` (landlord) and `routes/tenant_web.php` (per-tenant)
- **Models**: Most models (Student, User, Teacher, etc.) are tenant-scoped; queries automatically filter by active tenant
- **Config**: `config/tenancy.php` - Stancl/Tenancy bootstrappers handle database, cache, filesystem, and queue tenancy
- **Example**: `Student::query()` in tenant context only returns that tenant's students

### 2. User & Permission System
- **Base class**: `App\User` extends Laravel Authenticatable with `HasRoles` (Spatie Permission)
- **User types**: 'employee', 'teacher', 'student', 'guardian' - stored in `user_type` column
- **Permissions**: Spatie/Laravel-Permission v6.20 via `route_has_permission` middleware
- **Route-Based Permissions**: Each permission is based on a route name and each route has a name assigned for permission
  - **RouteNamePermissionMiddleware** (`app/Http/Middleware/RouteNamePermissionMiddleware.php`): Middleware that allows or blocks routes based on permissions assigned to roles
  - **Permission naming convention**: Route names map to permission names (e.g., route `students.index` requires permission `students.index`)
  - **Role assignment**: Permissions are assigned to roles in `config/permission.php` or via database seeders
  - **Route group setup**: All tenant routes wrapped in `Route::group(['middleware' => ['auth', 'auth.active', 'route_has_permission']])` to enforce permission checks
  - **Usage in routes**: `Route::get('/students', 'StudentsController@index')->name('students.index');` automatically checks for `students.index` permission
- **Session tracking**: Users have `academic_session` field (links to AcademicSession model) for session-scoped data
- **Key trait**: `HasRoles` automatically loaded on User model

### 3. Academic Session Scoping
- **Core concept**: All academic operations filtered by active `AcademicSession` (e.g., 2024-2025)
- **Student scopes**:
  - `.currentSession()` - filters by `Auth::user()->academic_session`
  - `.active()` / `.inactive()` - active/inactive enrollment status
  - `.newAdmission()` / `.oldAdmission()` - based on session start/end dates
- **Academic dates**: Sessions store start/end in 'd/m/Y' format; convert with `Carbon::createFromFormat('d/m/Y', ...)`

### 4. Model Relationships & Scopes
- **Heavy use of Query Scopes** - custom `scope*()` methods on Student, Teacher, Employee models
- **Example from Student.php**:
  ```php
  Student::currentSession()->active()->withoutDiscount()->get()
  ```
- **Dynamic relationships**: Models use HasOne/BelongsTo for academic sessions, classes, sections
- **Guardian relationship**: Separate `Guardian` model; students have multiple guardians

### 5. Core Domain Models
| Model | Purpose | Key Fields |
|-------|---------|-----------|
| **Student** | Enrollment & academics | `session_id`, `discount`, `net_amount`, `active`, `date_of_admission`, `phone` |
| **Teacher** | Staff & teaching assignments | Relates to Subject & Routine |
| **Employee** | Non-teaching staff | Attendance tracking via EmployeeAttendance |
| **User** | Authentication & roles | `user_type`, `academic_session`, `foreign_id` (links to Teacher/Employee/Student), `settings` (JSON) |
| **InvoiceMaster/Detail** | Fee billing | `month_id`, `student_id`, relates to FeeCollectionReport |
| **StudentResult/Exam** | Grade management | Exam marks, exam remarks, result attributes |
| **Book/Library** | Inventory & circulation | Library management system |
| **Routine/Schedule** | Timetabling | Teacher-class-section schedules |

### 6. Controllers & Organization
- **Location**: `app/Http/Controllers/Admin/*` (40+ specialized controllers)
- **Pattern**: CRUD controllers handling GET/POST for each domain (e.g., `StudentsController`, `FeesController`, `ExamController`)
- **Tenant routes**: All wrapped in `Route::group(['middleware' => ['auth', 'auth.active', 'route_has_permission']])`
- **Notable controllers**:
  - `DashboardController` - Aggregate stats using `tenancy()->tenant->system_info`
  - `ManageStudentResultCtrl` - Result entry & grade calculation
  - `FeeCollectionReportController` - Fee tracking & reports
  - `ExamReportController` - Exam analysis

### 7. Request & Validation
- **DataTables integration**: `yajra/laravel-datatables-oracle` for server-side tables (used in Admin views)
- **API layer**: `routes/api.php` & `routes/tenant_api.php` - Passport token auth for mobile/external clients
- **Form requests**: Implicit validation via middleware; explicit in controller methods

### 8. Views & Frontend
- **Blade templates**: `resources/views/admin/**` - Laravel Blade with Bootstrap 3 (jQuery)
- **Vue 2 as Library** (NOT full SPA): Vue 2.1 loaded as CDN/script library, not a full build setup
  - Used inline in Blade templates for component interactivity, NOT a webpack-bundled SPA
  - Axios for AJAX calls to backend API endpoints
  - Bootstrap Sass & jQuery for styling and DOM manipulation
  - **No build process**: Assets served directly from `public/` without webpack/Laravel Mix compilation
  - `webpack.mix.js` exists but is NOT used in the project
- **Printing**: `resources/views/admin/printable/` - DomPDF integration (barryvdh/laravel-dompdf) for certificates, ID cards, reports

### 9. Data Persistence & Jobs
- **SMS integration**: `App\Jobs\SendSmsJob` - queued notifications via BulkSms API
- **Notifications**: `App\Notification` + `NotificationLog` for audit trail
- **Observers**: `app/Observers/` - Model lifecycle hooks (create/update/delete)
- **Background jobs**: Laravel Queue system (config in `config/queue.php`)

### 10. Testing & Debugging
- **Tests**: `tests/Feature/` and `tests/Unit/` with PHPUnit 9.5
- **Debugbar**: `barryvdh/laravel-debugbar` (dev dependency) - enable with `DEBUGBAR_ENABLED=true`
- **Key test patterns**: Use `Tests\TestCase` base; factories in `database/factories/`

## Common Workflows

### Adding a New Feature
1. **Create migration**: `php artisan make:migration create_feature_table --create`
2. **Generate model**: `php artisan make:model FeatureName`
3. **Register route**: Add to `routes/tenant_web.php` (within auth middleware group)
4. **Create controller**: `php artisan make:controller Admin/FeatureController`
5. **Add permission**: Register in `config/permission.php` and seed via `PermissionsSeeder`
6. **Build view**: Blade template in `resources/views/admin/feature/**`

### Localization & Multi-Language Support
- **Language files location**: `resources/lang/en/` (English - can add other languages later)
- **Core language files** (6 files):
  - `messages.php` - Generic UI messages, alerts, notifications
  - `labels.php` - Form labels, field names, column headers
  - `validation.php` - Validation error messages and custom attributes
  - `common.php` - Shared strings across all modules (time, status, actions)
  - `reports.php` - Report and printable-related strings
  - `modules.php` - ALL module strings organized by underscore keys (students_management, fees_invoice, exams_result, etc.)

- **Usage in Blade templates**:
  ```blade
  {{ __('messages.success') }}
  {{ __('labels.student_name') }}
  {{ __('modules.students_management') }}
  ```

- **Usage in Vue.js (Global mixin approach)**:
  ```php
  <!-- In Blade layout (e.g., base.blade.php) -->
  <script>
  window.__trans = @json([
      'messages' => __('messages'),
      'labels' => __('labels'),
      'modules' => __('modules'),
      'common' => __('common'),
      'reports' => __('reports'),
  ]);
  </script>
  ```

  ```html
  <!-- In Vue components -->
  <div>{{ __trans['modules.students_management'] }}</div>
  <p>{{ __trans['messages.success'] }}</p>
  ```

- **Usage in Controllers**:
  ```php
  return redirect()->with('message', __('messages.created_success'));
  // Validation uses lang files automatically
  ```

### Modifying Academic Session Logic
- Always check `Auth::user()->academic_session` for current context
- Use Student scopes (`.currentSession()`, `.active()`, etc.)
- Convert session dates: `Carbon::createFromFormat('d/m/Y', $session->start)->format('Y-m-d')`
- System enforces session scoping at controller level via middleware

### Generating Reports (Printable)
- Use `barryvdh/laravel-dompdf` for PDF output
- **Tenant-specific printables**: Use `PrintableViewHelper::resolve()` to support per-tenant customization
  - Helper checks for tenant-specific printable first, falls back to default
  - Structure: `resources/views/admin/printable/{tenant_id}/{view_name}.blade.php`
  - Example: `resources/views/admin/printable/sags/exam_transcript.blade.php`
- **Usage in controllers**:
  ```php
  use App\Helpers\PrintableViewHelper;
  
  // Before: return PDF::loadView('admin.printable.exam_transcript', $data)->download('file.pdf');
  // After: return PDF::loadView(PrintableViewHelper::resolve('exam_transcript'), $data)->download('file.pdf');
  ```
- When adding new tenants, copy all default printables to tenant folder for customization

### Multi-Tenant Debugging
- Check active tenant: `tenancy()->tenant->id` and `tenancy()->tenant->system_info`
- Database context: Active tenant database automatically switched via `DatabaseTenancyBootstrapper`
- End tenancy: `tenancy()->end()` (only in landlord routes)

## Dependencies (Key Libraries)

- `laravel/framework` ^8.75 - Core framework
- `stancl/tenancy` ^3.6 - Multi-tenancy
- `spatie/laravel-permission` ^6.20 - RBAC
- `laravel/passport` ^10.0 - OAuth2 API authentication
- `barryvdh/laravel-dompdf` - PDF generation
- `yajra/laravel-datatables-oracle` - DataTables server-side processing

## File Structure Highlights
```
app/
  Student.php, Teacher.php, Employee.php, User.php    # Core models
  Helpers/PrintableViewHelper.php                     # Tenant printable resolver
  Http/Controllers/Admin/                             # 40+ domain controllers
  Http/Traits/HasLeave.php                            # Attendance leave logic
  Jobs/SendSmsJob.php                                 # Background tasks
  Observers/                                           # Model lifecycle
routes/
  tenant_web.php                                      # 400+ lines of tenant routes
  api.php, tenant_api.php                             # REST API endpoints
config/
  tenancy.php                                         # Multi-tenancy setup
  permission.php                                      # Role/permission config
resources/views/admin/
  printable/                                          # Default PDF report views
    exam_transcript.blade.php
    exam_average_result.blade.php
    ... (other defaults)
    {tenant_id}/                                      # Tenant-specific overrides
      exam_transcript.blade.php                       # Customized per tenant
```

## Environment Variables (Key)
```bash
CENTRAL_DOMAIN=aligarh.test             # Landlord domain
DB_CONNECTION=mysql_landlord            # Landlord DB
LANDLORD_DB_HOST, LANDLORD_DB_DATABASE  # Landlord credentials
DEBUGBAR_ENABLED=true                   # Enable debug toolbar
```

## AI Agent Workflow Guidelines
**CRITICAL**: Before making any file updates, code changes, or significant modifications:
1. **Analyze & Plan**: Read relevant files and understand the current implementation
2. **Discuss**: Explain your proposed changes in clear text to the user
3. **Wait for Approval**: Get explicit user confirmation before implementing
4. **Then Execute**: Only after approval, proceed with file updates/code changes
5. **Report & Commit Message**: After completion, provide a short, smart commit message

**Commit Message Format** (Smart & Concise):
```
<type>(<scope>): <subject>

- Key change 1
- Key change 2 (max 2-3 lines)

Examples:
feat(printables): add tenant-specific printable customization

- PrintableViewHelper with fallback to defaults
- Update 7 controllers (17 printable views)

feat(i18n): implement English localization (Phase 1)

- 550+ translation strings across 6 language files
- Foundation for multi-language support
```

This ensures changes align with user intent and prevents unwanted modifications.
