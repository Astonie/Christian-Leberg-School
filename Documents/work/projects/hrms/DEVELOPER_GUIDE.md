# HRMS Project - Developer Guide

## 📚 Documentation

All project documentation has been organized in the `/docs` directory. Start here: **[docs/README.md](docs/README.md)**

## 🗂️ Documentation Structure

```
docs/
├── README.md                    # Master documentation index
├── training/        (17 docs)  # Training management system
├── attendance/      (3 docs)   # Attendance tracking
├── documents/       (6 docs)   # Document management
├── backend/         (8 docs)   # Backend implementation
├── integration/     (4 docs)   # System integration
└── architecture/    (5 docs)   # System architecture
```

## 🚀 Quick Links

### Getting Started
- **Backend Setup:** [backend/README.md](backend/README.md)
- **Frontend Setup:** [frontend/README.md](frontend/README.md)
- **Backend Quick Reference:** [docs/backend/QUICK_REFERENCE.md](docs/backend/QUICK_REFERENCE.md)

### Module Documentation
- **Training Module:** [docs/training/TRAINING_QUICK_START_GUIDE.md](docs/training/TRAINING_QUICK_START_GUIDE.md)
- **Attendance Module:** [docs/attendance/ATTENDANCE_PHASE1_IMPLEMENTATION_SUMMARY.md](docs/attendance/ATTENDANCE_PHASE1_IMPLEMENTATION_SUMMARY.md)
- **Document Management:** [docs/documents/DOCUMENT_MANAGEMENT_API_REFERENCE.md](docs/documents/DOCUMENT_MANAGEMENT_API_REFERENCE.md)

### API Documentation
- [Document Management API](docs/documents/DOCUMENT_MANAGEMENT_API_REFERENCE.md)
- [Employee Documents API](docs/documents/EMPLOYEE_DOCUMENTS_API.md)
- [Training Enrollment API](docs/backend/TRAINING_ENROLLMENT_CALENDAR_API.md)

### Testing
- [Integration Test Guide](docs/integration/INTEGRATION_TEST_GUIDE.md)
- [Training Tests Implementation](docs/backend/TRAINING_TESTS_IMPLEMENTATION.md)

## 📦 Project Structure

```
hrms/
├── backend/              # Laravel API
│   ├── app/
│   ├── database/
│   ├── routes/
│   └── tests/
├── frontend/             # React application
│   ├── src/
│   └── public/
└── docs/                 # Project documentation
    ├── training/
    ├── attendance/
    ├── documents/
    ├── backend/
    ├── integration/
    └── architecture/
```

## 🛠️ Development Workflow

1. **Check Documentation:** Always check `/docs` for module-specific guides
2. **API Reference:** Refer to API documentation before implementing endpoints
3. **Testing:** Follow test guides in `/docs/integration` and `/docs/backend`
4. **Updates:** Keep documentation updated as you make changes

## 📖 Finding Documentation

- **By Module:** Navigate to the appropriate directory in `/docs`
- **By Type:**
  - Quick Guides: `*_QUICK_START_GUIDE.md`, `*_QUICK_REFERENCE.md`
  - APIs: `*_API.md`, `*_API_REFERENCE.md`
  - Implementation: `*_IMPLEMENTATION.md`, `*_IMPLEMENTATION_SUMMARY.md`
  - Fixes: `*_FIX.md`, `*_COMPLETE.md`

## 🤝 Contributing

When adding new features or making changes:
1. Update relevant documentation in `/docs`
2. Follow existing naming conventions
3. Add entries to `/docs/README.md` if creating new docs
4. Keep documentation organized by module

---

**For detailed module documentation, see:** [docs/README.md](docs/README.md)
