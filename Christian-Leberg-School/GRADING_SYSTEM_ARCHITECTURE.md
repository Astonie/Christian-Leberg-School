# Grading System Architecture

## Overview
The system has three interconnected components that work together to handle comprehensive academic assessment:

## 1. **Grading System** (Foundation)
**Purpose**: Defines the overall grading philosophy and scale system for the school

**Database Table**: `grading_systems`
- `id`
- `name` (e.g., "KCSE Grading", "International Baccalaureate", "American GPA")
- `description`
- `is_active` (boolean - only one can be active at a time)
- `timestamps`

**Relationships**:
- Has many `GradingScale` (one grading system contains multiple grade boundaries)
- Has many `AssessmentStructure` (optional - structures can reference specific grading systems)

**Example**:
- Name: "Kenya Secondary Education Grading System"
- Contains scales: A (80-100), A- (75-79), B+ (70-74)...

---

## 2. **Grading Scale** (Grade Boundaries)
**Purpose**: Defines the specific grade boundaries within a grading system

**Database Table**: `grading_scales`
- `id`
- `grading_system_id` (foreign key → grading_systems)
- `code` (e.g., "A", "B+", "C")
- `description` (e.g., "Excellent", "Good")
- `min_score` (minimum percentage/score for this grade)
- `max_score` (maximum percentage/score for this grade)
- `points` (grade point value, e.g., 12, 11, 10...)
- `order` (display order)
- `timestamps`

**Relationships**:
- Belongs to `GradingSystem`

**Example Scales for KCSE**:
```
A:  12 points  (80-100%)  - Excellent
A-: 11 points  (75-79%)   - Very Good
B+: 10 points  (70-74%)   - Good
B:  9 points   (65-69%)   - Good
B-: 8 points   (60-64%)   - Above Average
C+: 7 points   (55-59%)   - Average
```

**Usage**: When calculating final grades, the system:
1. Calculates the final percentage score
2. Looks up the active grading system
3. Matches the percentage against grading scales
4. Assigns the appropriate grade code and points

---

## 3. **Assessment Structure** (How to Assess)
**Purpose**: Defines HOW subjects are assessed - the components, weights, and scoring breakdown

**Database Table**: `assessment_structures`
- `id`
- `name` (e.g., "Standard Term Assessment", "Final Year Assessment")
- `description`
- `subject_id` (nullable - can be subject-specific or universal)
- `grading_system_id` (nullable - references which grading system to use)
- `version` (for tracking changes)
- `configuration` (JSON - additional settings)
- `timestamps`

**Relationships**:
- Has many `AssessmentComponent` (the individual assessment pieces)
- Belongs to `Subject` (optional - can apply to specific subject or all)
- Belongs to `GradingSystem` (optional - can override default)

**Example**:
- Name: "KCSE Standard Assessment"
- Subject: null (applies to all subjects)
- Components: CAT (20%), Mid-Term (30%), Final Exam (50%)

---

## 4. **Assessment Component** (Individual Assessment Pieces)
**Purpose**: Individual pieces that make up an assessment structure

**Database Table**: `assessment_components`
- `id`
- `assessment_structure_id` (foreign key → assessment_structures)
- `name` (e.g., "Continuous Assessment Test", "Final Examination")
- `code` (e.g., "CAT", "EXAM", "HW")
- `weight` (percentage contribution to final - must sum to 100%)
- `max_score` (maximum marks for this component)
- `order` (display order)
- `is_group` (boolean - can contain nested components)
- `parent_id` (nullable - for grouping components)
- `description`
- `timestamps`

**Relationships**:
- Belongs to `AssessmentStructure`
- Can have nested `AssessmentComponent` children (for grouping)
- Has many `StudentScore` (actual scores recorded)

**Example Components**:
```
CAT:      Weight 20%, Max Score 20,  Order 1
Mid-Term: Weight 30%, Max Score 30,  Order 2
Final:    Weight 50%, Max Score 50,  Order 3
Total:    Weight 100% (must equal 100%)
```

---

## 5. **Student Scores** (Actual Marks)
**Purpose**: Records individual student performance on each assessment component

**Database Table**: `student_scores`
- `id`
- `student_id` (foreign key → students)
- `subject_id` (foreign key → subjects)
- `assessment_component_id` (foreign key → assessment_components)
- `academic_year_id` (foreign key → academic_years)
- `term_id` (foreign key → terms)
- `score` (actual marks earned, e.g., 15/20)
- `entered_by` (user who entered the score)
- `timestamps`

