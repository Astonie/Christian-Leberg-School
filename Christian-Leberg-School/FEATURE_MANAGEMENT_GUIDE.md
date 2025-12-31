# Multi-School Feature Management System

## Overview

This system allows a **Super Admin** to control which features are enabled for each school deployment. This is perfect for scenarios where the same codebase is deployed to multiple schools, but each school has different requirements.

## Key Features

### 1. **Super Admin Role**
- New role with complete system control
- Only role that can manage feature toggles
- Has all permissions that Admin role has, plus:
  - `manage-features`: Enable/disable system features
  - `view-feature-settings`: View feature configuration

### 2. **Feature Toggle System**
Located at: **Admin → Feature Management** (Super Admins only)

#### Available Features:
- **Portal Features** (Core school management):
  - School Portal (students, teachers, exams, results)
  - Announcements
  
- **Website Features** (Public-facing CMS):
  - Public Website (entire CMS system)
  - CMS Pages
  - CMS Posts/News
  - CMS Events
  - CMS Photo Albums
  - CMS Menu Management
  
- **Academic Features**:
  - Timetable Management
  - Attendance Tracking

### 3. **How It Works**

#### For Super Admins:
1. Login with super admin credentials
2. Navigate to **Feature Management** in the sidebar (red badge)
3. Toggle features on/off per school needs
4. Use bulk actions to enable/disable multiple features
5. Changes take effect immediately

#### For Regular Admins:
- Cannot see or access Feature Management
- Can only use features that are enabled by Super Admin
- CMS menu automatically hidden if website feature is disabled

#### For End Users:
- Only see enabled features in navigation
- Attempting to access disabled features returns 403 error
- No indication of disabled features (clean UX)

## Use Cases

### Scenario 1: Portal-Only School
**Requirements**: No website needed, only management portal

**Super Admin Actions**:
1. Disable "Public Website" feature
2. All CMS features automatically hidden
3. Website routes return 403
4. Clean portal-only interface

### Scenario 2: Full Featured School
**Requirements**: Website + Portal + All features

**Super Admin Actions**:
1. Keep all features enabled (default)
2. Full system access for all users

### Scenario 3: Minimal Academic Features
**Requirements**: Basic portal without timetables or attendance

**Super Admin Actions**:
1. Enable Portal
2. Disable "Timetable Management"
3. Disable "Attendance Tracking"
4. Streamlined academic interface

## Technical Implementation

### Database
```sql
feature_toggles
├── id
├── key (unique)
├── name
├── description
├── is_enabled (boolean)
├── category (general|academic|cms|portal)
├── sort_order
└── timestamps
```

### Code Usage

#### In Controllers/Routes:
```php
// Protect routes with middleware
Route::middleware(['auth', 'feature:website'])->group(function () {
    // Website routes
});

Route::middleware(['auth', 'feature:timetable'])->group(function () {
    // Timetable routes
});
```

#### In Blade Views:
```blade
@if(feature_enabled('website'))
    <!-- CMS menu items -->
@endif

@if(feature_enabled('timetable'))
    <!-- Timetable link -->
@endif
```

#### In PHP Code:
```php
use App\Models\FeatureToggle;

if (FeatureToggle::isEnabled('website')) {
    // Execute website logic
}

// Or use helper function
if (feature_enabled('attendance')) {
    // Execute attendance logic
}
```

### Caching
- Features are cached for 1 hour
- Cache automatically cleared when features are toggled
- Manual clear: `FeatureToggle::clearCache()`

## Security

### Access Control
- **Super Admin**: Full feature management access
- **Admin**: Can use features, cannot manage them
- **Other Roles**: Limited by both role permissions AND enabled features

### Route Protection
All feature-controlled routes are protected with:
1. Authentication middleware
2. Role middleware (where applicable)
3. Feature middleware (new)

### Database Protection
- Feature keys are unique and validated
- Only Super Admins can modify via UI
- Direct database edits possible for deployment

## Deployment Guide

### Initial Setup
1. **Create Super Admin**:
   ```bash
   php artisan db:seed --class=SuperAdminSeeder
   ```
   - Email: `superadmin@school.com`
   - Password: `password` (change immediately!)

2. **Run Migrations**:
   ```bash
   php artisan migrate
   ```
   Creates `feature_toggles` table with default features

3. **Seed Permissions**:
   ```bash
   php artisan db:seed --class=PermissionSeeder
   ```
   Adds feature management permissions

### Per-School Configuration
1. Login as Super Admin
2. Navigate to Feature Management
3. Disable unwanted features for that school
4. Test with regular admin account

### Default Configuration
All features are **ENABLED** by default. This ensures backward compatibility and allows Super Admin to disable features as needed rather than having to enable them.

## API Integration (Future)

Feature checks can be exposed via API:
```json
GET /api/features
{
  "website": true,
  "portal": true,
  "timetable": false,
  "attendance": false
}
```

## Troubleshooting

### Feature not showing after enabling
- Clear browser cache
- Clear Laravel cache: `php artisan cache:clear`
- Check user role has permission for that feature

### Cannot access Feature Management
- Verify user has `super-admin` role (not just `admin`)
- Check `role_id` in users table matches super-admin role ID
- Re-seed permissions if needed

### Website still showing when disabled
- Clear feature cache: `FeatureToggle::clearCache()`
- Check middleware is applied to routes
- Verify `feature:website` middleware in web.php

## Files Modified/Created

### New Files:
- `app/Models/FeatureToggle.php`
- `app/Http/Controllers/FeatureToggleController.php`
- `app/Http/Middleware/CheckFeature.php`
- `resources/views/admin/features/index.blade.php`
- `database/migrations/2025_12_31_100000_create_feature_toggles_table.php`
- `database/seeders/SuperAdminSeeder.php`

### Modified Files:
- `database/seeders/RoleSeeder.php` (added super-admin role)
- `database/seeders/PermissionSeeder.php` (added feature permissions)
- `app/Models/User.php` (added `isSuperAdmin()` method)
- `app/helpers.php` (added `feature_enabled()` helper)
- `bootstrap/app.php` (registered feature middleware)
- `routes/web.php` (added feature protection)
- `resources/views/layouts/sidebar.blade.php` (conditional menus)

## Best Practices

1. **Always test as Super Admin first** before disabling features
2. **Document feature requirements** for each school deployment
3. **Use bulk actions** when configuring multiple features
4. **Monitor cache** performance in production
5. **Backup database** before making bulk feature changes
6. **Change default Super Admin password** immediately

## Support

For issues or questions:
1. Check feature toggle status in database
2. Verify role and permission assignments
3. Check Laravel logs for middleware errors
4. Clear all caches (app, route, config, view)

---

**System Version**: 1.0  
**Last Updated**: December 31, 2025  
**Compatibility**: Laravel 12.0+
