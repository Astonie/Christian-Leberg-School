# Academic Module Workflow Test Guide
**Test Date:** December 29, 2025  
**Testing Complete Exam to Report Card Workflow**

---

## Overview
This document guides you through testing the complete academic workflow:
1. **Exam Creation** (Head Teacher)
2. **Assessment Structure Configuration** (Head Teacher)
3. **Marks Entry** (Teachers)
4. **Results Grading** (System)
5. **Results Release** (Head Teacher)
6. **Report Card Generation** (System)

---

## Prerequisites

### Required Data
Before testing, ensure you have:
- ✅ Active Academic Year
- ✅ At least one Term created
- ✅ Exam Types configured
- ✅ Grading Scale set up
- ✅ Subjects created
- ✅ Classes with students enrolled
- ✅ Teachers assigned to subjects
- ✅ Assessment Structures defined

### User Accounts Needed
- **Head Teacher Account** (role: Head Teacher)
- **Teacher Account** (role: Teacher)
- **Student Account** (role: Student)

---

## STEP 1: Create Assessment Structure (Head Teacher)

### Purpose
Define how marks are distributed across components (e.g., CAT1, CAT2, Exam)

### Actions
1. Login as **Head Teacher**
2. Navigate to: **Assessments → Assessment Structures**
3. Click **"Create Assessment Structure"**
4. Fill in the form:
   ```
   Name: Form 1 Mathematics Structure
   Subject: Mathematics
   Academic Year: 2025
   Term: Term 1
   Pass Mark: 50
   Total Marks: 100
   ```

5. Add Components:
   ```
   Component 1:
   - Name: CAT 1
   - Weight: 20%
   - Max Marks: 100
   
   Component 2:
   - Name: CAT 2
   - Weight: 20%
   - Max Marks: 100
   
   Component 3:
   - Name: End Term Exam
   - Weight: 60%
   - Max Marks: 100
   ```

6. Click **"Save Assessment Structure"**

### Expected Result
✅ Assessment structure created successfully  
✅ Total weight = 100%  
✅ Structure appears in assessment structures list

### Verification Query
```sql
SELECT * FROM assessment_structures WHERE name = 'Form 1 Mathematics Structure';
SELECT * FROM assessment_components WHERE assessment_structure_id = [ID];
```

---

## STEP 2: Create Exam (Head Teacher)

### Purpose
Create an official exam that will use the assessment structure

### Actions
1. Still logged in as **Head Teacher**
2. Navigate to: **Exams → All Exams**
3. Click **"Create New Exam"**
4. Fill in exam details:
   ```
   Exam Name: Form 1 End of Term 1 Exam 2025
   Academic Year: 2025
   Term: Term 1
   Exam Type: End of Term
   Start Date: 2025-01-15
   End Date: 2025-01-25
   Results Entry Start: 2025-01-26
   Results Entry End: 2025-02-05
   Assessment Structure: Form 1 Mathematics Structure
   Grading Scale: Standard Scale
   ```

5. Select **Subjects**:
   - Check: Mathematics, English, Science, etc.

6. Select **Classes**:
   - Check: Form 1A, Form 1B, Form 1C

7. Add description:
   ```
   End of Term 1 Examination for Form 1 students.
   Results must be submitted by February 5, 2025.
   ```

8. Click **"Create Exam"**

### Expected Result
✅ Exam created successfully  
✅ Status shows as "Upcoming" (if before start date)  
✅ Exam appears in exams list  
✅ Shows attached subjects and classes

### Verification
- Navigate to exam detail page
- Verify all fields are correct
- Check that subjects and classes are listed

---

## STEP 3: Enter Component Marks (Teachers)

### Purpose
Teachers enter marks for each assessment component (CAT1, CAT2, Exam)

### Actions - CAT 1 Entry
1. **Logout** and login as **Teacher**
2. Navigate to: **Assessments → Enter Marks** OR **My Assessments**
3. Select:
   ```
   Academic Year: 2025
   Term: Term 1
   Subject: Mathematics
   Class: Form 1A
   Component: CAT 1
   ```

4. Click **"Load Students"**

5. Enter marks for each student:
   ```
   Student 1: 85/100
   Student 2: 72/100
   Student 3: 90/100
   Student 4: 65/100
   ...
   ```

6. Click **"Save Marks"**

7. **Repeat for CAT 2 and End Term Exam** components

### Expected Result
✅ Marks saved successfully for each component  
✅ Success message displayed  
✅ Marks appear when you reload the page  
✅ System calculates weighted marks automatically

### Verification Query
```sql
SELECT 
    s.first_name, s.last_name,
    ac.name as component,
    ss.raw_score, ss.weighted_score,
    ss.created_at
FROM student_scores ss
JOIN students s ON ss.student_id = s.id
JOIN assessment_components ac ON ss.assessment_component_id = ac.id
WHERE ss.assessment_structure_id = [structure_id]
ORDER BY s.last_name, ac.order;
```

