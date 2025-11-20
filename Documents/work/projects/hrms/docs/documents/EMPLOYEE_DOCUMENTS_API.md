# Employee Personal Documents API

## Overview
This API provides endpoints for employees to manage their personal documents within the HRMS system. Each employee gets a dedicated personal folder that is automatically created on first access.

## Base URL
```
/api/v1/employee/documents
```

## Authentication
All endpoints require JWT authentication. Include the token in the Authorization header:
```
Authorization: Bearer {your-jwt-token}
```

## Endpoints

### 1. Get My Documents
Get all documents in the authenticated employee's personal folder.

**Endpoint:** `GET /api/v1/employee/documents`

**Response:**
```json
{
  "success": true,
  "documents": [
    {
      "id": 1,
      "name": "Resume.pdf",
      "file_name": "resume_2024.pdf",
      "category": "personal",
      "tags": ["cv", "employment"],
      "description": "Updated resume for 2024",
      "size": 245760,
      "mime_type": "application/pdf",
      "confidentiality": "internal",
      "approval_status": "approved",
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z",
      "uploaded_by": "John Doe"
    }
  ],
  "total": 15,
  "total_size": 5242880,
  "by_category": {
    "personal": {
      "count": 8,
      "total_size": 3145728
    },
    "certificates": {
      "count": 7,
      "total_size": 2097152
    }
  },
  "folder": {
    "id": 42,
    "name": "John Doe - Personal"
  }
}
```

### 2. Upload Document
Upload a new document to your personal folder.

**Endpoint:** `POST /api/v1/employee/documents/upload`

**Content-Type:** `multipart/form-data`

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| file | File | Yes | The document file (max 10MB) |
| name | String | No | Display name for the document |
| category | String | No | Document category (default: "general") |
| description | String | No | Document description (max 1000 chars) |
| tags | Array | No | Array of tags for categorization |
| confidentiality | String | No | One of: public, internal, confidential, restricted (default: "internal") |

**Example Request (FormData):**
```javascript
const formData = new FormData();
formData.append('file', fileInput.files[0]);
formData.append('name', 'My Resume');
formData.append('category', 'personal');
formData.append('description', 'Updated resume for 2024');
formData.append('tags[]', 'cv');
formData.append('tags[]', 'employment');
formData.append('confidentiality', 'internal');

fetch('/api/v1/employee/documents/upload', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token
  },
  body: formData
});
```

**Response:**
```json
{
  "success": true,
  "message": "Document uploaded successfully",
  "document": {
    "id": 123,
    "name": "My Resume",
    "file_name": "my_resume.pdf",
    "size": 245760,
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

**Validation Errors:**
```json
{
  "success": false,
  "errors": {
    "file": ["The file field is required."],
    "confidentiality": ["The selected confidentiality is invalid."]
  }
}
```

### 3. View Document
View a document inline (opens in browser).

**Endpoint:** `GET /api/v1/employee/documents/{documentId}/view`

**Response:** Document file with `Content-Disposition: inline`

**Access Control:**
- Own documents: Always accessible
- Shared documents: Accessible if shared with you and not expired

### 4. Download Document
Download a document file.

**Endpoint:** `GET /api/v1/employee/documents/{documentId}/download`

**Response:** Document file with `Content-Disposition: attachment`

**Access Control:**
- Own documents: Always accessible
- Shared documents: Accessible if shared with you and not expired

### 5. Delete Document
Delete a document from your personal folder.

**Endpoint:** `DELETE /api/v1/employee/documents/{documentId}`

**Response:**
```json
{
  "success": true,
  "message": "Document deleted successfully"
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Unauthorized"
}
```

**Note:** You can only delete documents you own.

### 6. Get Shared Documents
Get all documents that have been shared with you by other users.

**Endpoint:** `GET /api/v1/employee/documents/shared`

**Response:**
```json
{
  "success": true,
  "documents": [
    {
      "id": 45,
      "name": "Company Policy.pdf",
      "file_name": "policy_2024.pdf",
      "size": 1048576,
      "mime_type": "application/pdf",
      "permission_type": "view",
      "shared_at": "2024-01-10T09:00:00Z",
      "expires_at": "2024-12-31T23:59:59Z",
      "shared_by_name": "HR Manager"
    }
  ],
  "total": 12
}
```

**Permission Types:**
- `view`: Can only view the document
- `download`: Can view and download
- `edit`: Can view, download, and modify
- `full`: Full access including sharing rights

## Features

### Automatic Folder Creation
When an employee accesses their documents for the first time, a personal folder is automatically created with:
- **Name:** `{FirstName} {LastName} - Personal`
- **Access Level:** Private (only accessible by the employee)
- **Icon:** 👤 (user icon)
- **Color:** Blue (#3B82F6)

### Document Categories
Organize your documents with categories:
- `personal` - Personal documents
- `certificates` - Educational/professional certificates
- `contracts` - Employment contracts
- `tax` - Tax-related documents
- `medical` - Medical records
- `training` - Training materials
- `general` - General documents

### Confidentiality Levels
Set appropriate confidentiality levels:
- `public` - Available to everyone
- `internal` - Available to all employees
- `confidential` - Restricted access
- `restricted` - Highly restricted

### File Size Limits
- Maximum file size: 10MB
- Supported formats: All common document formats (PDF, DOC, DOCX, XLS, XLSX, images, etc.)

## Frontend Integration

### React/Axios Example

```javascript
import axios from 'axios';

