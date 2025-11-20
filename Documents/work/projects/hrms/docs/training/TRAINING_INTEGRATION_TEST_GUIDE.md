# Integration Test Guide for Employee Training Module

## Overview
This guide provides comprehensive testing instructions for the employee training module integration between frontend and backend.

## Prerequisites
1. Backend server running on port 8000
2. Frontend server running on port 3000
3. Database seeded with test data
4. Valid authentication token

## Backend API Endpoints Testing

### 1. Employee Training Statistics
```bash
# Get employee dashboard statistics
curl -X GET "http://localhost:8000/api/v1/training-statistics/employee-dashboard?employee_id=EMP001" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Expected Response:
{
  "success": true,
  "data": {
    "overview": {
      "total_enrolled": 5,
      "completed_trainings": 3,
      "pending_approvals": 1,
      "upcoming_trainings": 2,
      "completion_rate": 60.0,
      "total_hours": 24,
      "certificates_earned": 2,
      "average_rating_given": 4.2
    },
    "monthly_activity": [...],
    "last_updated": "2024-01-15T10:30:00Z"
  }
}
```

### 2. Available Training Sessions
```bash
# Get available sessions for employee enrollment
curl -X GET "http://localhost:8000/api/v1/training-sessions/available-for-employee?employee_id=EMP001" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Expected Response:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "program": {
        "id": 1,
        "title": "Leadership Training",
        "category": "Management"
      },
      "start_time": "2024-02-15 09:00:00",
      "end_time": "2024-02-15 17:00:00",
      "location": "Conference Room A",
      "can_enroll": true,
      "is_enrollment_open": true,
      "available_seats": 5
    }
  ]
}
```

### 3. Employee Enrollments
```bash
# Get employee's current enrollments
curl -X GET "http://localhost:8000/api/v1/training-sessions/employee-enrollments?employee_id=EMP001" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Expected Response:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "enrollment_id": 5,
      "program": {
        "title": "Communication Skills",
        "category": "Soft Skills"
      },
      "start_time": "2024-01-20 14:00:00",
      "enrollment_status": "enrolled",
      "can_cancel": true,
      "feedback_submitted": false
    }
  ]
}
```

### 4. Training Calendar
```bash
# Get monthly calendar
curl -X GET "http://localhost:8000/api/v1/training-calendar/month?year=2024&month=1&employee_id=EMP001" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Expected Response:
{
  "success": true,
  "data": {
    "year": 2024,
    "month": 1,
    "sessions": {
      "2024-01-15": [
        {
          "id": 1,
          "title": "Safety Training",
          "start_time": "2024-01-15 09:00:00",
          "enrollment_status": "enrolled"
        }
      ]
    }
  }
}
```

### 5. Training History
```bash
# Get employee training history
curl -X GET "http://localhost:8000/api/v1/training-sessions/employee-history?employee_id=EMP001" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Expected Response:
{
  "success": true,
  "data": {
    "statistics": {
      "total_completed": 3,
      "total_hours": 24,
      "programs_completed": 2,
      "average_rating": 4.3,
      "certificates_earned": 2
    },
    "history": [...]
  }
}
```

## Frontend Component Testing

### 1. Employee Training Dashboard
**Location:** `frontend/src/views/client/training/EmployeeTrainingDashboard.jsx`

**Test Steps:**
1. Navigate to `/client/training`
2. Verify statistics cards display correct numbers
3. Check tab navigation works (My Trainings, Available, Calendar, History)
4. Verify overview stats match backend data
5. Test quick action buttons

**Expected Behavior:**
- Statistics load within 2 seconds
- All tabs are clickable and switch content
- Numbers reflect real enrollment data
- Loading states show during API calls

### 2. My Trainings Component
**Location:** `frontend/src/views/client/training/MyTrainings.jsx`

**Test Steps:**
1. Click "My Trainings" tab
2. Verify enrolled sessions are listed
3. Test expandable row details
4. Try canceling an enrollment
5. Submit feedback for completed session

**Expected Behavior:**
- Table shows all enrolled sessions
- Expandable details show session info
- Cancel button works with confirmation
- Feedback modal opens and submits successfully

### 3. Available Training Component
**Location:** `frontend/src/views/client/training/AvailableTraining.jsx`

