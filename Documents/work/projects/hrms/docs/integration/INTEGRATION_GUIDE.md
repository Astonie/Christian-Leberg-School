# Backend-Frontend Integration Guide

## Quick Start: Connecting Frontend to Backend

### 1. Verify Backend is Running

```powershell
cd "c:\Users\THINKPAD -T15\Documents\hrms\backend"
php artisan serve
```

Expected output: `Server started at http://127.0.0.1:8000`

---

### 2. Frontend API Configuration

Your frontend is already configured with the axios interceptor in `frontend/src/utils/api.js`.

**Verify the base URL:**
```javascript
// frontend/src/utils/api.js
const API_BASE_URL = process.env.REACT_APP_API_BASE_URL || 'http://localhost:8000/api';
```

**Environment file (.env):**
```
REACT_APP_API_BASE_URL=http://localhost:8000/api
```

---

### 3. Test Backend Endpoints

#### Test Training Programs API:

**Using PowerShell:**
```powershell
# Get all programs
Invoke-WebRequest -Uri "http://localhost:8000/api/training-programs" -Headers @{"Accept"="application/json"} | Select-Object -ExpandProperty Content

# Get statistics
Invoke-WebRequest -Uri "http://localhost:8000/api/training-programs/statistics" -Headers @{"Accept"="application/json"} | Select-Object -ExpandProperty Content
```

**Using curl (if installed):**
```bash
# Get all programs
curl -X GET "http://localhost:8000/api/training-programs" -H "Accept: application/json"

# Get statistics
curl -X GET "http://localhost:8000/api/training-programs/statistics" -H "Accept: application/json"
```

---

### 4. Add Missing API Routes

**File:** `backend/routes/api.php`

Add these routes for the new endpoints:

```php
use App\Http\Controllers\Api\TrainingProgramController;
use App\Http\Controllers\Api\TrainingSessionController;
use App\Http\Controllers\Api\EmployeeTrainingController;

Route::middleware(['auth:sanctum'])->group(function () {
    
    // Training Programs
    Route::prefix('training-programs')->group(function () {
        Route::get('/', [TrainingProgramController::class, 'index']);
        Route::post('/', [TrainingProgramController::class, 'store']);
        Route::get('/statistics', [TrainingProgramController::class, 'statistics']);
        Route::get('/{id}', [TrainingProgramController::class, 'show']);
        Route::put('/{id}', [TrainingProgramController::class, 'update']);
        Route::delete('/{id}', [TrainingProgramController::class, 'destroy']);
        Route::post('/{id}/publish', [TrainingProgramController::class, 'publish']);
        Route::post('/{id}/archive', [TrainingProgramController::class, 'archive']);
    });

    // Training Sessions
    Route::prefix('training-sessions')->group(function () {
        Route::get('/', [TrainingSessionController::class, 'index']);
        Route::post('/', [TrainingSessionController::class, 'store']);
        Route::get('/statistics', [TrainingSessionController::class, 'statistics']);
        Route::get('/{id}', [TrainingSessionController::class, 'show']);
        Route::put('/{id}', [TrainingSessionController::class, 'update']);
        Route::delete('/{id}', [TrainingSessionController::class, 'destroy']);
        Route::post('/{id}/cancel', [TrainingSessionController::class, 'cancel']);
        Route::post('/{id}/start', [TrainingSessionController::class, 'start']);
        Route::post('/{id}/complete', [TrainingSessionController::class, 'complete']);
    });

    // Employee Trainings
    Route::prefix('employee-trainings')->group(function () {
        Route::get('/', [EmployeeTrainingController::class, 'index']);
        Route::post('/', [EmployeeTrainingController::class, 'store']);
        Route::get('/{id}', [EmployeeTrainingController::class, 'show']);
        Route::put('/{id}', [EmployeeTrainingController::class, 'update']);
        Route::delete('/{id}', [EmployeeTrainingController::class, 'destroy']);
        Route::post('/{id}/attendance', [EmployeeTrainingController::class, 'markAttendance']);
        Route::post('/{id}/complete', [EmployeeTrainingController::class, 'complete']);
        Route::post('/bulk-enroll', [EmployeeTrainingController::class, 'bulkEnroll']);
    });

    // Employee Training Stats
    Route::get('/employees/{id}/training-statistics', [EmployeeTrainingController::class, 'employeeStatistics']);
    Route::get('/employees/{id}/upcoming-trainings', [EmployeeTrainingController::class, 'upcomingTrainings']);
});
```

---

### 5. Frontend API Service Updates

Your `TrainingProgramList.jsx` already uses the correct API calls:

```javascript
// Fetch programs
const response = await api.get('/training-programs', {
  params: {
    status: filters.status,
    category: filters.category,
    search: searchText,
    sortBy: sorter.field,
    sortOrder: sorter.order === 'ascend' ? 'asc' : 'desc',
    per_page: pagination.pageSize
  }
});

// Fetch statistics  
const statsResponse = await api.get('/training-programs/statistics');
```

---

### 6. Testing the Integration

#### Step-by-Step Test:

1. **Start Backend:**
```powershell
cd backend
php artisan serve
```

2. **Start Frontend:**
```powershell
cd frontend
npm start
```

3. **Navigate to Training Module:**
   - Go to `http://localhost:3000/training/programs`