const API_BASE = 'http://localhost:8000/api/v1';

// Get my documents
const getMyDocuments = async () => {
  const response = await axios.get(`${API_BASE}/employee/documents`, {
    headers: {
      Authorization: `Bearer ${token}`
    }
  });
  return response.data;
};

// Upload document
const uploadDocument = async (file, metadata) => {
  const formData = new FormData();
  formData.append('file', file);
  
  if (metadata.name) formData.append('name', metadata.name);
  if (metadata.category) formData.append('category', metadata.category);
  if (metadata.description) formData.append('description', metadata.description);
  if (metadata.tags) {
    metadata.tags.forEach(tag => formData.append('tags[]', tag));
  }
  
  const response = await axios.post(
    `${API_BASE}/employee/documents/upload`,
    formData,
    {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
      }
    }
  );
  return response.data;
};

// Download document
const downloadDocument = (documentId) => {
  window.open(
    `${API_BASE}/employee/documents/${documentId}/download?token=${token}`,
    '_blank'
  );
};

// Delete document
const deleteDocument = async (documentId) => {
  const response = await axios.delete(
    `${API_BASE}/employee/documents/${documentId}`,
    {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }
  );
  return response.data;
};

// Get shared documents
const getSharedDocuments = async () => {
  const response = await axios.get(
    `${API_BASE}/employee/documents/shared`,
    {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }
  );
  return response.data;
};
```

## Error Handling

### Common Error Responses

**401 Unauthorized:**
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

**403 Forbidden:**
```json
{
  "success": false,
  "message": "Unauthorized"
}
```

**404 Not Found:**
```json
{
  "success": false,
  "message": "Employee record not found"
}
```

**422 Validation Error:**
```json
{
  "success": false,
  "errors": {
    "file": ["The file field is required."],
    "category": ["The category must be a string."]
  }
}
```

**500 Internal Server Error:**
```json
{
  "success": false,
  "message": "Failed to upload document"
}
```

## Security Features

1. **Authentication Required:** All endpoints require valid JWT token
2. **Employee Association:** User must have an associated employee record
3. **Access Control:** 
   - Users can only access their own documents
   - Shared documents respect permission types and expiration dates
4. **File Validation:** Files are validated for size and type
5. **Automatic Folder Creation:** Personal folders are created automatically with proper permissions

## Database Schema

### CustomMedia (Documents)
- `id` - Primary key
- `model_type` - Folder class
- `model_id` - Folder ID
- `collection_name` - 'documents'
- `name` - Display name
- `file_name` - Actual filename
- `size` - File size in bytes
- `mime_type` - MIME type
- `category` - Document category
- `tags` - JSON array of tags
- `description` - Text description
- `confidentiality` - Confidentiality level
- `approval_status` - Approval status

### DocumentShares
- `id` - Primary key
- `media_id` - Foreign key to CustomMedia
- `employee_id` - Foreign key to Employee
- `shared_by` - Employee ID who shared
- `permission_type` - Permission level
- `shared_at` - Timestamp
- `expires_at` - Expiration timestamp (nullable)

## Testing

Test the API using the provided test script:

```bash
cd backend
php test_employee_documents.php
```

This will verify:
- Controller exists
- All methods are implemented
- Folder creation logic
- Document shares integration
- Routes configuration

## Best Practices

1. **Always validate files client-side** before uploading to improve UX
2. **Show file size limits** prominently in the UI
3. **Implement progress indicators** for file uploads
4. **Cache document lists** to reduce API calls
5. **Handle errors gracefully** with user-friendly messages
6. **Use appropriate confidentiality levels** based on document sensitivity
7. **Clean up expired shares** periodically (can be done via scheduled task)

## Next Steps

1. Implement file preview functionality
2. Add document search within personal folder
3. Implement document version history
4. Add bulk upload capability
5. Implement file type restrictions per category
6. Add document expiration reminders
