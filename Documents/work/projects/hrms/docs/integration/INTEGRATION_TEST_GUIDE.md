# Backend-Frontend Integration Test Guide

## Quick Test Checklist

### Prerequisites
1. ✅ Backend server running: `cd backend && php artisan serve`
2. ✅ Frontend server running: `cd frontend && npm start`
3. ✅ Database migrated: `php artisan migrate`
4. ✅ User logged in to get auth token

---

## Step 1: Start Backend Server

```powershell
cd "c:\Users\THINKPAD -T15\Documents\hrms\backend"
php artisan serve
```

Expected output: `Server started on http://127.0.0.1:8000`

---

## Step 2: Test Backend API Endpoints

### A. Test Training Programs Endpoint

**Using PowerShell (without authentication):**
```powershell
$response = Invoke-WebRequest -Uri "http://localhost:8000/api/v1/training-programs" -Method GET -Headers @{
    "Accept" = "application/json"
    "Content-Type" = "application/json"
}
$response.Content | ConvertFrom-Json
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [],
    "total": 0
  }
}
```

### B. Test Statistics Endpoint

```powershell
$response = Invoke-WebRequest -Uri "http://localhost:8000/api/v1/training-programs/statistics" -Method GET -Headers @{
    "Accept" = "application/json"
}
$response.Content | ConvertFrom-Json
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "total": 0,
    "active": 0,
    "completed": 0,
    "draft": 0,
    "by_category": {},
    "mandatory": 0,
    "optional": 0
  }
}
```

---

## Step 3: Create Test Data

### Option A: Using Tinker

```powershell
cd "c:\Users\THINKPAD -T15\Documents\hrms\backend"
php artisan tinker
```

```php
// Create a test user if needed
$user = App\Models\User::first();

// Create test training program
$program = App\Models\TrainingProgram::create([
    'title' => 'React Best Practices',
    'description' => 'Learn advanced React patterns and best practices for building scalable applications',
    'category' => 'Technical',
    'status' => 'active',
    'duration_days' => 5,
    'max_participants' => 20,
    'budget' => 5000.00,
    'is_mandatory' => true,
    'created_by' => $user->id
]);

// Create test training session
$session = App\Models\TrainingSession::create([
    'program_id' => $program->id,
    'trainer_id' => $user->id,
    'start_time' => now()->addDays(7),
    'end_time' => now()->addDays(7)->addHours(4),
    'location' => 'Training Room A',
    'status' => 'scheduled',
    'max_attendees' => 20,
    'current_attendees' => 0
]);

// Verify
App\Models\TrainingProgram::count();
App\Models\TrainingSession::count();
```

### Option B: Using SQL

```sql
-- Get a user ID first
SELECT id FROM users LIMIT 1;

-- Insert test program (replace 1 with actual user ID)
INSERT INTO training_programs (title, description, category, status, duration_days, max_participants, budget, is_mandatory, created_by, created_at, updated_at) 
VALUES ('React Best Practices', 'Learn advanced React patterns', 'Technical', 'active', 5, 20, 5000.00, true, 1, NOW(), NOW());
```

---

## Step 4: Test Frontend Integration

### A. Start Frontend

```powershell
cd "c:\Users\THINKPAD -T15\Documents\hrms\frontend"
npm start
```

### B. Login to Application
1. Navigate to `http://localhost:3000`
2. Login with your credentials
3. Navigate to Training module

### C. Verify Functionality

**Training Programs List:**
- [ ] Statistics cards display correct counts
- [ ] Table loads without errors
- [ ] Search functionality works
- [ ] Category filter works
- [ ] Status filter works
- [ ] Create button opens modal
- [ ] Edit button opens modal with data
- [ ] Delete button shows confirmation
- [ ] Excel export works

**Create/Edit Form:**
- [ ] Form submits successfully
- [ ] Validation errors display
- [ ] Success message shows
- [ ] Table refreshes with new data

---

## Step 5: Troubleshooting

### Error: 401 Unauthorized

