@php
    $watermark = false;
@endphp

@extends('admin.layouts.printable')
@section('title', 'Student Id Card | ')

@section('head')
    <style type="text/css">
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            font-size: 14px;
        }

        /* ID Card Container */
        .id-card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            max-width: 900px;
            margin: 0 auto;
        }

        /* ID Card Styling */
        .id-card {
            background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 430px;
            height: 300px;
            padding: 15px;
            position: relative;
            overflow: hidden;
            border: 1px solid #e1e1e1;
        }

        .id-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #1a4a8f, #3a7bd5);
            border-radius: 12px 12px 0 0;
        }

        /* Header Section */
        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eaeaea;
        }

        .school-logo {
            width: 50px;
            height: 50px;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #eaeaea;
            border-radius: 5px;
            overflow: hidden;
        }

        .school-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .school-info {
            flex: 1;
        }

        .school-name {
            font-size: 16px;
            font-weight: 700;
            color: #1a4a8f;
            margin-bottom: 3px;
        }

        .school-tagline {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-title {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            color: #1a4a8f;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Card Body */
        .card-body {
            display: flex;
            gap: 15px;
        }

        .student-photo {
            width: 100px;
            height: 120px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f9f9f9;
        }

        .student-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-details {
            flex: 1;
        }

        .detail-row {
            display: flex;
            margin-bottom: 6px;
            font-size: 12px;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
            width: 100px;
            flex-shrink: 0;
        }

        .detail-value {
            color: #333;
            flex: 1;
        }

        /* Footer Section */
        .card-footer {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eaeaea;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        /* QR Code */
        .qr-code {
            position: absolute;
            bottom: 15px;
            right: 15px;
            width: 60px;
            height: 60px;
            border: 1px solid #eaeaea;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
        }

        .qr-code img {
            max-width: 100%;
            max-height: 100%;
        }

        /* Signature Area */
        .signature-area {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            font-size: 10px;
        }

        .student-signature, .authority-signature {
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            width: 45%;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .id-card {
                box-shadow: none;
                border: 1px solid #000;
                page-break-inside: avoid;
                margin: 10px;
            }
            
            .id-card-container {
                max-width: 100%;
                gap: 10px;
            }

            /* @page { */
                /* margin: 0.5cm; */
                /* size: 5in 3.5in; */
                /* size: 5in 6.5in; */
            /* } */
        }

        /* Batch printing layout */
        .batch-print {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            padding: 20px;
        }

        @media print and (orientation: landscape) {
            .batch-print {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endsection

@section('content')
    <div class="id-card-container">
        <!-- Front of ID Card -->
        <div class="id-card">
            <div class="card-header">
                <div class="school-logo">
                    <img src="{{ tenancy()->tenant->system_info['general']['logo'] ? route('system-setting.logo') : URL('/img/logo-1.png') }}" alt="School Logo">
                </div>
                <div class="school-info">
                    <div class="school-name">{{ tenancy()->tenant->system_info['general']['title'] ?? 'School Name' }}</div>
                    <div class="school-tagline">Montessori to Matric</div>
                </div>
            </div>
            
            <div class="card-title">Student Identity Card</div>
            
            <div class="card-body">
                <div class="student-photo">
                    <img 
                        src="{{ url('students/image/' . $student->id) }}" 
                        onerror="this.onerror=null; this.src='{{ asset('img/avatar.jpg') }}';" 
                        alt="Student Photo"
                    >
                </div>
                
                <div class="student-details">
                    <div class="detail-row">
                        <div class="detail-label">Name:</div>
                        <div class="detail-value">{{ $student->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">GR No:</div>
                        <div class="detail-value">{{ $student->gr_no ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Father Name:</div>
                        <div class="detail-value">{{ $student->father_name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Session:</div>
                        <div class="detail-value">{{ $student->AcademicSession->title ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Issued Date:</div>
                        <div class="detail-value">{{ $student->created_at->format('F j, Y') }}</div>
                    </div>
                </div>
                
                {{-- <div class="qr-code">
                    <img src="http://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=student-{{ $student->id }}" alt="QR Code">
                </div> --}}
            </div>
            
            <div class="card-footer">
                {{ tenancy()->tenant->system_info['general']['address'] ?? 'School Address' }} | 
                Tel: {{ tenancy()->tenant->system_info['general']['contact_no'] ?? 'N/A' }}
            </div>
        </div>
        
        <!-- Back of ID Card -->
        <div class="id-card">
            <div class="card-header">
                <div class="school-logo">
                    <img src="{{ tenancy()->tenant->system_info['general']['logo'] ? route('system-setting.logo') : URL('/img/logo-1.png') }}" alt="School Logo">
                </div>
                <div class="school-info">
                    <div class="school-name">{{ tenancy()->tenant->system_info['general']['title'] ?? 'School Name' }}</div>
                    <div class="school-tagline">Student Information</div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="student-details" style="width: 100%;">
                    <div class="detail-row">
                        <div class="detail-label">Phone No:</div>
                        <div class="detail-value">{{ $student->Guardian->phone ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Address:</div>
                        <div class="detail-value">{{ $student->address ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="detail-row" style="margin-top: 10px;">
                        <div class="detail-label">Issuing Authority:</div>
                        <div class="detail-value">{{ tenancy()->tenant->system_info['general']['title'] ?? 'School Authority' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="signature-area">
                <div class="student-signature">
                    Student Signature
                </div>
                <div class="authority-signature">
                    Authorized Signature
                </div>
            </div>
            
            <div class="card-footer" style="margin-top: 15px;">
                <strong>IF THIS CARD IS FOUND, PLEASE RETURN TO THE NEAREST POST OFFICE OR CONTACT THE SCHOOL</strong>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Auto-print functionality (optional)
        window.onload = function() {
            window.print();
        }

    </script>
@endsection