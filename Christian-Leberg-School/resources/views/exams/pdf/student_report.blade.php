<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Card - {{ $student->user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            margin: 15mm 12mm;
            size: A4 portrait;
        }
        
        body { 
            font-family: 'Arial', 'Helvetica', sans-serif;
            color: #1a1a1a;
            font-size: 11px;
            line-height: 1.4;
            background: white;
        }
        
        /* Header Section */
        .header-section {
            border-bottom: 4px solid #1a1a1a;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        
        .title { 
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .subtitle {
            text-align: center;
            font-size: 10px;
            color: #666;
            font-style: italic;
            margin-bottom: 10px;
        }
        
        .top { 
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        
        .top td { 
            vertical-align: top;
            padding: 0;
        }
        
        .logo-box {
            width: 85px;
            height: 85px;
            border: 3px solid #1a1a1a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 9px;
            color: #999;
            background: #f5f5f5;
        }
        
        /* Student Info Table */
        .meta-table { 
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        
        .meta-table td { 
            padding: 4px 6px;
            font-size: 11px;
        }
        
        .meta-table td:first-child {
            font-weight: 600;
            color: #444;
            width: 110px;
        }
        
        .meta-table td:last-child {
            color: #1a1a1a;
        }
        
        /* Performance Table */
        .report-table { 
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .report-table th,
        .report-table td { 
            border: 1px solid #ccc;
            padding: 7px 8px;
            font-size: 11px;
        }
        
        .report-table th { 
            background: #2d3748;
            color: white;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .report-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .report-table tbody tr:hover {
            background: #f0f0f0;
        }
        
        .report-table td {
            text-align: center;
        }
        
        .report-table td:nth-child(2) {
            text-align: left;
            font-weight: 600;
        }
        
        .report-table td:first-child {
            font-weight: 600;
            color: #666;
        }
        
        /* Summary Section */
        .summary-section {
            margin-top: 15px;
            page-break-inside: avoid;
        }
        
        .summary-grid { 
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        
        .summary-grid td { 
            border: 1px solid #ddd;
            padding: 8px 10px;
            font-size: 11px;
            background: #fafafa;
        }
        
        .summary-grid strong {
            color: #1a1a1a;
            font-weight: 600;
        }
        
        .summary-grid .highlight {
            background: #e6f2ff;
            font-weight: bold;
        }
        
        /* Grading Scale */
        .grading-scale {
            border-top: 2px solid #e5e5e5;
            padding-top: 12px;
            margin-top: 15px;
            page-break-inside: avoid;
        }
        
        .grading-scale h4 {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 6px;
            color: #1a1a1a;
        }
        
        .grading-scale .scale-content {
            font-size: 9.5px;
            line-height: 1.6;
            color: #444;
        }
        
        .grading-scale .scale-item {
            margin-bottom: 3px;
        }
        
        /* Signature Section */
        .signature-section {
            margin-top: 30px;
            page-break-inside: avoid;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .signature-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .signature-item {
            display: table-cell;
            text-align: center;
            padding: 0 15px;
        }
        
        .signature-line {
            border-bottom: 2px solid #666;
            height: 50px;
            margin-bottom: 6px;
        }
        
        .signature-label {
            font-size: 10px;
            font-weight: 600;
            color: #444;
        }
        
        /* Utility Classes */
        .muted { color: #666; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 600; }
        
        /* Note/Alert Box */
        .note-box {
            background: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 10px 12px;
            margin: 12px 0;
            font-size: 10px;
            color: #856404;
        }
        
        .note-box strong {
            color: #856404;
        }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    @php $logo = file_exists(public_path('images/school_logo.png')) ? asset('images/school_logo.png') : null; @endphp
    
    <!-- Header Section -->
    <div class="header-section">
        <div class="title">{{ strtoupper(config('app.name', 'HENRY HENDERSON SCHOOL OF EXCELLENCE')) }}</div>
        <div class="subtitle">Knowledge • Excellence • Character</div>
    </div>

    <!-- Student Information -->
    <table class="top">
        <tr>
            <td style="width: 70%;">
                <table class="meta-table">
                    <tr><td>Student Name:</td><td>{{ strtoupper($student->user->name) }}</td></tr>
                    <tr><td>Admission No:</td><td>{{ $student->admission_number }}</td></tr>
                    <tr><td>Class:</td><td>{{ $class?->name ?? '-' }}{{ $stream?->name ? ' - '.$stream->name : '' }}</td></tr>
                    <tr><td>Exam:</td><td>{{ $exam->name }}</td></tr>
                    <tr><td>Academic Year:</td><td>{{ $exam->academicYear?->name ?? '-' }}</td></tr>
                    <tr><td>Term:</td><td>{{ $exam->term->name }}</td></tr>
                    <tr><td>Report Date:</td><td>{{ now()->format('d M Y') }}</td></tr>
                </table>
            </td>
            <td style="width: 30%; text-align: center; padding-left: 15px;">
                @if($logo)
                    <img src="{{ $logo }}" style="width:85px; height:85px; object-fit:contain; border-radius:50%; border: 3px solid #1a1a1a;" alt="School Logo" />
                @else
                    <div class="logo-box">
                        <div>SCHOOL<br>LOGO</div>
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Performance Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: auto;">Subject</th>
                <th style="width: 80px;">Marks</th>
                <th style="width: 50px;">Percent</th>
                <th style="width: 50px;">Grade</th>
                <th style="width: 85px;">Position</th>
                <th style="width: 110px;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row['no'] }}</td>
                    <td style="text-align: left;">{{ $row['subject']?->name ?? '-' }}</td>
                    <td>
                        @if($row['marks'] > 0)
                            {{ $row['marks'] }} / 100
                        @else
                            <span class="muted">-</span>
                        @endif
                    </td>
                    <td class="font-bold">
                        @if($row['marks'] > 0)
                            {{ $row['percent'] }}%
                        @else
                            <span class="muted">-</span>
                        @endif
                    </td>
                    <td class="font-bold">
                        @if($row['marks'] > 0)
                            {{ $row['grade'] }}
                        @else
                            <span class="muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($row['position'])
                            {{ $row['position'] }} / {{ $row['position_total'] }}
                        @else
                            <span class="muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($row['marks'] > 0)
                            {{ $row['remark'] }}
                        @else
                            <span class="muted">Absent</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Section -->
    <div class="summary-section">
        <table class="summary-grid">
            <tr>
                <td><strong>Subjects Taken:</strong> {{ count($rows) }}</td>
                <td><strong>Subjects Passed:</strong> {{ $subjectsPassed }}</td>
                <td class="highlight"><strong>Pass Rate:</strong> {{ count($rows) > 0 ? number_format(($subjectsPassed / count($rows)) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td><strong>Total Marks:</strong> {{ $total }} / {{ $totalPossible }}</td>
                <td class="highlight"><strong>Average:</strong> {{ $average === null ? '-' : number_format($average, 1) }}%</td>
                <td><strong>Points:</strong> {{ $points }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Position in Stream:</strong>
                    {{ $positionInStream ? $positionInStream.' / '.count($streamTotals) : '-' }}
                </td>
                <td>
                    <strong>Position in Class:</strong>
                    {{ $positionInClass ? $positionInClass.' / '.count($classTotals) : '-' }}
                </td>
                <td class="highlight">
                    <strong>Exam Status:</strong> 
                    <span style="color: {{ $examStatus === 'PASS' ? '#16a34a' : '#dc2626' }};">{{ $examStatus }}</span>
                </td>
            </tr>
        </table>

        @if($subjectsPassed < 4)
            <div class="note-box">
                <strong>⚠ NOTE:</strong> Student has passed fewer than 4 subjects. Additional support may be required.
                Positioning is based on total points scored (lower is better).
            </div>
        @endif
    </div>

    <!-- Grading Scale -->
    <div class="grading-scale">
        <h4>Grading Scale:</h4>
        <div class="scale-content">
            <div class="scale-item"><strong>Grade 1 (85%-100%):</strong> Strong Distinction - Exceptional performance</div>
            <div class="scale-item"><strong>Grade 2 (75%-84%):</strong> Distinction - Excellent performance</div>
            <div class="scale-item"><strong>Grade 3 (70%-74%):</strong> Strong Credit - Very good performance</div>
            <div class="scale-item"><strong>Grade 4 (65%-69%):</strong> Credit - Good performance</div>
            <div class="scale-item"><strong>Grade 5 (60%-64%):</strong> Credit - Satisfactory performance</div>
            <div class="scale-item"><strong>Grade 6 (55%-59%):</strong> Weak Credit - Fair performance</div>
            <div class="scale-item"><strong>Grade 7 (50%-54%):</strong> Pass - Acceptable performance</div>
            <div class="scale-item"><strong>Grade 8 (40%-49%):</strong> Weak Pass - Below average performance</div>
            <div class="scale-item"><strong>Grade 9 (0%-39%):</strong> Fail - Unsatisfactory performance</div>
        </div>
    </div>

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-grid">
            <div class="signature-item">
                <div class="signature-line"></div>
                <div class="signature-label">Class Teacher</div>
            </div>
            <div class="signature-item">
                <div class="signature-line"></div>
                <div class="signature-label">Head Teacher</div>
            </div>
            <div class="signature-item">
                <div class="signature-line"></div>
                <div class="signature-label">Date</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} | This is a computer-generated document
    </div>
</body>
</html>