**Symptom:** API calls return 401 Unauthorized

**Solution:**
1. Ensure you're logged in
2. Check browser console for auth token
3. Verify api.js interceptor is working:

```javascript
// In browser console
localStorage.getItem('token')
```

If no token, login again.

### Error: 404 Not Found

**Symptom:** API endpoint returns 404

**Solution:**
1. Clear route cache:
```powershell
cd backend
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

2. Verify route exists:
```powershell
php artisan route:list --path=v1/training
```

### Error: CORS Issues

**Symptom:** Browser shows CORS error

**Solution:**
Edit `backend/config/cors.php`:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:3000'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'supports_credentials' => true,
```

Then clear config:
```powershell
php artisan config:clear
```

### Error: 500 Internal Server Error

**Symptom:** API returns 500 error

**Solution:**
1. Check Laravel logs:
```powershell
Get-Content backend/storage/logs/laravel.log -Tail 50
```

2. Enable debug mode in `.env`:
```
APP_DEBUG=true
```

3. Common fixes:
   - Missing database fields: `php artisan migrate`
   - Wrong relationships in models
   - Service class not found: Check namespace

### Frontend Not Updating

**Symptom:** Changes in backend don't reflect in frontend

**Solution:**
1. Hard refresh browser: `Ctrl + Shift + R`
2. Clear browser cache
3. Check Network tab in DevTools
4. Verify API response format matches frontend expectations

---

## Step 6: API Response Format Validation

### Expected Frontend Data Structure

**Training Programs:**
```javascript
{
  success: true,
  data: {
    current_page: 1,
    data: [
      {
        id: 1,
        title: "React Training",
        description: "Learn React",
        category: "Technical",
        status: "active",
        duration_days: 5,
        max_participants: 20,
        budget: "5000.00",
        is_mandatory: true,
        created_by: 1,
        created_at: "2025-10-16T...",
        updated_at: "2025-10-16T...",
        creator: {
          id: 1,
          name: "Admin User",
          email: "admin@example.com"
        },
        sessions: []
      }
    ],
    total: 1,
    per_page: 15
  }
}
```

### Frontend Expects (in TrainingProgramList.jsx line 28-30):
```javascript
const data = Array.isArray(res.data) ? res.data : (res.data?.data || []);
```

This handles both:
- Direct array: `res.data = [...]`
- Paginated: `res.data.data = [...]`

---

## Step 7: Test All CRUD Operations

### Create Program
```javascript
// Should work in browser console (after login)
fetch('http://localhost:8000/api/v1/training-programs', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    title: 'Test Program',
    description: 'This is a test program',
    category: 'Technical',
    status: 'draft'
  })
})
.then(r => r.json())
.then(console.log);
```

### Read Programs
```javascript
fetch('http://localhost:8000/api/v1/training-programs', {
  headers: {
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'Accept': 'application/json'
  }
})
.then(r => r.json())
.then(console.log);
```

### Update Program
```javascript
fetch('http://localhost:8000/api/v1/training-programs/1', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    title: 'Updated Program',
    description: 'Updated description',
    category: 'Technical',
    status: 'active'
  })
})
.then(r => r.json())
.then(console.log);
```

### Delete Program
```javascript
fetch('http://localhost:8000/api/v1/training-programs/1', {
  method: 'DELETE',
  headers: {
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'Accept': 'application/json'
  }
})
.then(r => r.json())
.then(console.log);
```

---

## Success Criteria

✅ All checkboxes in Step 4C are checked
✅ No console errors in browser
✅ Backend logs show successful requests
✅ Statistics update correctly
✅ CRUD operations work end-to-end
✅ Filters and search work
✅ Excel export downloads file

---

## Next Steps After Integration Success

1. Test Training Sessions module
2. Test Employee Trainings module
3. Test Training Dashboard
4. Run frontend tests: `npm test`
5. Create production build: `npm run build`

---

**Document Version:** 1.0  
**Last Updated:** 2025-10-16  
**Status:** Ready for Testing