4. **Expected Behavior:**
   - ✅ Statistics cards populate with data
   - ✅ Programs table loads
   - ✅ Filters work (status, category, search)
   - ✅ Create/Edit/Delete work
   - ✅ Excel export works

#### Troubleshooting:

**Issue:** 401 Unauthorized
- **Solution:** Ensure you're logged in and token is being sent
- **Check:** Browser DevTools → Network → Request Headers → Authorization

**Issue:** 404 Not Found
- **Solution:** Verify routes are registered in `api.php`
- **Check:** Run `php artisan route:list | Select-String training`

**Issue:** CORS errors
- **Solution:** Update `backend/config/cors.php`
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:3000'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'supports_credentials' => true,
```

**Issue:** 500 Server Error
- **Solution:** Check Laravel logs
```powershell
Get-Content backend/storage/logs/laravel.log -Tail 50
```

---

### 7. Database Seeding (Optional)

Create seed data for testing:

```powershell
cd backend
php artisan tinker
```

```php
// Create test training program
App\Models\TrainingProgram::create([
    'title' => 'React Advanced Techniques',
    'description' => 'Learn advanced React patterns and best practices',
    'category' => 'Technical',
    'status' => 'active',
    'duration_days' => 5,
    'max_participants' => 20,
    'budget' => 5000.00,
    'is_mandatory' => true,
    'created_by' => 1 // Replace with valid user ID
]);

// Create test training session
App\Models\TrainingSession::create([
    'program_id' => 1,
    'trainer_id' => 1, // Replace with valid user ID
    'start_time' => now()->addDays(7),
    'end_time' => now()->addDays(7)->addHours(4),
    'location' => 'Training Room A',
    'status' => 'scheduled',
    'max_attendees' => 20,
    'current_attendees' => 0
]);
```

---

### 8. API Response Format

**All endpoints return consistent format:**

**Success:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "React Training",
    ...
  },
  "message": "Operation successful" // Optional
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error (debug mode only)"
}
```

**Paginated:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [...],
    "first_page_url": "...",
    "last_page": 5,
    "per_page": 15,
    "total": 67
  }
}
```

---

### 9. Frontend Data Handling

**Update your components to handle pagination:**

```javascript
const fetchPrograms = async () => {
  try {
    const response = await api.get('/training-programs', { params });
    
    // Backend now returns paginated data
    const { data, total, current_page, per_page } = response.data.data;
    
    setPrograms(Array.isArray(data) ? data : []);
    setPagination(prev => ({
      ...prev,
      current: current_page,
      total: total,
      pageSize: per_page
    }));
  } catch (error) {
    message.error('Failed to fetch programs');
  }
};
```

---

### 10. Performance Optimization

**Backend:**
- ✅ Eager loading relationships (with('creator', 'sessions'))
- ✅ Database indexes on foreign keys
- ✅ Query pagination (15 items per page)
- ✅ Selective field loading

**Frontend:**
- ✅ Debounced search (already implemented)
- ✅ Pagination (already implemented)
- ✅ Loading states (already implemented)
- ✅ Error boundaries

---

### 11. Security Checklist

- ✅ Sanctum authentication middleware
- ✅ CSRF token protection
- ✅ Input validation (FormRequests)
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS prevention (Laravel escaping)
- ✅ Authorization checks (in controllers)

**Add authorization:**
```php
// In controller
public function destroy($id)
{
    $program = TrainingProgram::findOrFail($id);
    
    // Only creator or admin can delete
    if ($program->created_by !== auth()->id() && !auth()->user()->isAdmin()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }
    
    // ... rest of delete logic
}
```

---

### 12. Monitoring & Debugging

**Enable Query Logging (in development):**

```php
// In AppServiceProvider boot()
if (app()->environment('local')) {
    \DB::listen(function ($query) {
        \Log::info('Query: ' . $query->sql, [
            'bindings' => $query->bindings,
            'time' => $query->time
        ]);
    });
}
```

**Frontend API Monitoring:**

```javascript
// In api.js, add response interceptor
api.interceptors.response.use(
  response => {
    console.log(`✅ ${response.config.method.toUpperCase()} ${response.config.url}`, response.data);
    return response;
  },
  error => {
    console.error(`❌ ${error.config?.method?.toUpperCase()} ${error.config?.url}`, error.response?.data);
    return Promise.reject(error);
  }
);
```

---

### 13. Next Steps

1. ✅ **Backend is ready** - Services, models, migrations complete
2. ⏳ **Add API routes** - Update `routes/api.php` with new endpoints
3. ⏳ **Test endpoints** - Use Postman or curl to verify
4. ⏳ **Update frontend** - Ensure pagination handling
5. ⏳ **Integration test** - Full end-to-end testing
6. ⏳ **Deploy** - Set up production environment

---

### 14. Quick Commands Reference

```powershell
# Start backend
cd backend; php artisan serve

# Start frontend  
cd frontend; npm start

# Run migrations
cd backend; php artisan migrate

# Clear cache
cd backend; php artisan cache:clear; php artisan config:clear

# View routes
cd backend; php artisan route:list | Select-String training

# View logs
Get-Content backend/storage/logs/laravel.log -Tail 50 -Wait

# Database console
cd backend; php artisan tinker
```

---

**Integration Status:** ✅ Backend Ready | ⏳ Routes Pending | ⏳ Testing Pending  
**Document Version:** 1.0  
**Last Updated:** 2025-01-16
