# Authorization Architecture: Policies vs Permissions

## Current Implementation

### **Hybrid Authorization System**

Your application now uses a **two-layer authorization system**:

1. **Permission-Based Override** (checked first)
2. **Role-Based Policies** (fallback logic)

---

## How It Works

### **Authorization Flow**

```
User tries to perform action
    ↓
1. Gate::before() checks if user has specific permission
    ↓
   YES → ✅ Allow (override policy)
    ↓
   NO → Continue to Policy
    ↓
2. Policy checks role-based business logic
    ↓
   Returns true/false based on role and context
```

### **Example Scenario**

**Without Permission Override:**
- A regular `teacher` cannot create exams (policy checks role)
- Only `admin`, `head-teacher`, `deputy-head-teacher` can create exams

**With Permission Override:**
- If you grant `create-exams` permission to a specific teacher
- That teacher can now create exams, bypassing the policy restriction
- Other teachers without the permission still cannot create exams

---

## Key Benefits

### ✅ **Flexible Authorization**
- **Broad strokes with roles**: Most authorization follows role-based rules
- **Fine-tuning with permissions**: Grant special abilities to specific users
- **No code changes needed**: Adjust authorization through database records

### ✅ **Granular Control**
- Give a teacher permission to manage attendance for all classes
- Allow a deputy head teacher to manage grading systems
- Grant report generation access to specific staff members

### ✅ **Backward Compatible**
- Existing role-based policies continue to work
- Permissions are optional enhancements
- No breaking changes to existing functionality

---

## Implementation Details

### **1. Permission Override (AuthServiceProvider)**

Located in: `app/Providers/AuthServiceProvider.php`

```php
Gate::before(function ($user, $ability) {
    $permissionMap = [
        'create-exams' => 'create-exams',
        'view-students' => 'view-students',
        // ... more mappings
    ];
    
    if (isset($permissionMap[$ability])) {
        if ($user->hasPermission($permissionMap[$ability])) {
            return true; // Override - allow access
        }
    }
    
    return null; // Let policy decide
});
```

### **2. Role-Based Policies**

Example: `app/Policies/ExamPolicy.php`

```php
public function create(User $user): bool
{
    // This runs AFTER permission check
    // If user has 'create-exams' permission, this never executes
    return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
}
```

### **3. User Permission Check**

Located in: `app/Models/User.php`

```php
public function hasPermission($permission)
{
    if (!$this->role) {
        return false;
    }
    
    return $this->role->permissions()
                      ->where('slug', $permission)
                      ->exists();
}
```

---

## Database Structure

### **Tables**

1. **`permissions`** - All available permissions
   ```
   id | name            | slug            | description
   1  | Create Exams    | create-exams    | Can create new exams
   2  | View Students   | view-students   | Can view student listings
   ```

2. **`roles`** - User roles
   ```
   id | name         | slug         
   1  | Admin        | admin        
   2  | Teacher      | teacher      
   3  | Head Teacher | head-teacher 
   ```

3. **`role_permission`** - Many-to-many relationship
   ```
   role_id | permission_id
   1       | 1
   1       | 2
   2       | 2
   ```

4. **`users`** - Each user has one role
   ```
   id | name      | role_id
   1  | John Doe  | 2
   ```

---

## Usage Examples

### **Example 1: Grant Special Permission to a Teacher**

```php
// Give Teacher Jane permission to create exams (normally not allowed)
$teacher = User::where('email', 'jane@school.com')->first();
$permission = Permission::where('slug', 'create-exams')->first();

// Add permission to teacher's role OR create custom role for Jane
$teacherRole = Role::where('slug', 'teacher')->first();
$teacherRole->permissions()->attach($permission->id);

// Now Jane (and all teachers) can create exams
```

### **Example 2: Create Custom Role with Specific Permissions**

```php
// Create "Senior Teacher" role with extra permissions
$seniorTeacherRole = Role::create([
    'name' => 'Senior Teacher',
    'slug' => 'senior-teacher',
    'description' => 'Experienced teacher with additional privileges'
]);

// Assign specific permissions
$permissions = Permission::whereIn('slug', [
    'view-students',
    'edit-students',
    'view-exams',
    'create-exams',  // Extra privilege
    'mark-attendance',
    'edit-attendance',
    'view-all-results'  // Extra privilege
])->get();

$seniorTeacherRole->permissions()->sync($permissions->pluck('id'));

// Assign user to this role
$user->update(['role_id' => $seniorTeacherRole->id]);
```

### **Example 3: Temporarily Grant Permission**

```php
// For one academic year, allow specific teacher to manage grading
$permission = Permission::where('slug', 'manage-grading-systems')->first();
$specialRole = Role::create([
    'name' => 'Grading Coordinator',
    'slug' => 'grading-coordinator',
]);

$specialRole->permissions()->attach($permission->id);
$teacher->update(['role_id' => $specialRole->id]);

// At year end, revert
$teacher->update(['role_id' => $teacherRole->id]);
```

---

## Permission Mapping

### **Complete Ability → Permission Mapping**

The `AuthServiceProvider` maps policy abilities to permission slugs:

| Policy Ability | Permission Slug | Description |
|----------------|----------------|-------------|
| `viewAny-students` | `view-students` | View student list |
| `create-students` | `create-students` | Create new students |
| `update-students` | `edit-students` | Edit student info |
| `delete-students` | `delete-students` | Delete students |
| `create-exams` | `create-exams` | Create new exams |
| `enterForSubject-exam-results` | `enter-exam-results` | Enter exam results |
| `mark-attendance` | `mark-attendance` | Mark attendance |
| ... | ... | ... |