---

## STEP 4: Grade Results (System Automatic)

### Purpose
System automatically calculates final grades based on assessment structure

### What Happens Automatically
When all component marks are entered:
1. **Weighted scores calculated**: CAT1 (20%) + CAT2 (20%) + Exam (60%)
2. **Total score computed**: Sum of all weighted scores
3. **Grade assigned**: Based on grading scale
4. **Pass/Fail determined**: Compared against pass mark (50)

### Example Calculation
```
Student: John Doe
CAT 1: 85/100 × 20% = 17.0
CAT 2: 72/100 × 20% = 14.4
Exam: 90/100 × 60% = 54.0
─────────────────────────────
Final Score: 85.4/100
Grade: A (Distinction)
Result: PASS
```

### Verification
1. Navigate to: **Exams → [Exam Name] → View Results**
2. Check calculated totals for students
3. Verify grades match grading scale

---

## STEP 5: Publish/Release Results (Head Teacher)

### Purpose
Make results visible to students and parents

### Actions
1. Login as **Head Teacher**
2. Navigate to: **Exams → All Exams**
3. Click on **Form 1 End of Term 1 Exam 2025**
4. Review results summary:
   - Total students tested
   - Average score
   - Pass rate
   - Distribution chart

5. If satisfied, click **"Release Results"** or **"Publish Results"**
6. Confirm the action

### Expected Result
✅ Results status changes to "Published"  
✅ Students can now view their results  
✅ Report cards become available for generation

### Verification
- Try logging in as a student
- Check if results are visible
- Verify report card link appears

---

## STEP 6: Generate Report Cards (System/Head Teacher)

### Purpose
Create printable report cards for students

### Actions - Individual Report Card
1. As **Head Teacher** or **Class Teacher**
2. Navigate to: **Reports → Student Report Cards**
3. Select:
   ```
   Academic Year: 2025
   Term: Term 1
   Class: Form 1A
   Student: [Select Student]
   ```

4. Click **"Generate Report Card"**

### Actions - Bulk Report Cards
1. Navigate to: **Reports → Bulk Report Cards**
2. Select:
   ```
   Academic Year: 2025
   Term: Term 1
   Exam: Form 1 End of Term 1 Exam 2025
   Class: Form 1A (or All Classes)
   ```

3. Click **"Generate All Report Cards"**

### Expected Report Card Contents
✅ Student information (Name, ID, Class, Stream)  
✅ Exam details (Name, Term, Year)  
✅ Subject-wise marks breakdown:
   - CAT 1 marks
   - CAT 2 marks
   - Exam marks
   - Total marks
   - Grade
   - Position in class

✅ Summary statistics:
   - Total marks obtained
   - Total possible marks
   - Percentage
   - Overall grade
   - Class average
   - Position in class/stream

✅ Teacher comments (if configured)  
✅ Head teacher signature  
✅ School logo and details

### Report Card Formats Available
- PDF (downloadable/printable)
- HTML (web view)
- Batch PDF (all students in one file)

---

## STEP 7: Student/Parent View Results

### Purpose
Verify students can access their results

### Actions
1. **Logout** and login as **Student**
2. Navigate to: **My Results** or **Dashboard**
3. View published results:
   - See all exam results
   - View subject-wise performance
   - Check grades and comments

4. Download report card:
   - Click **"Download Report Card"**
   - Verify PDF is correctly formatted

### Expected Result
✅ Student sees only their own results  
✅ Results match what was entered  
✅ Report card downloads successfully  
✅ Report card is professional and complete

---

## Testing Checklist

### Prerequisites Setup
- [ ] Academic year created and active
- [ ] Term created for the academic year
- [ ] Exam types configured
- [ ] Grading scale set up
- [ ] Subjects created (at least 3)
- [ ] Classes created with students
- [ ] Students enrolled in classes
- [ ] Teachers assigned to subjects
- [ ] User accounts (Head Teacher, Teacher, Student)

### Workflow Steps
- [ ] Assessment structure created with components
- [ ] Exam created by head teacher
- [ ] Exam linked to assessment structure
- [ ] Subjects and classes attached to exam
- [ ] Teacher entered marks for CAT 1
- [ ] Teacher entered marks for CAT 2
- [ ] Teacher entered marks for End Term Exam
- [ ] System calculated weighted scores
- [ ] System assigned grades correctly
- [ ] Head teacher reviewed results
- [ ] Results published/released
- [ ] Individual report card generated
- [ ] Bulk report cards generated
- [ ] Student accessed their results
- [ ] Student downloaded report card
- [ ] Report card contains all required information

### Edge Cases to Test
- [ ] What happens if marks not entered for all components?
- [ ] Can teacher edit marks after submission?
- [ ] Can results be unpublished?
- [ ] What if student is absent (mark as absent)?
- [ ] What if student transfers mid-term?
- [ ] Multiple exams in same term handling
- [ ] Grading scale edge cases (exactly 50, 49, etc.)

