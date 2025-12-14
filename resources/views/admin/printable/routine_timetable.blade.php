@extends('admin.layouts.printable')

@section('head')
<style>
@media print {
    .print-header {
        margin-bottom: 20px;
    }
    .section-break {
        page-break-before: always;
        margin-top: 20px;
    }
    .section-break.no-break {
        page-break-before: avoid;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    td {
      white-space: normal;
      word-wrap: break-word;
    }
}
</style>
@endsection

@section('content')
<div class="print-header text-center">
    <h2>Class Routine Timetable</h2>
    <h3>Class: {{ $class->name }}</h3>
</div>

@foreach($sections as $section)
<div class="section-break {{ $loop->first ? 'no-break' : '' }}">
    <h4>Section: {{ $section->name }}</h4>
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
                          <div class="tw-flex tw-flex-wrap tw-gap-2">
                              @foreach($routines['section_' . $section->id][$day] as $routin)
                                  <span class="tw-inline-block tw-border tw-border-gray-700 tw-rounded tw-px-2 tw-py-1 tw-text-sm">
                                      <strong>{{ $routin->subject_name }}</strong>
                                      <span class="tw-text-xs">
                                          ({{ $routin->from_time }} - {{ $routin->to_time }})
                                      </span>
                                      <br>
                                      <span class="tw-text-xs tw-italic">
                                          {{ $routin->teacher_name }}
                                      </span>
                                  </span>
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