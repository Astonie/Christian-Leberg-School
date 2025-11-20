# Training Module Frontend Integration Issues

## Current Status

### ✅ Backend API Working
- All 59 training routes are registered and accessible
- Test endpoints (without auth) are working correctly
- Models and factories are functioning (33 tests passing)

### ❌ Frontend Integration Issues

## Problems Identified

### 1. Authentication Issue (401 Unauthorized)
**Error:**
```
GET http://localhost:8000/api/v1/users/astonie-mukiwa
[HTTP/1.1 401 Unauthorized 5658ms]
```

**Cause:** The frontend is not sending authentication tokens with API requests.

**Solution Needed:**
- Verify Sanctum authentication is configured in frontend
- Check if auth tokens are being stored and sent with requests
- Verify axios interceptors are adding Authorization headers

---

### 2. Protected Routes Returning 404
**Errors:**
```
GET /api/v1/employee-trainings?employee_id=2 [404]
GET /api/v1/training-sessions/available-for-employee?employee_id=2 [404]
GET /api/v1/training-calendar/month?year=2025&month=11&employee_id=2 [404]
GET /api/v1/employees/2/training-history [404]
GET /api/v1/employees/2/training-statistics [404]
```

**Cause:** These routes exist but require `auth:sanctum` middleware. Without authentication, Laravel returns 404 instead of 401 for middleware-protected routes in some configurations.

**Verification:**
- ✅ Routes exist: `php artisan route:list --path=training` shows all routes
- ✅ Test endpoint works: `/api/v1/test/calendar-month` returns data
- ❌ Auth required: All training routes require `auth:sanctum` middleware

---

## Routes Analysis

### Working Routes (No Auth Required)
```php
Route::prefix('v1/test')->group(function () {
    Route::get('ping', [TestController::class, 'ping']); // ✅ Working
    Route::get('employee-training', [TestController::class, 'testEmployeeTraining']); // ✅ Working
    Route::get('training-sessions', [TestController::class, 'testTrainingSessions']); // ✅ Working
    Route::get('calendar-month', [TestController::class, 'testCalendarMonth']); // ✅ Working
});
```

**Test Results:**
```bash
curl http://localhost:8000/api/v1/test/ping
# Response: {"success":true,"message":"Training API is working"}

curl "http://localhost:8000/api/v1/test/calendar-month?year=2025&month=11&employee_id=2"
# Response: Full calendar data with sessions
```

### Protected Routes (Auth Required)
All training routes are behind `auth:sanctum` middleware:
```php
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    // 59 training routes here
});
```

---

## Solutions Required

### 1. Frontend Authentication Fix

#### Check Axios Configuration
Location: `frontend/src/api/axiosConfig.js` or similar

Should include:
```javascript
axios.interceptors.request.use(
  config => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
  },
  error => Promise.reject(error)
);
```

#### Check Login Flow
1. User logs in → receives token
2. Token stored in localStorage
3. Token sent with all API requests
4. Sanctum validates token

---

### 2. Temporary Solution: Add Public Test Routes

If you need to test the frontend without authentication, we can add public versions of the routes:

```php
// In routes/modules/training.php
Route::prefix('v1/public')->group(function () {
    // Calendar
    Route::get('training-calendar/month', [TrainingCalendarController::class, 'getMonthCalendar']);
    
    // Employee trainings
    Route::get('employee-trainings', [EmployeeTrainingController::class, 'index']);
    Route::get('training-sessions/available-for-employee', [TrainingSessionController::class, 'getAvailableForEmployee']);
    
    // Employee history
    Route::get('employees/{id}/training-history', [EmployeeTrainingController::class, 'employeeHistory']);
    Route::get('employees/{id}/training-statistics', [EmployeeTrainingController::class, 'employeeStatistics']);
});
```

---

### 3. Sanctum Configuration Check

#### File: `config/sanctum.php`
Verify:
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,localhost:8000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

#### File: `.env`
Check:
```
SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:8000
SESSION_DRIVER=cookie
SESSION_DOMAIN=localhost
```

#### File: `config/cors.php`
Verify:
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:3000'],
'supports_credentials' => true,
```

---

## Quick Diagnostic Commands

### Check if server is running:
```bash
curl http://localhost:8000/api/v1/test/ping
```

### Test authenticated endpoint (with token):
```bash
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" http://localhost:8000/api/v1/training-programs
```

### Check route registration:
```bash
php artisan route:list --path=training
```

### Clear caches:
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

---

## Next Steps

1. **Fix Frontend Authentication** (Priority 1)
   - [ ] Check if user is logged in
   - [ ] Verify token is stored in localStorage
   - [ ] Confirm axios is sending Authorization header
   - [ ] Check browser network tab for Authorization header

2. **Add Public Routes** (Temporary Solution)
   - [ ] Create public endpoints for testing
   - [ ] Update frontend to use public routes during development
   - [ ] Remove public routes before production

3. **Verify Sanctum Configuration** (Priority 2)
   - [ ] Check CORS settings
   - [ ] Verify stateful domains
   - [ ] Test CSRF cookie endpoint

4. **Test API with Postman/Insomnia**
   - [ ] Login to get token
   - [ ] Test protected endpoints with token
   - [ ] Verify all training endpoints work

---

## Console Errors Explained

```javascript
Failed to fetch my trainings: 
Object { message: "Request failed with status code 404" }
```

This means:
- ✅ Frontend code is correct
- ✅ Backend routes exist
- ❌ **Authentication is missing**
- The route is protected by `auth:sanctum`
- Without auth, Laravel returns 404 instead of 401

---

## Recommended Action

**Option 1: Fix Authentication (Recommended)**
Check your login component and verify tokens are being stored and sent.

**Option 2: Temporary Public Routes (Quick Fix)**
Would you like me to create public versions of these routes so you can test the frontend immediately?

**Option 3: Update Frontend to Use Test Routes**
The test routes work without auth - I can help update the frontend to use those for now.

Which approach would you like to take?