---

## Common Issues and Solutions

### Issue 1: Assessment Structure Total Weight ≠ 100%
**Symptom:** Cannot save assessment structure  
**Solution:** Adjust component weights to sum to exactly 100%

### Issue 2: No Students Appear When Entering Marks
**Symptom:** Student list is empty  
**Solution:** 
- Verify students are enrolled in the selected class
- Check students are active for the academic year
- Ensure teacher is assigned to the subject

### Issue 3: Grades Not Calculating
**Symptom:** Final grades show as blank  
**Solution:**
- Ensure all component marks are entered
- Check assessment structure is properly linked
- Verify grading scale is configured

### Issue 4: Report Cards Missing Data
**Symptom:** Report card incomplete  
**Solution:**
- Ensure all exam results are entered
- Check student has marks for all subjects
- Verify exam is published

---

## SQL Verification Queries

### Check Assessment Structure
```sql
SELECT 
    as_table.name,
    as_table.total_marks,
    ac.name as component,
    ac.weight,
    ac.max_marks
FROM assessment_structures as_table
JOIN assessment_components ac ON as_table.id = ac.assessment_structure_id
WHERE as_table.id = [structure_id];
```

### Check Exam Configuration
```sql
SELECT 
    e.name,
    e.start_date,
    e.end_date,
    ay.name as academic_year,
    t.name as term,
    COUNT(DISTINCT es.subject_id) as subjects_count,
    COUNT(DISTINCT ec.class_id) as classes_count
FROM exams e
JOIN academic_years ay ON e.academic_year_id = ay.id
JOIN terms t ON e.term_id = t.id
LEFT JOIN exam_subjects es ON e.id = es.exam_id
LEFT JOIN exam_classes ec ON e.id = ec.exam_id
WHERE e.id = [exam_id]
GROUP BY e.id;
```

### Check Student Scores
```sql
SELECT 
    s.first_name, s.last_name,
    sub.name as subject,
    ac.name as component,
    ss.raw_score,
    ss.weighted_score,
    ac.weight
FROM student_scores ss
JOIN students s ON ss.student_id = s.id
JOIN subjects sub ON ss.subject_id = sub.id
JOIN assessment_components ac ON ss.assessment_component_id = ac.id
WHERE ss.assessment_structure_id = [structure_id]
ORDER BY s.last_name, sub.name, ac.order;
```

### Check Final Results
```sql
SELECT 
    s.first_name, s.last_name,
    sub.name as subject,
    SUM(ss.weighted_score) as total_score,
    (SUM(ss.weighted_score) / as_table.total_marks * 100) as percentage
FROM student_scores ss
JOIN students s ON ss.student_id = s.id
JOIN subjects sub ON ss.subject_id = sub.id
JOIN assessment_structures as_table ON ss.assessment_structure_id = as_table.id
WHERE ss.assessment_structure_id = [structure_id]
GROUP BY s.id, sub.id
ORDER BY s.last_name, sub.name;
```

---

## Success Criteria

The workflow test is successful if:
1. ✅ Assessment structure created with proper component weights
2. ✅ Exam created and linked to assessment structure
3. ✅ Teachers able to enter marks for all components
4. ✅ System correctly calculates weighted scores
5. ✅ Final grades assigned according to grading scale
6. ✅ Results successfully published by head teacher
7. ✅ Report cards generate with complete information
8. ✅ Students can view their results
9. ✅ Students can download properly formatted report cards
10. ✅ All data is accurate and consistent

---

## Next Steps After Testing

If tests pass:
- [ ] Document any workflow issues discovered
- [ ] Create user training materials
- [ ] Set up production data
- [ ] Configure email notifications
- [ ] Set up automatic report card generation
- [ ] Configure parent access to results

If tests fail:
- [ ] Document specific failures
- [ ] Check error logs
- [ ] Verify database schema
- [ ] Review controller logic
- [ ] Test with minimal data first
- [ ] Report bugs with detailed reproduction steps

---

## Test Execution Log

| Step | Status | Notes | Tested By | Date |
|------|--------|-------|-----------|------|
| Create Assessment Structure | ⬜ Not Started | | | |
| Create Exam | ⬜ Not Started | | | |
| Enter CAT 1 Marks | ⬜ Not Started | | | |
| Enter CAT 2 Marks | ⬜ Not Started | | | |
| Enter Exam Marks | ⬜ Not Started | | | |
| Verify Grade Calculation | ⬜ Not Started | | | |
| Publish Results | ⬜ Not Started | | | |
| Generate Individual Report | ⬜ Not Started | | | |
| Generate Bulk Reports | ⬜ Not Started | | | |
| Student View Results | ⬜ Not Started | | | |
| Student Download Report | ⬜ Not Started | | | |

---

**End of Test Guide**
