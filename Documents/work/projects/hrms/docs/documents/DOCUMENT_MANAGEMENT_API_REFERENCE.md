# Document Management API - Quick Reference

## Base URL
```
http://your-domain.com/api/v1/documents
```

## Authentication
All endpoints require Bearer token authentication:
```
Authorization: Bearer {your-token}
X-Tenant-ID: {tenant-id}
```

## Folder Endpoints

### List Folders
```http
GET /folders
```
**Response:**
```json
{
  "success": true,
  "folders": [
    {
      "id": 1,
      "name": "Company Policies",
      "icon": "📋",
      "color": "#3B82F6",
      "description": "Official policies",
      "access_level": "public",
      "document_count": 15,
      "total_size": 5242880,
      "created_at": "2025-01-01T10:00:00Z"
    }
  ]
}
```

### Get Folder Details
```http
GET /folders/{folderId}
```
**Response:**
```json
{
  "success": true,
  "data": {
    "folder": { ... },
    "subfolders": [ ... ],
    "documents": [
      {
        "id": 1,
        "name": "Employee Handbook",
        "file_name": "handbook.pdf",
        "category": "POLICY",
        "tags": ["HR", "Mandatory"],
        "confidentiality": "internal",
        "size": 1048576,
        "document_number": "POL-2025-0001",
        "approval_status": "approved",
        "created_at": "2025-01-01T10:00:00Z",
        "url": "https://..."
      }
    ]
  }
}
```

### Create Folder
```http
POST /folders
Content-Type: application/json

{
  "name": "Company Policies",
  "icon": "📋",
  "color": "#3B82F6",
  "description": "Official company policies",
  "access_level": "public",
  "parent_id": null
}
```

### Update Folder
```http
PUT /folders/{folderId}
Content-Type: application/json

{
  "name": "Updated Name",
  "description": "Updated description"
}
```

### Delete Folder
```http
DELETE /folders/{folderId}
```

## Document Endpoints

### Upload Document
```http
POST /upload
Content-Type: multipart/form-data

file: [FILE]
folder_id: 1
category: "POLICY"
tags: ["HR", "Mandatory"]
confidentiality: "internal"
description: "Employee handbook 2025"
requires_approval: false
```

**Response:**
```json
{
  "success": true,
  "message": "Document uploaded successfully",
  "document": {
    "id": 123,
    "name": "handbook.pdf",
    "file_name": "handbook.pdf",
    "document_number": "POL-2025-0001",
    "category": "POLICY",
    "approval_status": "approved",
    "url": "https://..."
  }
}
```

### Download Document
```http
GET /download/{documentId}
```
Returns file as download.

### Delete Document
```http
DELETE /{documentId}
```

### Bulk Delete
```http
POST /bulk-delete
Content-Type: application/json

{
  "document_ids": [1, 2, 3]
}
```

## Version Control

### Create New Version
```http
POST /{documentId}/versions
Content-Type: multipart/form-data

file: [FILE]
version_notes: "Updated with new regulations"
```

### Get Version History
```http
GET /{documentId}/versions
```

**Response:**
```json
{
  "success": true,
  "versions": [
    {
      "id": 1,
      "name": "handbook.pdf",
      "file_name": "handbook_v1.pdf",
      "version_notes": "Initial version",
      "created_at": "2025-01-01T10:00:00Z",
      "size": 1048576,
      "uploaded_by": "John Doe"
    },
    {
      "id": 2,
      "name": "handbook.pdf",
      "file_name": "handbook_v2.pdf",
      "version_notes": "Updated with new regulations",
      "created_at": "2025-02-01T10:00:00Z",
      "size": 1150000,
      "uploaded_by": "Jane Smith"
    }
  ]
}
```

## Approval Workflow

### Approve Document
```http
POST /{documentId}/approve
Content-Type: application/json

{
  "notes": "Approved for publication"
}
```

### Reject Document
```http
POST /{documentId}/reject
Content-Type: application/json

{
  "reason": "Requires revision before approval"
}
```

### Get Pending Approvals
```http
GET /pending-approvals
```

**Response:**
```json
{
  "success": true,
  "count": 5,
  "documents": [
    {
      "id": 123,
      "name": "New Policy Draft",
      "file_name": "policy.pdf",
      "category": "POLICY",
      "description": "Draft policy for review",
      "document_number": "POL-2025-0010",
      "uploaded_by": "John Doe",
      "created_at": "2025-11-01T10:00:00Z"
    }
  ]
}
```

