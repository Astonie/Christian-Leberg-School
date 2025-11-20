# Quick Reference Guide - Training Calendar & Enrollment

## ✅ What Was Fixed

### Bulk Enrollment Bug
**Before**: Only the last employee was enrolled  
**After**: All employees are enrolled successfully

```bash
# Example: Enroll 5 employees at once
POST /api/v1/employee-trainings/bulk-enroll
{
  "session_id": 1,
  "employee_ids": ["EMP001", "EMP002", "EMP003", "EMP004", "EMP005"]
}
```

---

## 🗓️ Calendar Endpoints - Quick Reference

### Get Monthly View
```bash
GET /api/v1/training-calendar/month?year=2025&month=11
```

### Get Weekly View
```bash
GET /api/v1/training-calendar/week?date=2025-10-22
```

### Get Daily View
```bash
GET /api/v1/training-calendar/day?date=2025-10-22
```

### Get Custom Date Range
```bash
# Group by day
GET /api/v1/training-calendar/range?start_date=2025-10-01&end_date=2025-10-31&group_by=day

# Group by week
GET /api/v1/training-calendar/range?start_date=2025-10-01&end_date=2025-12-31&group_by=week

# No grouping (flat list)
GET /api/v1/training-calendar/range?start_date=2025-10-01&end_date=2025-10-31&group_by=none
```

### Get Upcoming Trainings
```bash
# Next 30 days (default)
GET /api/v1/training-calendar/upcoming

# Next 60 days
GET /api/v1/training-calendar/upcoming?days=60
```

### Get Employee's Calendar
```bash
GET /api/v1/training-calendar/employee/EMP001?start_date=2025-10-01&end_date=2025-12-31

# Exclude completed trainings
GET /api/v1/training-calendar/employee/EMP001?start_date=2025-10-01&end_date=2025-12-31&include_completed=false
```

### Get Trainer's Calendar
```bash
GET /api/v1/training-calendar/trainer/5?start_date=2025-10-01&end_date=2025-10-31
```

### Get Calendar Statistics
```bash
GET /api/v1/training-calendar/summary?start_date=2025-10-01&end_date=2025-10-31
```

---

## 🔍 Filtering Options

All calendar endpoints support these filters:

```bash
# Filter by employee
?employee_id=EMP001

# Filter by program
?program_id=5

# Filter by trainer
?trainer_id=10

# Filter by status
?status=scheduled  # Options: scheduled, in_progress, completed, cancelled

# Combine multiple filters
?employee_id=EMP001&program_id=5&status=scheduled
```

---

## 📋 Common Use Cases

### 1. Display Dashboard Calendar (Current Month)
```bash
GET /api/v1/training-calendar/month?year=2025&month=10
```

### 2. Show Employee's Upcoming Trainings
```bash
GET /api/v1/training-calendar/employee/EMP001?start_date=2025-10-22&end_date=2025-12-31&include_completed=false
```

### 3. Check Trainer Availability This Week
```bash
GET /api/v1/training-calendar/trainer/5?start_date=2025-10-20&end_date=2025-10-26
```

### 4. Get Q4 Training Report
```bash
GET /api/v1/training-calendar/summary?start_date=2025-10-01&end_date=2025-12-31
```

### 5. Find Available Leadership Trainings
```bash
GET /api/v1/training-calendar/upcoming?days=90&program_id=5
```

### 6. Enroll Team in Training
```bash
POST /api/v1/employee-trainings/bulk-enroll
{
  "session_id": 15,
  "employee_ids": ["EMP001", "EMP002", "EMP003", "EMP004", "EMP005", "EMP006"]
}
```

---

## 📊 Response Example

```json
{
  "success": true,
  "data": {
    "year": 2025,
    "month": 10,
    "month_name": "October",
    "total_sessions": 15,
    "days": [
      {
        "date": "2025-10-22",
        "day_name": "Wednesday",
        "sessions": [
          {
            "id": 1,
            "program": {
              "id": 1,
              "title": "Leadership Training",
              "category": "Management"
            },
            "trainer": {
              "id": 5,
              "name": "John Doe",
              "email": "john@example.com"
            },
            "start_time": "2025-10-22 09:00:00",
            "end_time": "2025-10-22 17:00:00",
            "duration_hours": 8,
            "location": "Conference Room A",
            "status": "scheduled",
            "max_attendees": 20,
            "current_attendees": 15,
            "available_seats": 5,
            "is_full": false
          }
        ]
      }
    ]
  }
}
```

---

## 🔐 Authentication

All endpoints require Bearer token:

```bash
curl -X GET "http://your-api/api/v1/training-calendar/month?year=2025&month=10" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

---

## 📝 Files Changed

1. **app/Services/EmployeeTrainingService.php** - Fixed bulk enrollment
2. **app/Services/TrainingSessionService.php** - Added calendar methods
3. **app/Http/Controllers/Api/TrainingCalendarController.php** - New controller
4. **routes/modules/training.php** - Added calendar routes

---

## 🎯 Summary

- ✅ Bulk enrollment now works correctly for all employees
- ✅ 8 new calendar endpoints available
- ✅ Flexible filtering and grouping options
- ✅ Employee and trainer specific views
- ✅ Calendar statistics and analytics
- ✅ All endpoints authenticated and validated
- ✅ Comprehensive documentation provided

**All features are production-ready!**
