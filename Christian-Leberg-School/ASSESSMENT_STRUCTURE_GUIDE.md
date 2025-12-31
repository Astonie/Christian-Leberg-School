# Assessment Structure Integration Guide

## Overview 📚

Assessment Structures define how examination marks are broken down and weighted for grading. They are now fully integrated with the exam system.

## What Are Assessment Structures?

Assessment structures allow you to define **component-based grading** where the final mark is calculated from multiple weighted components.

### Example Scenarios:

1. **Basic Structure**:
   - Continuous Assessment: 40%
   - Final Exam: 60%
   - **Total: 100%**

2. **Detailed Structure**:
   - Coursework: 30%
   - Mid-term Test: 20%
   - Final Examination: 50%
   - **Total: 100%**

3. **Complex Structure** (with groups):
   - Continuous Assessment (40%):
     - Assignments: 50% (of CA)
     - Class Tests: 50% (of CA)
   - Final Exam: 60%
   - **Total: 100%**

---

## Integration Points

### 1. During Exam Creation ✅ (Implemented)

When creating an examination, you can now select an **Assessment Structure** in the "Grading Configuration" section.

**Location**: [Exams](exams/create.blade.php) > Create Examination > Grading Configuration

**Fields Available**:
- **Grading Scale**: Defines the letter grades (A-F) or numeric scale (1-7)
- **Assessment Structure**: Defines how marks are broken down into components

**When to Use**:
- If all subjects in the exam use the same assessment breakdown
- For standardized school-wide assessment policies
- When you want a default structure that applies to all subjects

**Effect**: The selected assessment structure will be linked to the exam and can be used during:
- Mark entry
- Grade calculation
- Result generation

---

### 2. During Mark Entry (Future Enhancement)

**Planned Feature**: When entering marks for students, teachers should be able to:
- See the assessment components (e.g., Continuous Assessment, Final Exam)
- Enter marks for each component separately
- System automatically calculates the weighted total

**Example Mark Entry Form**:
```
Subject: Mathematics
Student: John Doe

Assessment Components:
├─ Continuous Assessment (40%): [85] marks
└─ Final Exam (60%): [78] marks

Calculated Total: (85 × 0.4) + (78 × 0.6) = 34 + 46.8 = 80.8/100
```

---

## Workflow Guide

### Step-by-Step: Setting Up Examination with Assessment Structure

#### Step 1: Create Assessment Structure
1. Go to **Academic Records** > **Assessment Structures**
2. Click **"Create Assessment Structure"**
3. Fill in:
   - Name: "Standard CA + Exam"
   - Select Subject (optional - can be subject-specific)
   - Select Grading System
4. Add Components:
   - Component 1: "Continuous Assessment" - Weight: 40%
   - Component 2: "Final Examination" - Weight: 60%
5. Save (ensures weights sum to 100%)

#### Step 2: Create Grading Scale (if not exists)
1. Go to **Academic Records** > **Grading Scales**
2. Create scale with grade ranges:
   - A: 80-100
   - B: 70-79
   - C: 60-69
   - D: 50-59
   - E: 40-49
   - F: 0-39

#### Step 3: Create Examination
1. Go to **Examinations** > **Create Examination**
2. Fill Basic Information:
   - Name: "End of Term 1 Examination 2025"
   - Academic Year, Term, Dates
3. Select Subjects and Classes
4. **Grading Configuration**:
   - Select Grading Scale: "Standard A-F Scale"
   - Select Assessment Structure: "Standard CA + Exam"
5. Submit

#### Step 4: Enter Marks (Current Process)
Currently, you enter the **final marks** only. With assessment structures configured:
- The system knows how those marks should be weighted
- Future enhancement will break this into component entry

#### Step 5: Generate Results
The assessment structure will be used to:
- Display component breakdown on report cards
- Calculate weighted totals
- Apply grading scale correctly

---

## Technical Details

### Database Schema

```sql
-- Exams table has assessment_structure_id
exams:
  - id
  - name
  - academic_year_id
  - term_id
  - grading_scale_id
  - assessment_structure_id  ← Links to assessment structure
  - start_date
  - end_date

-- Assessment structures define the breakdown
assessment_structures:
  - id
  - name
  - subject_id (nullable)
  - grading_system_id
  - configuration

-- Components define individual parts
assessment_components:
  - id
  - assessment_structure_id
  - name
  - code
  - weight (percentage)
  - parent_id (for nested structures)
  - is_group (true for group components)
```