## Sharing

### Share Document
```http
POST /{documentId}/share
Content-Type: application/json

{
  "employee_ids": [456, 789, 101]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Document shared successfully",
  "shared_with": [
    {
      "employee_id": 456,
      "name": "Jane Smith"
    },
    {
      "employee_id": 789,
      "name": "Bob Johnson"
    }
  ]
}
```

### Get Shared Documents
```http
GET /shared
```

## Search & Filter

### Search Documents
```http
POST /search
Content-Type: application/json

{
  "search": "policy",
  "category": "POLICY",
  "confidentiality": "internal",
  "approval_status": "approved",
  "date_from": "2025-01-01",
  "date_to": "2025-12-31",
  "folder_id": 1,
  "per_page": 20
}
```

**Response:**
```json
{
  "success": true,
  "documents": [ ... ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 150,
    "last_page": 8
  }
}
```

## Categories

### Get Categories
```http
GET /categories
```

**Response:**
```json
{
  "success": true,
  "categories": [
    {
      "id": 1,
      "name": "Policies",
      "code": "POLICY",
      "description": "Company policies and procedures",
      "icon": "📋",
      "color": "#3B82F6",
      "requires_approval": true
    }
  ]
}
```

### Create Category
```http
POST /categories
Content-Type: application/json

{
  "name": "Certifications",
  "code": "CERT",
  "description": "Professional certifications",
  "icon": "🎓",
  "color": "#10B981",
  "requires_approval": false
}
```

## Analytics

### Get Analytics
```http
GET /analytics
```

**Response:**
```json
{
  "success": true,
  "analytics": {
    "total_documents": 500,
    "documents_this_month": 45,
    "pending_approvals": 8,
    "total_size": 524288000,
    "by_category": {
      "POLICY": 150,
      "CONTRACT": 100,
      "CERT": 250
    },
    "by_confidentiality": {
      "public": 50,
      "internal": 300,
      "confidential": 100,
      "restricted": 50
    },
    "by_approval_status": {
      "approved": 450,
      "pending": 30,
      "rejected": 20
    },
    "recent_documents": [ ... ]
  }
}
```

## Status Codes

- `200 OK` - Request successful
- `201 Created` - Resource created successfully
- `400 Bad Request` - Invalid request data
- `401 Unauthorized` - Invalid or missing token
- `403 Forbidden` - Insufficient permissions
- `404 Not Found` - Resource not found
- `422 Unprocessable Entity` - Validation failed
- `500 Internal Server Error` - Server error

## Common Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Validation error message"]
  }
}
```

## Confidentiality Levels
- `public` - Everyone can access
- `internal` - Employees only
- `confidential` - Restricted access
- `restricted` - Highly sensitive

## Approval Status
- `pending` - Awaiting approval
- `approved` - Approved for use
- `rejected` - Rejected with reason

## Permission Types
- `view` - Can view document
- `download` - Can download document
- `edit` - Can edit document
- `delete` - Can delete document
- `share` - Can share document

## Access Levels (Folders)
- `private` - Owner only
- `departmental` - Department members
- `public` - All employees

## File Upload Limits
- **Maximum file size**: 10MB
- **Allowed types**: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG

## Testing with cURL

### Login and get token
```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -H "X-Tenant-ID: 1" \
  -d '{"email":"user@example.com","password":"password"}'
```

### Create folder
```bash
curl -X POST http://localhost:8000/api/v1/documents/folders \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "X-Tenant-ID: 1" \
  -d '{"name":"Test Folder","access_level":"private"}'
```

### Upload document
```bash
curl -X POST http://localhost:8000/api/v1/documents/upload \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "X-Tenant-ID: 1" \
  -F "file=@document.pdf" \
  -F "folder_id=1" \
  -F "category=POLICY" \
  -F "confidentiality=internal"
```

### Search documents
```bash
curl -X POST http://localhost:8000/api/v1/documents/search \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "X-Tenant-ID: 1" \
  -d '{"search":"policy","category":"POLICY"}'
```

## Rate Limiting
Consider implementing rate limiting for upload endpoints to prevent abuse.

## Best Practices
1. Always validate file types on server side
2. Scan uploaded files for malware
3. Use pagination for large result sets
4. Cache analytics data for better performance
5. Implement proper error handling
6. Log all document access for audit trails
7. Use meaningful document numbers
8. Regular cleanup of expired permissions
9. Monitor storage usage
10. Backup document storage regularly
