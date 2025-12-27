# Grading System Quick Start Guide

## Setup Process (Administrator)

### 1. Create Grading System
```
Navigation: Admin → Grading Systems → Create New

Example:
- Name: "KCSE Grading System 2025"
- Description: "Kenya Certificate of Secondary Education Grading"
- Is Active: ✓ (checked)
```

### 2. Add Grading Scales to System
```
Navigation: Admin → Grading Systems → [Select System] → Add Scale

Example Scales:
Grade | Code | Min Score | Max Score | Points | Description
------|------|-----------|-----------|--------|------------
A     | A    | 80        | 100       | 12     | Excellent
A-    | A-   | 75        | 79        | 11     | Very Good  
B+    | B+   | 70        | 74        | 10     | Good
B     | B    | 65        | 69        | 9      | Good
B-    | B-   | 60        | 64        | 8      | Above Average
C+    | C+   | 55        | 59        | 7      | Average
C     | C    | 50        | 54        | 6      | Average
C-    | C-   | 45        | 49        | 5      | Below Average
D+    | D+   | 40        | 44        | 4      | Below Average
D     | D    | 35        | 39        | 3      | Poor
D-    | D-   | 30        | 34        | 2      | Poor
E     | E    | 0         | 29        | 1      | Very Poor
```

### 3. Create Assessment Structure
```
Navigation: Admin → Assessment Structures → Create New

Example 1: Standard Term Assessment
- Name: "KCSE Standard Term Assessment"
- Subject: (Leave blank for all subjects)
- Grading System: "KCSE Grading System 2025"
- Version: 1

Components:
1. Continuous Assessment Tests (CAT)
   - Code: CAT
   - Weight: 20%
   - Max Score: 20
   - Description: Regular class tests throughout the term

2. Mid-Term Examination (MID)
   - Code: MID
   - Weight: 30%
   - Max Score: 30
   - Description: Mid-term examination assessment

3. End of Term Examination (EXAM)
   - Code: EXAM
   - Weight: 50%
   - Max Score: 50
   - Description: Final term examination

Total Weight: 100% ✓
```

---

## Usage Process (Teacher)

### 1. Select or Create Exam
```
Navigation: Exams → Create New

Example:
- Name: "Term 1 Mid-Year 2025"
- Academic Year: "2025"
- Term: "Term 1"
- Assessment Structure: "KCSE Standard Term Assessment"
- Exam Type: "Mid-Term"
- Start Date: 2025-04-01
- End Date: 2025-04-05
```

### 2. Enter Student Scores by Component
```
Navigation: Exams → [Select Exam] → Enter Marks → [Select Class & Subject]

For each student, enter marks for each component:

Student: John Doe
Subject: Mathematics

CAT (Max: 20):     [18]  ← Enter actual score
MID (Max: 30):     [24]  ← Enter actual score  
EXAM (Max: 50):    [42]  ← Enter actual score

[Save Scores]
```

### 3. System Auto-Calculates Final Grade
```
The system automatically:

1. Converts each score to percentage:
   CAT:  18/20  = 90%
   MID:  24/30  = 80%
   EXAM: 42/50  = 84%

2. Applies weights:
   CAT:  90% × 20% = 18%
   MID:  80% × 30% = 24%
   EXAM: 84% × 50% = 42%

3. Sums weighted scores:
   Total: 18% + 24% + 42% = 84%

4. Looks up grade from scale:
   84% falls in 80-100 range
   Grade: A (12 points)

5. Saves to final_results:
   - Percentage: 84%
   - Grade: A
   - Points: 12
   - Breakdown: [detailed component scores]
```

---

## Advanced Features

### Subject-Specific Assessment Structures

Some subjects may require different assessment structures:

```
Example: Practical-Heavy Subjects (Science, ICT)

Structure Name: "Science Practical Assessment"
Subject: Chemistry (specific subject)

Components:
1. Lab Practicals (PRAC) - 30%, Max: 30
2. Continuous Assessment (CAT) - 20%, Max: 20
3. End Term Exam (EXAM) - 50%, Max: 50
```

### Component Groups (Future Enhancement)

For more complex assessment:

