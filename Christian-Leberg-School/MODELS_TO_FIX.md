# Remaining Models to Fix - Mass Assignment Protection

This document lists all models that still need mass assignment protection applied.

## 📋 Models Requiring Updates

### Core System Models
- [ ] `Guardian.php` - Line 14: `protected $guarded = [];`
- [ ] `Stream.php` - Line 15: `protected $guarded = [];`
- [ ] `SchoolClass.php` - Line 13: `protected $guarded = [];`
- [ ] `Subject.php` - Line 14: `protected $guarded = [];`
- [ ] `Role.php` - Line 13: `protected $guarded = [];`
- [ ] `Permission.php` - Line 9: `protected $guarded = [];`

### Academic Models
- [ ] `AcademicYear.php`
- [ ] `Term.php` - Line 11: `protected $guarded = [];`
- [ ] `AssessmentStructure.php`
- [ ] `AssessmentComponent.php`
- [ ] `GradingSystem.php` - Line 11: `protected $guarded = [];`
- [ ] `GradingScale.php` - Line 11: `protected $guarded = [];`
- [ ] `ExamType.php` - Line 9: `protected $guarded = [];`
- [ ] `ExamMark.php` - Line 9: `protected $guarded = [];`
- [ ] `FinalResult.php` - Line 11: `protected $guarded = [];`
- [ ] `StudentScore.php` - Line 11: `protected $guarded = [];`

### Attendance & Audit
- [ ] `AttendanceRecord.php` - Line 9: `protected $guarded = [];`
- [ ] `AuditLog.php` - Line 9: `protected $guarded = [];`

### Timetable
- [ ] `TimetablePeriod.php` - Line 9: `protected $guarded = [];`
- [ ] `TimetableEntry.php` - Line 9: `protected $guarded = [];`

### CMS Models (Already Have $fillable - Review for Completeness)
- [x] `Event.php` - Line 16: `protected $fillable = [...]` ✅
- [x] `Menu.php` - Line 15: `protected $fillable = [...]` ✅
- [x] `Page.php` - Line 16: `protected $fillable = [...]` ✅
- [x] `Post.php` - Line 16: `protected $fillable = [...]` ✅
- [x] `Setting.php` - Line 14: `protected $fillable = [...]` ✅
- [x] `Tag.php` - Line 15: `protected $fillable = [...]` ✅
- [x] `MenuItem.php` - Line 14: `protected $fillable = [...]` ✅
- [x] `Media.php` - Line 16: `protected $fillable = [...]` ✅
- [x] `Category.php` - Line 15: `protected $fillable = [...]` ✅
- [x] `Album.php` - Line 15: `protected $fillable = [...]` ✅

**CMS Models:** Should also add `$guarded` array for extra protection even with `$fillable`

---

## 🔧 Fix Template

For each model, replace:
```php
protected $guarded = [];
```

With:
```php
/**
 * The attributes that are mass assignable.
 *
 * @var array<string>
 */
protected $fillable = [
    // List ONLY fields that should be mass-assignable
    // Review create/update logic to determine these
];

/**
 * The attributes that are not mass assignable.
 *
 * @var array<string>
 */
protected $guarded = [
    'id',
    // Any sensitive fields that should NEVER be mass-assigned
    'created_at',
    'updated_at',
    'deleted_at', // If using SoftDeletes
];
```

---

## 📖 Model-Specific Recommendations

### Guardian.php
```php
protected $fillable = [
    'user_id',
    'first_name',
    'last_name',
    'phone',
    'email',
    'address',
    'relationship',
    'occupation',
];

protected $guarded = [
    'id',
    'created_at',
    'updated_at',
    'deleted_at',
];
```

### Stream.php
```php
protected $fillable = [
    'name',
    'class_id',
    'capacity',
    'description',
];

protected $guarded = [
    'id',
    'created_at',
    'updated_at',
    'deleted_at',
];
```

### SchoolClass.php
```php
protected $fillable = [
    'name',
    'level',
    'description',
];

protected $guarded = [
    'id',
    'created_at',
    'updated_at',
    'deleted_at',
];
```

### Subject.php
```php
protected $fillable = [
    'name',
    'code',
    'description',
    'max_score',
    'pass_mark',
];

protected $guarded = [
    'id',
    'created_at',
    'updated_at',
    'deleted_at',
];
```

### Role.php
```php
protected $fillable = [
    'name',
    'slug',
    'description',
];

protected $guarded = [
    'id',
    'created_at',
    'updated_at',
];
```

### Permission.php
```php
protected $fillable = [
    'name',
    'slug',
    'description',
];

protected $guarded = [
    'id',
    'created_at',
    'updated_at',
];
```

### AttendanceRecord.php
```php
protected $fillable = [
    'student_id',
    'date',
    'status',
    'remarks',
];

protected $guarded = [
    'id',
    'recorded_by', // Prevent attribution manipulation
    'created_at',
    'updated_at',
];
```

### AuditLog.php
```php
protected $fillable = [];  // Audit logs should NEVER be mass-assigned

protected $guarded = [
    'id',
    'user_id',
    'action',
    'model_type',
    'model_id',
    'old_values',
    'new_values',
    'ip_address',
    'user_agent',
    'created_at',
    'updated_at',
];
```

### AcademicYear.php
```php
protected $fillable = [
    'name',
    'start_date',
    'end_date',
];

protected $guarded = [
    'id',
    'is_active',  // Prevent unauthorized activation
    'created_at',
    'updated_at',
];
```

### Term.php
```php
protected $fillable = [
    'name',
    'academic_year_id',
    'start_date',
    'end_date',
];

protected $guarded = [
    'id',
    'created_at',
    'updated_at',
];
```

---

## ⏱️ Estimated Time

- **Per Model:** 3-5 minutes
- **20 models:** 1-2 hours total
- **Testing:** 30 minutes
- **Total:** 1.5-2.5 hours

---

## ✅ Verification Steps

After fixing each model:

1. **Compile check:**
   ```bash
   php artisan optimize:clear
   php artisan config:cache
   ```

2. **Test key operations:**
   - Create new record
   - Update existing record
   - Verify sensitive fields cannot be mass-assigned

3. **Run tests:**
   ```bash
   php artisan test
   ```

---

## 🚨 Common Pitfalls to Avoid

1. **Don't leave both empty:**
   ```php
   // BAD - No protection!
   protected $fillable = [];
   protected $guarded = [];
   ```

2. **Don't use $fillable with sensitive fields:**
   ```php
   // BAD - Allows role_id to be mass-assigned
   protected $fillable = ['name', 'email', 'role_id'];
   ```

3. **Remember timestamps:**
   Always guard `created_at`, `updated_at`, `deleted_at`

4. **Review controller logic:**
   If you get "not fillable" errors after the fix, that field should probably be set explicitly:
   ```php
   // Instead of: $user->update($request->all());
   $user->update($request->validated());
   $user->role_id = $request->role_id; // Set explicitly with authorization check
   $user->save();
   ```

---

**Status:** 4 of 24 models completed (User, Student, Teacher, Exam, ExamResult)  
**Remaining:** 20 models  
**Priority:** HIGH - Complete before production deployment