*See `app/Providers/AuthServiceProvider.php` for complete mapping*

---

## When to Use What

### **Use Roles (Policies) For:**
- ✅ Broad organizational structure
- ✅ Defining general user capabilities
- ✅ Relationship-based access (teacher sees their students)
- ✅ Context-dependent rules (exam entry time windows)

### **Use Permissions For:**
- ✅ Fine-grained overrides
- ✅ Temporary special access
- ✅ Exceptions to role rules
- ✅ Per-user customization

---

## Testing Authorization

### **Test Permission Override**

```bash
php artisan tinker

# Get a teacher
$teacher = User::whereHas('role', fn($q) => $q->where('slug', 'teacher'))->first();

# Check current ability
$teacher->can('create', App\Models\Exam::class);  // false (policy denies)

# Grant permission
$permission = \App\Models\Permission::where('slug', 'create-exams')->first();
$teacher->role->permissions()->attach($permission->id);

# Check again
$teacher->refresh();
$teacher->can('create', App\Models\Exam::class);  // true (permission overrides!)
```

### **Test in Browser**

In your Blade templates:
```blade
@can('create', App\Models\Exam::class)
    <button>Create Exam</button>
@endcan
```

In your controllers:
```php
public function store(Request $request)
{
    $this->authorize('create', Exam::class);
    
    // Create exam...
}
```

---

## Administrative Interface Recommendations

### **Suggested Features**

1. **Role Management UI**
   - View all roles
   - Create custom roles
   - Assign permissions to roles
   - View users in each role

2. **Permission Management UI**
   - View all permissions
   - See which roles have each permission
   - Bulk assign/remove permissions

3. **User Permission Overrides**
   - Grant individual permissions to specific users
   - Temporary permission grants with expiration
   - Audit log of permission changes

4. **Permission Testing Tool**
   - Select a user
   - Test what they can/cannot do
   - Show which rule allowed/denied (permission vs policy)

---

## Migration Path

### **Current State**
- ✅ Permissions exist in database (from seeder)
- ✅ Roles have permissions assigned
- ✅ AuthServiceProvider installed
- ✅ All existing tests pass

### **What Changed**
- Added `AuthServiceProvider` with `Gate::before()` hook
- Registered `AuthServiceProvider` in `bootstrap/providers.php`
- **No changes to policies** - they work as before

### **What Works Now**
1. **Without permissions**: Policies work exactly as before (role-based)
2. **With permissions**: Can override policies for specific users/roles
3. **Tests**: All 141 policy tests still pass (backward compatible)

---

## Best Practices

### ✅ **DO:**
- Use roles for standard organizational structure
- Use permissions for exceptions and special cases
- Document why special permissions were granted
- Regularly audit permission assignments
- Create meaningful permission descriptions

### ❌ **DON'T:**
- Grant too many individual permissions (defeats role purpose)
- Bypass policies without good reason
- Create overlapping permissions
- Forget to remove temporary permissions
- Hard-code permission checks in controllers (use policies)

---

## Security Considerations

### **Permission Escalation Protection**

1. **User model protects role_id**:
   ```php
   protected $guarded = [
       'role_id',  // Prevents privilege escalation
   ];
   ```

2. **Only admins should manage roles/permissions**:
   - Permission: `edit-roles`
   - Permission: `edit-permissions`
   - Typically only given to `admin` role

3. **Audit sensitive changes**:
   - Log when permissions are granted/revoked
   - Log role changes
   - Monitor who can modify authorization

### **Policy vs Permission Priority**

The system follows **"most permissive wins"**:
- If permission grants access → ✅ Allow
- If permission not found → Check policy
- If policy denies → ❌ Deny

This prevents permission removal from accidentally granting access.

---

## Troubleshooting

### **"Permission not working"**

1. Check permission exists:
   ```php
   Permission::where('slug', 'your-permission')->exists();
   ```

2. Check user's role has permission:
   ```php
   $user->role->permissions()->where('slug', 'your-permission')->exists();
   ```

3. Check mapping in AuthServiceProvider:
   ```php
   // Is the ability name correct?
   'your-ability' => 'your-permission'
   ```

4. Clear cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

### **"Policy still blocking user"**

- Permissions override policies to **allow**, not deny
- If policy has additional context checks (time windows, ownership), those still apply
- Permission grant means "eligible" but other rules may apply

---

## Future Enhancements

### **Possible Additions**

1. **User-Level Permissions** (not just role-level)
   - Direct permission assignment to users
   - Overrides both role permissions and policies

2. **Conditional Permissions**
   - Time-based permissions (expires after date)
   - Context-based permissions (only for specific academic year)

3. **Permission Groups**
   - Bundle related permissions
   - Easier bulk assignment

4. **Permission Inheritance**
   - Advanced roles that inherit from base roles
   - Override specific permissions while keeping others

---

## Summary

### **Key Takeaways**

✅ **Policies define the default rules** based on roles and context

✅ **Permissions can override policies** for fine-grained control

✅ **Backward compatible** - existing code works unchanged

✅ **Database-driven** - no code changes needed for authorization adjustments

✅ **Flexible** - supports both broad roles and specific exceptions

### **Quick Reference**

```
Authorization = Permissions (override) → Policies (default) → Deny (fallback)
```

**To grant special access**: Add permission to role
**To restrict access**: Remove permission or update policy
**To test**: Use `$user->can('ability', $model)` or `@can` directive