### Model Relationships

```php
// Exam.php
public function assessmentStructure()
{
    return $this->belongsTo(AssessmentStructure::class);
}

// AssessmentStructure.php
public function components()
{
    return $this->hasMany(AssessmentComponent::class)->orderBy('order');
}

public function subject()
{
    return $this->belongsTo(Subject::class);
}
```

---

## Best Practices

### 1. Subject-Specific vs Generic Structures

**Subject-Specific** (Recommended for varying requirements):
- Mathematics: 30% CA + 70% Exam (heavy on final assessment)
- English: 50% CA + 50% Exam (balanced)
- Physical Education: 70% CA + 30% Exam (heavy on continuous)

**Generic** (Recommended for standardized policies):
- "Standard Structure" applies to all subjects uniformly
- Easier to manage
- Consistent school-wide grading

### 2. Naming Conventions

Good names:
- ✅ "Standard 40/60 CA-Exam"
- ✅ "Mathematics Assessment 2025"
- ✅ "Balanced 50-50 Structure"

Avoid:
- ❌ "Structure 1"
- ❌ "Test"
- ❌ "AS1"

### 3. Weight Validation

The system **automatically validates** that component weights sum to 100%.

Valid:
```
Continuous Assessment: 40%
Final Exam: 60%
Total: 100% ✅
```

Invalid:
```
Continuous Assessment: 40%
Final Exam: 50%
Total: 90% ❌ (will be rejected)
```

---

## Future Enhancements

### Planned Features:

1. **Component-Based Mark Entry**
   - Enter marks per component
   - Auto-calculate weighted totals

2. **Report Card Integration**
   - Display component breakdown
   - Show "CA: 34/40, Exam: 46.8/60"

3. **Analytics Dashboard**
   - Compare student performance across components
   - Identify weak areas (e.g., low CA but high exam scores)

4. **Flexible Structures Per Subject**
   - Override exam-level structure per subject
   - Different subjects, different breakdowns in same exam

5. **Historical Tracking**
   - Track assessment structure changes over time
   - Compare year-over-year grading policies

---

## Common Questions

### Q: Do I have to use assessment structures?
**A**: No, they're optional. You can create exams without selecting an assessment structure.

### Q: Can I change the assessment structure after creating the exam?
**A**: Yes, you can edit the exam and change the assessment structure. However, if marks are already entered, you may need to recalculate.

### Q: Can different subjects in the same exam use different structures?
**A**: Currently, one structure applies to the entire exam. Future enhancement will allow per-subject structures.

### Q: What happens if I don't select an assessment structure?
**A**: Marks are entered and calculated as simple totals without component breakdown. The grading scale still applies.

### Q: Can I have nested components (groups)?
**A**: Yes! Assessment components support parent-child relationships for complex structures. For example:
```
Continuous Assessment (40%) [GROUP]
├─ Assignments (50% of CA = 20% total)
└─ Tests (50% of CA = 20% total)
Final Exam (60%)
```

---

## Summary

✅ **Assessment Structures are now linked to exams** during creation and editing  
✅ **Displayed on exam detail pages** for transparency  
✅ **Database relationships** fully configured  
✅ **Ready for mark entry integration** (next phase)  

The foundation is complete. Next step: Integrate component-based mark entry when teachers record student results.

---

## Quick Reference

| Feature | Status | Location |
|---------|--------|----------|
| Create Assessment Structure | ✅ Working | Academic Records > Assessment Structures |
| Link to Exam (Create) | ✅ Working | Exams > Create > Grading Configuration |
| Link to Exam (Edit) | ✅ Working | Exams > Edit > Grading Configuration |
| Display on Exam Details | ✅ Working | Exams > View Exam |
| Component Mark Entry | 🚧 Planned | Marks Entry (future) |
| Report Card Breakdown | 🚧 Planned | Results/Reports (future) |

---

**Last Updated**: December 28, 2025  
**Version**: 1.0
