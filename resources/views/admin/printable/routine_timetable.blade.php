@extends('admin.layouts.printable')

@section('head')
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11pt;
        color: #000;
    }

    .print-header {
        text-align: center;
        margin-bottom: 25px;
    }

    .print-header h2 {
        font-size: 16pt;
        margin: 0 0 5px 0;
    }

    .print-header h3 {
        font-size: 13pt;
        margin: 0;
        font-weight: normal;
    }

    h4 {
        font-size: 13pt;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th, td {
        border: 1px solid #000;
        padding: 8px;
        vertical-align: top;
    }

    th {
        background-color: #f0f0f0;
        font-weight: bold;
        font-size: 11pt;
    }

    td {
        font-size: 10.5pt;
        line-height: 1.4;
    }

    .schedule-container {
        display: block;
    }

    .schedule-item {
        display: inline-block;
        border: 1px solid #000;
        padding: 6px 8px;
        margin: 4px 4px 4px 0;
        font-size: 10pt;
        page-break-inside: avoid;
    }

    .schedule-item strong {
        display: block;
        font-size: 10.5pt;
        margin-bottom: 2px;
    }

    .schedule-time {
        font-size: 9.5pt;
    }

    .schedule-teacher {
        font-size: 9.5pt;
        font-style: italic;
        margin-top: 2px;
        display: block;
    }

/* ===== PRINT-ONLY STYLES ===== */
@media print {
    .section-break {
        page-break-before: always;
        margin-top: 30px;
    }

    .section-break.no-break {
        page-break-before: avoid;
    }

    .schedule-item {
        page-break-inside: avoid;
    }
}
</style>
@endsection


@section('content')
<div class="print-header text-center">
  	<h2 class="text-center">{{ tenancy()->tenant->system_info['general']['title'] }}</h2>
    <h3>Class Routine</h3>
</div>

@foreach($sections as $section)
<div class="section-break {{ $loop->first ? 'no-break' : '' }}">
    <h4>Class: {{ $class->name }}, Section: {{ $section->name }}</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Day</th>
                <th>Schedule</th>
            </tr>
        </thead>
        <tbody>
            @foreach($days as $day)
                <tr>
                    <td>{{ $day }}</td>
                    <td>
                      @if(isset($routines['section_' . $section->id][$day]))
                          <div class="schedule-container">
                            @foreach($routines['section_' . $section->id][$day] as $routin)
                                <div class="schedule-item">
                                    <strong>{{ $routin->subject_name }}</strong>
                                    <span class="schedule-time">
                                        ({{ $routin->from_time }} - {{ $routin->to_time }})
                                    </span>
                                    <span class="schedule-teacher">
                                        {{ $routin->teacher_name }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                      @else
                          -
                      @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endforeach

@endsection

@section('script')
<script>
$(document).ready(function(){
    window.print();
});
</script>
@endsection