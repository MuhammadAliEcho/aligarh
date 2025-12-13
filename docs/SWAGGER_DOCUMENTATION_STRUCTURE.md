# Swagger API Documentation Structure

## Overview
All Swagger/OpenAPI documentation is organized in a **separate folder structure** using **annotation-based generation** with clean separation between documentation and controller logic.

## Directory Structure

```
app/Http/Controllers/Api/Docs/
├── SwaggerBase.php              # Main API info, servers, security schemes
├── Guardian/                    # Guardian API documentation
│   ├── AuthDocs.php            # Login, logout endpoints
│   ├── UserDocs.php            # User info endpoint
│   ├── HomeDocs.php            # Dashboard endpoint
│   ├── StudentDocs.php         # Student list, attendance
│   ├── ImageDocs.php           # Student image retrieval
│   ├── ExamDocs.php            # Exam information
│   ├── FeeDocs.php             # Fee invoices
│   ├── RoutineDocs.php         # Class routines/timetables
│   ├── NoticeBoardDocs.php     # Notice board announcements
│   └── QuizDocs.php            # Quiz information
└── TMS/                        # Teacher Management System docs
    ├── AuthDocs.php            # TMS login, logout
    ├── UserDocs.php            # TMS user info
    └── AttendanceDocs.php      # Attendance recording
```

## Technology Stack

- **Laravel 8.75+**
- **Swagger-PHP 4.x** (zircote/swagger-php ^4.0)
- **L5-Swagger 8.x** (darkaonline/l5-swagger ^8.0)
- **Doctrine Annotations 1.14**

## Dynamic Base URL

The Swagger configuration uses **dynamic URL resolution** for multi-tenant architecture:

### In SwaggerBase.php:
```php
/**
 * @OA\Server(url="/", description="Current Tenant API Server")
 */
```

This means:
- ✅ Works with any tenant domain automatically
- ✅ No hardcoded URLs
- ✅ Perfect for multi-tenant architecture
- ✅ Resolves to current tenant: `https://{tenant}.aligarh.test/`

## Accessing Documentation

### Web UI (requires developer role):
```
https://{tenant-domain}/api/documentation
```

### JSON Spec:
```
https://{tenant-domain}/api-docs/api-docs.json
```

## Documented Endpoints (19 Total)

### Guardian API (14 endpoints)
- `POST /guardian/login` - Authenticate guardian
- `POST /guardian/logout` - Logout guardian
- `GET /guardian/user` - Get user info
- `GET /guardian/home` - Get dashboard data
- `POST /guardian/students` - Get students list
- `GET /guardian/students/image/{image}` - Get student image
- `GET /guardian/attendance/{student_id}` - Get attendance
- `POST /guardian/student-profile` - Get student profile
- `POST /guardian/exams` - Get exam data
- `POST /guardian/fee` - Get fee invoices
- `POST /guardian/student-invoices` - Get student invoices
- `GET /guardian/routines` - Get class routines
- `GET /guardian/noticeboard` - Get notice board
- `GET /guardian/quiz/{student_id}` - Get quiz data

### TMS API (5 endpoints)
- `POST /tms/login` - Authenticate TMS user
- `POST /tms/logout` - Logout TMS user
- `GET /tms/user` - Get user info
- `POST /tms/attendance` - Record attendance
- `POST /tms/cachedata` - Cache attendance data

## Workflow: Annotation-Based Generation

### 1. Edit Documentation Files
Update annotations in `app/Http/Controllers/Api/Docs/` folder

### 2. Generate Documentation
```bash
php artisan swagger:generate
```

This command:
1. Calls `l5-swagger:generate` to scan annotations
2. Generates `storage/api-docs/api-docs.json` from annotations
3. Copies to `public/api-docs/api-docs.json` for web access

### 3. View in Browser
Documentation automatically updates at `/api/documentation`

## Configuration

### config/l5-swagger.php
```php
'annotations' => [
    base_path('app/Http/Controllers/Api/Docs'),  // ← Scans only Docs folder
],
'paths' => [
    'docs_json' => 'api-docs.json',  // Generated filename
],
```

### routes/tenant_web.php
```php
Route::get('/api/documentation', function() {
    return view('swagger');
})->middleware(['auth', 'auth.active', 'route_has_permission', 'role:developer']);
```

## Benefits of This Structure

✅ **Annotation-Based**: Automatic generation from OpenAPI annotations
✅ **Clean Separation**: Documentation separate from controller logic
✅ **Organized**: Easy to find and update docs by module/feature
✅ **Scalable**: Add new modules by creating new folders
✅ **Maintainable**: Each doc file focuses on one feature
✅ **Dynamic URLs**: Works with multi-tenant domains automatically
✅ **No Controller Pollution**: Controllers remain clean without @OA annotations
✅ **Version Control**: Track documentation changes in Git

## Adding New Documentation

### 1. Create new doc file:
```php
// app/Http/Controllers/Api/Docs/Guardian/NewFeatureDocs.php
<?php

namespace App\Http\Controllers\Api\Docs\Guardian;

/**
 * Guardian New Feature API Documentation
 */
class NewFeatureDocs
{
    /**
     * @OA\Get(
     *     path="/guardian/new-feature",
     *     summary="Get New Feature Data",
     *     description="Detailed description of what this endpoint does",
     *     operationId="guardianGetNewFeature",
     *     tags={"Guardian"},
     *     security={{"bearerToken": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="string", example="sample")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function newFeature() {}
}
```

### 2. Regenerate docs:
```bash
php artisan swagger:generate
```

Documentation auto-updates!

## Notes

- **Authentication**: All endpoints use Bearer token (except login)
- **Multi-tenant**: Server URL "/" resolves to current tenant domain
- **Organization**: Documentation organized by business logic, not by controller
- **Empty Methods**: Doc class methods are empty (annotations only)
- **Access Control**: Documentation requires authenticated user with `developer` role
- **Auto-generation**: Run `php artisan swagger:generate` after any annotation changes

## Troubleshooting

### Documentation not updating?
```bash
php artisan swagger:generate
```

### Endpoints missing?
- Check annotations syntax in Docs files
- Ensure namespace is correct: `App\Http\Controllers\Api\Docs\{Module}`
- Run generation command to see errors

### Can't access /api/documentation?
- Ensure you're logged in as a user with `developer` role
- Check route is in `tenant_web.php`, not `tenant_api.php`
