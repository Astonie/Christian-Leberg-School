# Training Enrollment & Calendar API Documentation

## Overview
This document describes the employee training enrollment system and calendar API endpoints.****

## What Was Fixed

### 1. Bulk Enrollment Bug Fix ✅
**Problem**: When enrolling multiple employees, only the last employee in the list was being enrolled.

**Root Cause**: The `bulkEnroll` method was calling `enrollEmployee()` which started a nested database transaction. This caused all enrollments except the last one to be rolled back.

**Solution**: Modified the `bulkEnroll` method to:
- Perform enrollment operations directly within a single transaction
- Skip employees who are already enrolled (with proper error messages)
- Validate each employee before enrollment
- Provide detailed success/failure reporting

**Location**: `app/Services/EmployeeTrainingService.php`

### 2. Training Calendar Feature ✅
Added comprehensive calendar functionality to view and manage training sessions across different time periods.

---

## API Endpoints

### Bulk Enrollment

#### Enroll Multiple Employees in a Session
```http
POST /api/v1/employee-trainings/bulk-enroll
```

**Request Body:**
```json
{
  "session_id": 1,
  "employee_ids": ["EMP001", "EMP002", "EMP003"]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Successfully enrolled 3 employees",
  "data": {
    "success": 3,
    "failed": 0,
    "errors": [],
    "enrollments": [
      {
        "id": 1,
        "employee_id": "EMP001",
        "session_id": 1,
        "enrollment_status": "enrolled",
        "attendance_status": "pending",
        "enrolled_at": "2025-10-22T10:00:00.000000Z",
        "employee": {...},
        "session": {...}
      }
      // ... more enrollments
    ]
  }
}
```

**Validation:**
- `session_id`: Required, must exist in training_sessions table
- `employee_ids`: Required, array of valid employee IDs
- Each employee must exist in the employees table
- Session must have available seats
- Employees cannot be enrolled twice in the same session

---

## Training Calendar Endpoints

### 1. Monthly Calendar View

```http
GET /api/v1/training-calendar/month?year=2025&month=10
```

**Query Parameters:**
- `year` (required): Integer, 2020-2100
- `month` (required): Integer, 1-12
- `employee_id` (optional): Filter by specific employee
- `program_id` (optional): Filter by training program
- `trainer_id` (optional): Filter by trainer
- `status` (optional): Filter by session status (scheduled, in_progress, completed, cancelled)

**Response:**
```json
{
  "success": true,
  "data": {
    "year": 2025,
    "month": 10,
    "month_name": "October",
    "start_date": "2025-10-01",
    "end_date": "2025-10-31",
    "total_sessions": 15,
    "days": [
      {
        "date": "2025-10-01",
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
              "name": "John Trainer",
              "email": "john@example.com"
            },
            "start_time": "2025-10-01 09:00:00",
            "end_time": "2025-10-01 17:00:00",
            "duration_hours": 8,
            "location": "Conference Room A",
            "room_number": "CR-101",
            "meeting_link": "https://meet.example.com/abc123",
            "status": "scheduled",
            "max_attendees": 20,
            "current_attendees": 15,
            "available_seats": 5,
            "is_full": false
          }
        ]
      }
      // ... more days
    ]
  }
}
```

### 2. Weekly Calendar View

```http
GET /api/v1/training-calendar/week?date=2025-10-22
```

**Query Parameters:**
- `date` (required): Any date within the week (e.g., "2025-10-22")
- `employee_id`, `program_id`, `trainer_id`, `status` (optional): Same as monthly view

**Response:**
```json
{
  "success": true,
  "data": {
    "week_number": 43,
    "year": 2025,
    "start_date": "2025-10-20",
    "end_date": "2025-10-26",
    "total_sessions": 5,
    "days": [...]
  }
}
```

### 3. Daily Calendar View

```http
GET /api/v1/training-calendar/day?date=2025-10-22
```

**Query Parameters:**
- `date` (required): The specific date (e.g., "2025-10-22")
- `employee_id`, `program_id`, `trainer_id`, `status` (optional)

**Response:**
```json
{
  "success": true,
  "data": {
    "date": "2025-10-22",
    "day_name": "Tuesday",
    "total_sessions": 3,
    "sessions": [...]
  }
}
```

### 4. Date Range Calendar

```http
GET /api/v1/training-calendar/range?start_date=2025-10-01&end_date=2025-10-31&group_by=day
```

**Query Parameters:**
- `start_date` (required): Start date
- `end_date` (required): End date (must be after or equal to start_date)
- `group_by` (optional): How to group results - "day", "week", or "none" (default: "day")
- `employee_id`, `program_id`, `trainer_id`, `status` (optional)

**Response (grouped by day):**
```json
{
  "success": true,
  "data": {
    "start_date": "2025-10-01",
    "end_date": "2025-10-31",
    "grouped_by": "day",
    "total_sessions": 25,
    "days": [...]
  }
}
```

**Response (grouped by week):**
```json
{
  "success": true,
  "data": {
    "start_date": "2025-10-01",
    "end_date": "2025-10-31",
    "grouped_by": "week",
    "total_sessions": 25,
    "weeks": [
      {
        "week_number": 40,
        "year": 2025,
        "start_date": "2025-09-29",
        "end_date": "2025-10-05",
        "sessions": [...]
      }
    ]
  }
}
```

### 5. Upcoming Trainings

```http
GET /api/v1/training-calendar/upcoming?days=30
```