**Relationships**:
- Belongs to `Student`
- Belongs to `Subject`
- Belongs to `AssessmentComponent`
- Belongs to `AcademicYear`
- Belongs to `Term`

**Example**:
- Student: John Doe
- Subject: Mathematics
- Component: CAT (max_score: 20, weight: 20%)
- Score: 15/20
- Contribution to final: (15/20) × 20% = 15%

---

## How They Work Together

### Scenario: Recording and Grading Student Performance

**Step 1: Define Grading System** (Done Once)
```
Create "KCSE Grading System"
  └─ Add scales: A (80-100), A- (75-79), B+ (70-74)...
```

**Step 2: Define Assessment Structure** (Per Subject or Universal)
```
Create "Standard Term Assessment"
  ├─ CAT (20% weight, 20 max marks)
  ├─ Mid-Term (30% weight, 30 max marks)
  └─ Final Exam (50% weight, 50 max marks)
```

**Step 3: Record Student Scores** (Throughout Term)
```
Student: John Doe | Subject: Mathematics

Record Scores:
  CAT:      18/20  (90%)
  Mid-Term: 24/30  (80%)
  Final:    42/50  (84%)
```

**Step 4: Calculate Final Grade** (End of Term)
```
Weighted Average:
  CAT:      (18/20) × 20% = 18%
  Mid-Term: (24/30) × 30% = 24%
  Final:    (42/50) × 50% = 42%
  Total:    84%

Match against Grading Scale:
  84% falls in range 80-100%
  Grade: A (12 points)
```

---

## Database Relationships Diagram

```
GradingSystem (1) ─────┐
    │                   │
    │ has many          │ optionally used by
    ↓                   ↓
GradingScale (*)   AssessmentStructure (1)
                        │
                        │ has many
                        ↓
                   AssessmentComponent (*)
                        │
                        │ has many
                        ↓
                   StudentScore (*)
                        │
                        └─ belongs to Student, Subject, Term, AcademicYear
```

---

## Key Integration Points

### 1. **Exam → Assessment Structure**
- Each `Exam` can reference an `AssessmentStructure`
- Defines which components teachers need to enter marks for
- Example: "Mid-Term Exam 2025" uses "Standard Term Assessment" structure

### 2. **Assessment Structure → Grading System**
- Assessment structures can specify which grading system to use
- Falls back to the active grading system if not specified
- Example: "IB Assessment" uses "International Baccalaureate Grading"

### 3. **Student Scores → Final Grades**
- System calculates weighted average from component scores
- Converts to percentage based on max_score
- Looks up grade from grading scales
- Assigns grade code and points

### 4. **Final Results Calculation**
```php
// Pseudocode for grade calculation
$components = AssessmentStructure::find($structureId)->components;
$totalWeightedScore = 0;

foreach ($components as $component) {
    $score = StudentScore::where([
        'student_id' => $studentId,
        'subject_id' => $subjectId,
        'assessment_component_id' => $component->id
    ])->first();
    
    if ($score) {
        // Convert to percentage
        $percentage = ($score->score / $component->max_score) * 100;
        
        // Apply weight
        $weightedScore = ($percentage * $component->weight) / 100;
        $totalWeightedScore += $weightedScore;
    }
}

// $totalWeightedScore now contains final percentage
// Look up grade from GradingScale
$grade = GradingScale::where('grading_system_id', $activeSystemId)
    ->where('min_score', '<=', $totalWeightedScore)
    ->where('max_score', '>=', $totalWeightedScore)
    ->first();

return [
    'percentage' => $totalWeightedScore,
    'grade' => $grade->code,
    'points' => $grade->points
];
```

---

## Benefits of This Architecture

1. **Flexibility**: Different subjects can use different assessment structures
2. **Versioning**: Track changes to assessment structures over time
3. **Multiple Grading Systems**: Support different curricula (KCSE, IB, A-Level)
4. **Component-Level Detail**: Teachers see exactly what to assess
5. **Automatic Calculation**: System handles weighted averages and grading
6. **Audit Trail**: Track who entered scores and when
7. **Nested Components**: Support complex assessment hierarchies (groups within groups)

---

## Future Enhancements

1. **Component Groups**: Enable nested component structures (e.g., "Coursework" containing "HW", "Projects", "Tests")
2. **Subject-Specific Overrides**: Allow subjects to override assessment structures
3. **Rubric Integration**: Attach rubrics to components for detailed assessment
4. **Progressive Assessment**: Track improvement over multiple terms
5. **Predictive Analytics**: Forecast final grades based on component performance