```
Group: Coursework (40% total)
  ├─ Homework (HW) - 10%, Max: 10
  ├─ Projects (PROJ) - 15%, Max: 15
  └─ Class Participation (PART) - 15%, Max: 15

Individual: Final Exam (EXAM) - 60%, Max: 60
```

---

## Integration with Existing Systems

### How it connects to current workflow:

**Before Assessment Structures:**
```
Teacher enters exam marks → System shows percentage → Manual grade assignment
```

**With Assessment Structures:**
```
Teacher enters component marks (CAT, MID, EXAM)
    ↓
System applies weights automatically
    ↓
System calculates final percentage
    ↓
System assigns grade from grading scale
    ↓
System stores in final_results with full breakdown
    ↓
Reports show detailed component performance + final grade
```

### Backwards Compatibility

The system supports both:
- **Old Method**: Direct exam marks entry (single mark per subject)
- **New Method**: Component-based marks entry (CAT, MID, EXAM)

Existing exams without assessment structures continue to work normally.

---

## Common Workflows

### Workflow 1: Beginning of Term Setup
1. Admin creates/activates appropriate grading system
2. Admin creates assessment structure for the term
3. Admin creates exam and links to assessment structure
4. System is ready for mark entry

### Workflow 2: During Term - Continuous Assessment
1. Teacher navigates to exam/assessment
2. Teacher selects class and subject
3. Teacher enters CAT marks (first component)
4. System saves, shows progress (20% of total entered)
5. Teacher repeats for MID marks (50% of total entered)
6. Teacher enters EXAM marks (100% of total entered)
7. System auto-calculates final grades

### Workflow 3: End of Term - Results Generation
1. Admin triggers final results calculation
2. System:
   - Loads all student scores for each component
   - Applies weights per assessment structure
   - Calculates final percentages
   - Assigns grades from grading scales
   - Saves to final_results table
3. Reports become available for:
   - Report cards (with component breakdown)
   - Class performance analysis
   - Subject performance trends
   - Individual student progress

---

## Benefits Summary

✅ **Transparent Assessment**: Students/parents see exactly how grades are calculated
✅ **Flexible Structures**: Different subjects can have different assessment methods
✅ **Automatic Calculation**: No manual weighted average calculations
✅ **Audit Trail**: Track who entered scores and when
✅ **Historical Tracking**: Version control for assessment structures
✅ **Multiple Grading Systems**: Support KCSE, IB, A-Level, etc.
✅ **Detailed Reporting**: Component-level performance analysis
✅ **Error Prevention**: System validates weights sum to 100%

---

## Troubleshooting

**Q: Components don't sum to 100%**
- Check each component's weight
- System shows warning if not exactly 100%
- Adjust weights before saving

**Q: Grades not auto-calculating**
- Verify grading system is marked as "Active"
- Check grading scales have no gaps (0-100% fully covered)
- Ensure assessment structure is linked to exam

**Q: Different subjects need different structures**
- Create multiple assessment structures
- Assign subject_id when creating structure
- Subject-specific structures override general ones

**Q: Need to change assessment structure mid-term**
- Create new structure with incremented version
- System keeps historical data intact
- Reports show which version was used

---

## API Usage (For Developers)

### Calculate Grade Programmatically

```php
use App\Services\GradingEngine;

$engine = new GradingEngine();

$result = $engine->compute([
    'student_id' => 123,
    'subject_id' => 5,
    'academic_year_id' => 2025,
    'term_id' => 1,
    'assessment_structure_id' => 1
]);

// Returns:
[
    'percentage' => 84.0,
    'grade_code' => 'A',
    'grade_label' => 'Excellent',
    'points' => 12,
    'breakdown' => [
        ['name' => 'CAT', 'raw' => 18, 'max' => 20, 'weight' => 20, 'contribution' => 18],
        ['name' => 'MID', 'raw' => 24, 'max' => 30, 'weight' => 30, 'contribution' => 24],
        ['name' => 'EXAM', 'raw' => 42, 'max' => 50, 'weight' => 50, 'contribution' => 42],
    ]
]
```

### Save Final Result

```php
$finalResult = $engine->calculateAndSave(
    studentId: 123,
    subjectId: 5,
    academicYearId: 2025,
    termId: 1,
    assessmentStructureId: 1
);

// Saves to final_results table and returns FinalResult model
```