**Test Steps:**
1. Click "Available Training" tab
2. Test search and filter functionality
3. Click "Request Enrollment" button
4. Fill justification and submit
5. Verify enrollment request is sent

**Expected Behavior:**
- Sessions load and display in table
- Search filters results correctly
- Enrollment modal opens with form
- Submission shows success message

### 4. Training Calendar Component
**Location:** `frontend/src/views/client/training/TrainingCalendar.jsx`

**Test Steps:**
1. Click "Calendar" tab
2. Navigate between months
3. Switch between "My Trainings" and "All Sessions" modes
4. Click on training sessions
5. Verify session details modal

**Expected Behavior:**
- Calendar displays current month
- Month navigation works smoothly
- Mode switch shows different data
- Session badges show enrollment status
- Details modal provides complete info

### 5. Training History Component
**Location:** `frontend/src/views/client/training/TrainingHistory.jsx`

**Test Steps:**
1. Click "History" tab
2. Verify completed trainings list
3. Check statistics overview
4. Test certificate download
5. Filter by year/program

**Expected Behavior:**
- Completed sessions display in chronological order
- Statistics show accurate completion data
- Certificate downloads work (or show placeholder)
- Filters update the list correctly

## Integration Tests

### 1. End-to-End Enrollment Flow
1. Browse available training sessions
2. Request enrollment with justification
3. Check enrollment appears in "My Trainings"
4. Verify calendar shows the session
5. Complete training and provide feedback
6. Confirm completion appears in history

### 2. Data Consistency Test
1. Check dashboard statistics
2. Count actual enrollments in "My Trainings"
3. Verify numbers match
4. Check calendar event count
5. Confirm history completion count

### 3. Real-time Updates Test
1. Make enrollment request
2. Check if dashboard stats update
3. Verify calendar reflects new enrollment
4. Ensure "Available Training" removes session

## Error Handling Tests

### 1. Network Errors
- Disconnect internet during API call
- Verify error messages display
- Check retry functionality
- Ensure graceful degradation

### 2. Authentication Errors
- Use expired token
- Verify redirect to login
- Test token refresh
- Check error notifications

### 3. Validation Errors
- Submit invalid enrollment request
- Check field validation messages
- Test required field enforcement
- Verify error styling

## Performance Tests

### 1. Load Times
- Dashboard should load within 3 seconds
- Calendar should render within 2 seconds
- Large training lists should paginate
- Images/certificates should lazy load

### 2. Memory Usage
- No memory leaks during navigation
- Proper cleanup of event listeners
- Component unmounting works correctly
- State management efficient

## Browser Compatibility
Test on:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Mobile Responsiveness
Test on:
- Mobile phones (320px-768px)
- Tablets (768px-1024px)
- Desktop (1024px+)

## Common Issues & Solutions

### Issue: "employee_id not found"
**Solution:** Ensure localStorage has valid employee_id set

### Issue: Calendar not loading
**Solution:** Check date format in API parameters (YYYY-MM-DD)

### Issue: Enrollment request fails
**Solution:** Verify authentication token and employee permissions

### Issue: Statistics not updating
**Solution:** Check if API endpoints return fresh data, clear cache

### Issue: Components not rendering
**Solution:** Verify all dependencies installed, check console errors

## Test Data Requirements

### Minimum Test Data:
- 1 employee record with ID
- 3-5 training programs
- 10+ training sessions (past, current, future)
- 5+ employee enrollments (various statuses)
- Sample feedback records

### Mock Data Script:
```sql
-- Create test employee
INSERT INTO employees (employee_id, first_name, last_name, email) 
VALUES ('EMP001', 'John', 'Doe', 'john.doe@company.com');

-- Create test training programs
INSERT INTO training_programs (title, description, category, status) 
VALUES 
('Leadership Skills', 'Leadership development program', 'Management', 'published'),
('Communication Training', 'Effective communication skills', 'Soft Skills', 'published'),
('Technical Training', 'Technical skill development', 'Technical', 'published');

-- Create test sessions and enrollments
-- (Add appropriate test data for your database schema)
```

This comprehensive test guide ensures the employee training module works correctly across all components and integrates properly with the backend API.