**Query Parameters:**
- `days` (optional): Number of days to look ahead (default: 30, max: 365)
- `employee_id`, `program_id`, `trainer_id` (optional)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "program": {...},
      "trainer": {...},
      "start_time": "2025-10-25 09:00:00",
      "end_time": "2025-10-25 17:00:00",
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
```

### 6. Employee Calendar

```http
GET /api/v1/training-calendar/employee/{employeeId}?start_date=2025-10-01&end_date=2025-10-31
```

**Path Parameters:**
- `employeeId`: Employee ID (e.g., "EMP001")

**Query Parameters:**
- `start_date` (required): Start date
- `end_date` (required): End date
- `include_completed` (optional): Boolean, include completed trainings (default: true)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "program": {...},
      "trainer": {...},
      "start_time": "2025-10-25 09:00:00",
      "end_time": "2025-10-25 17:00:00",
      "duration_hours": 8,
      "location": "Conference Room A",
      "status": "scheduled",
      "max_attendees": 20,
      "current_attendees": 15,
      "available_seats": 5,
      "is_full": false,
      "enrollment": {
        "id": 123,
        "enrollment_status": "enrolled",
        "attendance_status": "pending",
        "enrolled_at": "2025-10-15T10:00:00.000000Z",
        "score": null,
        "passed": null
      }
    }
  ]
}
```

### 7. Trainer Calendar

```http
GET /api/v1/training-calendar/trainer/{trainerId}?start_date=2025-10-01&end_date=2025-10-31
```

**Path Parameters:**
- `trainerId`: Trainer/User ID

**Query Parameters:**
- `start_date` (required): Start date
- `end_date` (required): End date

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "program": {...},
      "trainer": {...},
      "start_time": "2025-10-25 09:00:00",
      "end_time": "2025-10-25 17:00:00",
      "duration_hours": 8,
      "location": "Conference Room A",
      "status": "scheduled",
      "max_attendees": 20,
      "current_attendees": 15,
      "available_seats": 5,
      "is_full": false,
      "enrollments": [
        {
          "id": 1,
          "employee": {
            "id": "EMP001",
            "name": "John Doe",
            "email": "john.doe@example.com"
          },
          "enrollment_status": "enrolled",
          "attendance_status": "pending"
        }
      ]
    }
  ]
}
```

### 8. Calendar Summary/Statistics

```http
GET /api/v1/training-calendar/summary?start_date=2025-10-01&end_date=2025-10-31
```

**Query Parameters:**
- `start_date` (required): Start date
- `end_date` (required): End date

**Response:**
```json
{
  "success": true,
  "data": {
    "date_range": {
      "start": "2025-10-01",
      "end": "2025-10-31",
      "days": 31
    },
    "total_sessions": 25,
    "by_status": {
      "scheduled": 15,
      "in_progress": 2,
      "completed": 7,
      "cancelled": 1
    },
    "total_enrolled": 350,
    "average_attendance_per_session": 14.5,
    "programs_count": 8,
    "sessions_by_program": {
      "1": 5,
      "2": 8,
      "3": 12
    }
  }
}
```

---

## Common Response Formats

### Success Response
```json
{
  "success": true,
  "data": {...}
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message here"
}
```

---

## Status Values

### Enrollment Status
- `enrolled` - Employee is enrolled in the session
- `waitlisted` - Employee is on the waiting list
- `completed` - Employee has completed the training
- `cancelled` - Enrollment was cancelled
- `withdrawn` - Employee withdrew from the training

### Attendance Status
- `pending` - Attendance not yet marked
- `present` - Employee attended the session
- `absent` - Employee did not attend
- `late` - Employee arrived late
- `excused` - Employee was excused from attendance

### Session Status
- `scheduled` - Session is scheduled for the future
- `in_progress` - Session is currently ongoing
- `completed` - Session has been completed
- `cancelled` - Session was cancelled

---

## Use Cases

### 1. Display Monthly Training Calendar for All Employees
```http
GET /api/v1/training-calendar/month?year=2025&month=11
```

### 2. View Specific Employee's Upcoming Trainings
```http
GET /api/v1/training-calendar/employee/EMP001?start_date=2025-10-22&end_date=2025-12-31&include_completed=false
```

### 3. Check Trainer's Schedule for a Week
```http
GET /api/v1/training-calendar/trainer/5?start_date=2025-10-20&end_date=2025-10-26
```

### 4. Get All Leadership Trainings in Q4
```http
GET /api/v1/training-calendar/range?start_date=2025-10-01&end_date=2025-12-31&program_id=5&group_by=week
```

### 5. Enroll Multiple Team Members
```http
POST /api/v1/employee-trainings/bulk-enroll
{
  "session_id": 10,
  "employee_ids": ["EMP001", "EMP002", "EMP003", "EMP004"]
}
```

### 6. Get Calendar Statistics for Monthly Report
```http
GET /api/v1/training-calendar/summary?start_date=2025-10-01&end_date=2025-10-31
```

---

## Authentication

All endpoints require authentication using Laravel Sanctum:

```http
Authorization: Bearer {your_access_token}
```

---

## Notes

1. **Timezone**: All datetime values are in UTC. Convert to local timezone on the client side.
2. **Pagination**: Calendar endpoints don't use pagination - they return all sessions for the specified period.
3. **Performance**: For large date ranges, consider using `group_by=week` to reduce payload size.
4. **Seat Management**: The system automatically tracks available seats and prevents over-enrollment.
5. **Concurrent Enrollments**: The bulk enrollment handles race conditions properly with database transactions.

---

## Testing

You can test these endpoints using tools like:
- Postman
- cURL
- Insomnia
- Your frontend application

Example cURL command:
```bash
curl -X GET "http://your-api-domain/api/v1/training-calendar/month?year=2025&month=10" \
  -H "Authorization: Bearer your_token_here" \
  -H "Accept: application/json"
```
