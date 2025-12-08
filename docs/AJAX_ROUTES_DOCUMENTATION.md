# AJAX Routes & Permission Handling Documentation

## Overview

This document outlines all AJAX routes in Aligarh EMS and their permission handling strategy. AJAX routes are used for:
- Select2/Dropdown data fetching
- Profile image retrieval
- PDF report generation
- Dynamic data loading via AJAX

## Route Classification

### 1. AJAX Data Endpoints (SELECT2/DROPDOWN)

These routes fetch filtered data for form dropdowns and select2 fields.

| Route Name | Controller | Purpose | Status |
|-----------|-----------|---------|--------|
| `students.guardians.list` | StudentsController | Fetch guardians for student form | ✓ Named |
| `teacher.find` | TeacherController | Find teacher via select2 | ✓ Named |
| `employee.find` | EmployeeController | Find employee via select2 | ✓ Named |
| `student-migrations.get` | StudentMigrationsController | Get students for migration | ✓ Named |
| `exam-reports.find.student` | ExamReportController | Find student for exam reports | ✓ Named |
| `fee.findstu` | FeesController | Find student for fees | ✓ Named |

**Permission Strategy**: All these routes are in `ignore_routes` for now because:
- They return filtered data based on user's session/academic context
- Data is already scoped (tenant isolation)
- They support UI interactions without modifying data

**Future Enhancement**: Add explicit permission checks per module (e.g., `students.view` to access `students.guardians.list`)

---

### 2. Image/Avatar Endpoints

These routes return user profile images and avatars.

| Route Name | Purpose | HTTP Method | Status |
|-----------|---------|------------|--------|
| `students.image` | Return student profile image | GET | ✓ Ignored |
| `teacher.image` | Return teacher profile image | GET | ✓ Ignored |
| `employee.image` | Return employee profile image | GET | ✓ Ignored |

**Permission Strategy**: Ignored because:
- Images are non-sensitive media files
- Used for UI rendering only
- User can only view images for records they have access to (via tenant context)

**Security Note**: Image access is implicitly controlled by tenant database isolation, not explicit route permissions.

---

### 3. Report & Print Endpoints

These routes generate and return PDF documents.

| Route Name | Purpose | HTTP Method | Related Permission |
|-----------|---------|------------|-------------------|
| `fee.chalan.print` | Generate chalan PDF | GET | fee.create |
| `fee.invoice.print` | Generate invoice PDF | GET | fee.index |
| `fee.group.chalan.print` | Generate group chalan | GET | fee.create |
| `fee.bulk.print.invoice` | Bulk invoice generation | GET | fee.create |
| `students.card` | Generate student ID card | GET | students.index |

**Permission Strategy**: Currently ignored, but implicitly protected because:
- Users must have accessed the parent form (which requires permission)
- Print endpoints are not directly accessible via URL
- Future: Could add explicit permission for sensitive reports

---

## Current Ignore Routes Configuration

Location: `config/permission.php` → `ignore_routes` array

### Why Routes Are Ignored:

1. **Debugbar Routes** (`debugbar.*`)
   - Development tools only
   - Not accessible in production

2. **Authentication Routes** (`login`, `logout`, `login.post`, etc.)
   - Must be accessible before user is authenticated
   - Permission check doesn't apply yet

3. **Image Endpoints** (`*.image`)
   - Non-sensitive UI assets
   - Controlled implicitly by tenant isolation

4. **AJAX Data Routes** (`*.find`, `*.list`, `*.get`)
   - Support form dropdowns
   - Data already filtered by tenant/session context
   - Should have permission in future

5. **Print/PDF Endpoints** (`*.print`)
   - Generated on-demand from data user can already access
   - Implicit permission via parent access

---

## Permission Dependency Architecture

```
User Permission Request
    ↓
Route Middleware Check (route_has_permission)
    ↓
┌─────────────────────────────────────────────┐
│ Route Name Exists + Has Permission?        │
│                                             │
│ YES → Allow                                 │
│ NO → Check Ignore List                     │
│      ├─ In Ignore List → Allow             │
│      └─ Not Ignored → Deny (403)           │
└─────────────────────────────────────────────┘
```

---

## Adding New AJAX Routes

### Best Practice Workflow:

1. **Create the route WITH a name:**
   ```php
   Route::get('/data', [Controller::class, 'method'])->name('.data.fetch');
   ```

2. **Decide protection level:**
   - **Option A**: Add to permission list (recommended for sensitive data)
     ```php
     // In PermissionsSeeder or config
     'module.data.fetch' => 'View module data',
     ```
   
   - **Option B**: Add to `ignore_routes` (for public/implicit data)
     ```php
     // In config/permission.php
     'module.data.fetch' // Safe for all authenticated users
     ```

3. **Document in routes with comment:**
   ```php
   // AJAX: Fetch filtered data for dropdown
   Route::get('/data', [Controller::class, 'method'])->name('.data.fetch'); 
   ```

---

## Future Enhancements

### Phase 1: Route Names (✓ Done)
- All AJAX routes should have explicit names
- Enables granular permission tracking

### Phase 2: Permission Dependencies (Planned)
- Implement dependency mapping:
  ```php
  'dependencies' => [
      'students.edit' => ['students.index', 'students.show'],
      'students.guardians.list' => ['students.view'], // Future
  ]
  ```
- Auto-grant dependent permissions when parent is granted

### Phase 3: Permission Validation (Planned)
- Warn when granting permissions without dependencies
- Show permission hierarchy in role management UI

### Phase 4: Audit Logging (Planned)
- Log all AJAX endpoint accesses
- Track which data was accessed/modified

---

## Testing AJAX Routes

### Manual Testing:

```bash
# Test route with authentication
curl -b cookies.txt http://localhost/students/guardians/list

# Test route without authentication
curl http://localhost/students/guardians/list
# Should redirect to login
```

### Permission Testing:

```php
// In tests
$user = User::find(1);
$user->revokePermissionTo('students.index');

// Try to access guardians list
$response = $this->actingAs($user)->get('/students/guardians/list');
// Should still work (in ignore_routes)
// Future: Should require 'students.view' permission
```

---

## Troubleshooting

### Problem: AJAX endpoint returns 403 Forbidden
**Solution**: Check if route name is in `ignore_routes` or user has explicit permission

### Problem: Dropdown data not loading
**Solution**: 
1. Check route name is correct
2. Verify user has access to parent module
3. Check browser console for AJAX error details

### Problem: Image not displaying
**Solution**: Verify route name includes `.image` suffix and is in ignore_routes

---

## Related Files

- Route definitions: `routes/tenant_web.php`
- Permission config: `config/permission.php`
- Middleware: `app/Http/Middleware/RouteNamePermissionsMiddleware.php`
- Permission seeds: `database/seeders/PermissionsSeeder.php`

---

**Last Updated**: December 9, 2025
**Version**: 1.0
**Status**: Documentation Only (Routes in Ignore List for Now)
