@extends('admin.layouts.printable')
@section('title', 'Student Challan | ')

@section('head')
<style type="text/css">
    .invoice-title h2,
    .invoice-title h3 {
        display: inline-block;
    }

    .table {
        width: auto;
        margin-top: 15px;
    }

    .table>tbody>tr>.no-line {
        border-top: none;
    }

    .table>thead>tr>.no-line {
        border-bottom: none;
    }

    .table>tbody>tr>.thick-line {
        border-top: 1px solid;
    }

    body {
        padding: 0px 10px;
        margin: 0px;
        font-size: 15px;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid black !important;
        padding: 0px;
    }

    .table>tbody>tr>td {
        padding: 1px;
    }

    a[href]:after {
        content: none;
    }

    /* Print page break for each invoice */
    .invoice-page {
        page-break-after: always;
        break-after: page;
        width: 507px;
        margin: 0 auto 40px auto;
        border: 1px solid black;
        padding: 10px;
        min-height: 1000px;
        position: relative;
    }

    .invoice-header,
    .invoice-footer {
        width: 500px;
        margin: 0 auto;
    }
</style>
@endsection

@section('content')
<div class="container-fluid" style="padding-left: 5px;">

    @foreach ($invoices as $index => $invoice)
        @php
            $student = $students[$index] ?? null;
        @endphp

        @if (!$student)
            <div class="invoice-page">
                <p><strong>No student data found for Invoice #{{ $invoice->id ?? 'N/A' }}</strong></p>
            </div>
            @continue
        @endif

        <div class="invoice-page" id="invoice-{{ $index }}">

            {{-- Address Section --}}
            <div class="invoice-header" id="address-{{ $index }}">
                <table style="width: 420px;">
                    <tbody>
                        <tr>
                            <td rowspan="3" style="padding: 5px;">
                                <img alt="logo" width="80px"
                                    src="{{ tenancy()->tenant->system_info['general']['logo'] ? route('system-setting.logo') : URL('/img/logo-1.png') }}">
                            </td>
                            <td>
                                <h2 class="text-center text-success">{{ tenancy()->tenant->system_info['general']['name'] }}</h2>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                {{ tenancy()->tenant->system_info['general']['address'] }}. Tel
                                {{ tenancy()->tenant->system_info['general']['contact_no'] }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table style="width: 500px;">
                    <tbody>
                        <tr style="border-top:1px solid black;">
                            <td>{{ tenancy()->tenant->system_info['general']['bank']['name'] }}</td>
                            <td rowspan="3" style="padding-top: 10px;">
                                <img alt="bank-logo" src="{{ URL('/img/bank.png') }}" style="width: 43px;">
                            </td>
                        </tr>
                        <tr>
                            <td>{{ tenancy()->tenant->system_info['general']['bank']['address'] }}</td>
                        </tr>
                        <tr>
                            <td>Account No. {{ tenancy()->tenant->system_info['general']['bank']['account_no'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Student's Copy --}}
            <h4 class="text-center text-danger invoice-header" style="border:1px solid black; margin-top: 10px;">Student's Copy</h4>

            <div class="invoice-header" id="stdcopy-{{ $index }}">
                <table style="margin-top: 15px; width: 100%;">
                    <tbody>
                        <tr>
                            <td width="250px">R.No. <u>{{ $invoice->id }}</u></td>
                            <td width="250px">Issue Date. <u>{{ \Carbon\Carbon::parse($invoice->created_at)->format('F j, Y') }}</u></td>
                        </tr>
                        <tr>
                            <td>
                                @if($invoice->paid_amount)
                                    <span class="label label-success hidden-print">PAID</span>
                                @else
                                    <span class="label label-danger hidden-print">UNPAID</span>
                                @endif
                            </td>
                            <td>Due Date. <u>{{ $invoice->due_date }}</u></td>
                        </tr>
                        <tr>
                            <td>Name. <u>{{ $student->name ?? 'N/A' }}</u></td>
                            <td>Father's Name. <u>{{ $student->father_name ?? 'N/A' }}</u></td>
                        </tr>
                        <tr>
                            <td>Class. <u>{{ optional($student->std_class)->name ?? 'N/A' }}</u></td>
                            <td>G.R No. <u>{{ $student->gr_no ?? 'N/A' }}</u></td>
                        </tr>
                        <tr>
                            <td colspan="2">Fee for the month. <u>
                                @if($invoice->InvoiceMonths && count($invoice->InvoiceMonths) > 0)
                                    @foreach($invoice->InvoiceMonths as $month)
                                        {{ $month->month }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </u></td>
                        </tr>
                    </tbody>
                </table>

                <div style="height: 350px; overflow: hidden;">
                    <table class="table table-bordered" style="width: 100%;">
                        <tbody>
                            <tr style="background: blue; color: white;">
                                <th width="300px">Particulars</th>
                                <th width="200px">Amount</th>
                            </tr>

                            @if($invoice->InvoiceDetail && count($invoice->InvoiceDetail) > 0)
                                @foreach($invoice->InvoiceDetail as $detail)
                                    <tr>
                                        <td>{{ $detail->fee_name }}</td>
                                        <td>{{ $detail->amount }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            @if($invoice->discount > 0)
                                <tr>
                                    <th>Discount</th>
                                    <th>{{ $invoice->discount }}</th>
                                </tr>
                            @endif

                            <tr>
                                <th class="text-right">Payable within due date</th>
                                <th>{{ $invoice->net_amount }}/-</th>
                            </tr>
                            <tr>
                                <td class="text-right">Payable after due due date</td>
                                <td>{{ $invoice->net_amount + $invoice->late_fee }}/-</td>
                            </tr>
                        </tbody>
                    </table>

                    @php
                        // Simple number to words conversion (you can create a helper function)
                        // function numberToWords($number) {
                        //     $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                        //     return ucfirst($formatter->format($number));
                        // }
                    @endphp
                    <p style="margin-top: 15px;">Amount In Words: <u>{{ $invoice->net_amount }}</u></p>
                    {{-- <p style="margin-top: 15px;">Amount In Words: <u>{{ numberToWords($invoice->net_amount) }}</u></p> --}}
                </div>

                <p style="margin-top: 20px; margin-bottom: 5px; border-bottom: 1px solid;">Accountant Signature</p>

                <ol style="margin-bottom: 0px;">
                    @php
                        $terms = tenancy()->tenant->system_info['general']['chalan_term_and_Condition'] ?? '';
                        $formatted_terms = nl2br(e($terms));
                    @endphp
                    {!! $formatted_terms !!}
                </ol>
            </div>

            {{-- School's Copy --}}
            <div class="invoice-header" style="margin-top: 40px; border-top: 1px solid black; padding-top: 10px;">
                <h4 class="text-center text-danger" style="border:1px solid black;">School's Copy</h4>
                
                {{-- Repeat address section for school copy --}}
                <table style="width: 420px;">
                    <tbody>
                        <tr>
                            <td rowspan="3" style="padding: 5px;">
                                <img alt="logo" width="80px"
                                    src="{{ tenancy()->tenant->system_info['general']['logo'] ? route('system-setting.logo') : URL('/img/logo-1.png') }}">
                            </td>
                            <td>
                                <h2 class="text-center text-success">{{ tenancy()->tenant->system_info['general']['name'] }}</h2>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                {{ tenancy()->tenant->system_info['general']['address'] }}. Tel
                                {{ tenancy()->tenant->system_info['general']['contact_no'] }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table style="width: 500px;">
                    <tbody>
                        <tr style="border-top:1px solid black;">
                            <td>{{ tenancy()->tenant->system_info['general']['bank']['name'] }}</td>
                            <td rowspan="3" style="padding-top: 10px;">
                                <img alt="bank-logo" src="{{ URL('/img/bank.png') }}" style="width: 43px;">
                            </td>
                        </tr>
                        <tr>
                            <td>{{ tenancy()->tenant->system_info['general']['bank']['address'] }}</td>
                        </tr>
                        <tr>
                            <td>Account No. {{ tenancy()->tenant->system_info['general']['bank']['account_no'] }}</td>
                        </tr>
                    </tbody>
                </table>

                {{-- Repeat student copy content for school copy --}}
                <table style="margin-top: 15px; width: 100%;">
                    <tbody>
                        <tr>
                            <td width="250px">R.No. <u>{{ $invoice->id }}</u></td>
                            <td width="250px">Issue Date. <u>{{ \Carbon\Carbon::parse($invoice->created_at)->format('F j, Y') }}</u></td>
                        </tr>
                        <tr>
                            <td>
                                @if($invoice->paid_amount)
                                    <span class="label label-success hidden-print">PAID</span>
                                @else
                                    <span class="label label-danger hidden-print">UNPAID</span>
                                @endif
                            </td>
                            <td>Due Date. <u>{{ $invoice->due_date }}</u></td>
                        </tr>
                        <tr>
                            <td>Name. <u>{{ $student->name ?? 'N/A' }}</u></td>
                            <td>Father's Name. <u>{{ $student->father_name ?? 'N/A' }}</u></td>
                        </tr>
                        <tr>
                            <td>Class. <u>{{ optional($student->std_class)->name ?? 'N/A' }}</u></td>
                            <td>G.R No. <u>{{ $student->gr_no ?? 'N/A' }}</u></td>
                        </tr>
                        <tr>
                            <td colspan="2">Fee for the month. <u>
                                @if($invoice->InvoiceMonths && count($invoice->InvoiceMonths) > 0)
                                    @foreach($invoice->InvoiceMonths as $month)
                                        {{ $month->month }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </u></td>
                        </tr>
                    </tbody>
                </table>

                <div style="height: 350px; overflow: hidden;">
                    <table class="table table-bordered" style="width: 100%;">
                        <tbody>
                            <tr style="background: blue; color: white;">
                                <th width="300px">Particulars</th>
                                <th width="200px">Amount</th>
                            </tr>

                            @if($invoice->InvoiceDetail && count($invoice->InvoiceDetail) > 0)
                                @foreach($invoice->InvoiceDetail as $detail)
                                    <tr>
                                        <td>{{ $detail->fee_name }}</td>
                                        <td>{{ $detail->amount }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            @if($invoice->discount > 0)
                                <tr>
                                    <th>Discount</th>
                                    <th>{{ $invoice->discount }}</th>
                                </tr>
                            @endif

                            <tr>
                                <th class="text-right">Payable within due date</th>
                                <th>{{ $invoice->net_amount }}/-</th>
                            </tr>
                            <tr>
                                <td class="text-right">Payable after due date</td>
                                <td>{{ $invoice->net_amount + $invoice->late_fee }}/-</td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="margin-top: 15px;">Amount In Words: <u>{{ $invoice->net_amount }}</u></p>
                    {{-- <p style="margin-top: 15px;">Amount In Words: <u>{{ numberToWords($invoice->net_amount) }}</u></p> --}}
                </div>
            </div>

            {{-- Bank's Copy --}}
            <div class="invoice-header" style="margin-top: 20px; border-top: 1px solid black; padding-top: 10px;">
                <h4 class="text-center text-danger" style="border:1px solid black;">Bank's Copy</h4>
                
                {{-- Repeat address section for bank copy --}}
                <table style="width: 420px;">
                    <tbody>
                        <tr>
                            <td rowspan="3" style="padding: 5px;">
                                <img alt="logo" width="80px"
                                    src="{{ tenancy()->tenant->system_info['general']['logo'] ? route('system-setting.logo') : URL('/img/logo-1.png') }}">
                            </td>
                            <td>
                                <h2 class="text-center text-success">{{ tenancy()->tenant->system_info['general']['name'] }}</h2>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                {{ tenancy()->tenant->system_info['general']['address'] }}. Tel
                                {{ tenancy()->tenant->system_info['general']['contact_no'] }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table style="width: 500px;">
                    <tbody>
                        <tr style="border-top:1px solid black;">
                            <td>{{ tenancy()->tenant->system_info['general']['bank']['name'] }}</td>
                            <td rowspan="3" style="padding-top: 10px;">
                                <img alt="bank-logo" src="{{ URL('/img/bank.png') }}" style="width: 43px;">
                            </td>
                        </tr>
                        <tr>
                            <td>{{ tenancy()->tenant->system_info['general']['bank']['address'] }}</td>
                        </tr>
                        <tr>
                            <td>Account No. {{ tenancy()->tenant->system_info['general']['bank']['account_no'] }}</td>
                        </tr>
                    </tbody>
                </table>

                {{-- Repeat student copy content for bank copy --}}
                <table style="margin-top: 15px; width: 100%;">
                    <tbody>
                        <tr>
                            <td width="250px">R.No. <u>{{ $invoice->id }}</u></td>
                            <td width="250px">Issue Date. <u>{{ \Carbon\Carbon::parse($invoice->created_at)->format('F j, Y') }}</u></td>
                        </tr>
                        <tr>
                            <td>
                                @if($invoice->paid_amount)
                                    <span class="label label-success hidden-print">PAID</span>
                                @else
                                    <span class="label label-danger hidden-print">UNPAID</span>
                                @endif
                            </td>
                            <td>Due Date. <u>{{ $invoice->due_date }}</u></td>
                        </tr>
                        <tr>
                            <td>Name. <u>{{ $student->name ?? 'N/A' }}</u></td>
                            <td>Father's Name. <u>{{ $student->father_name ?? 'N/A' }}</u></td>
                        </tr>
                        <tr>
                            <td>Class. <u>{{ optional($student->std_class)->name ?? 'N/A' }}</u></td>
                            <td>G.R No. <u>{{ $student->gr_no ?? 'N/A' }}</u></td>
                        </tr>
                        <tr>
                            <td colspan="2">Fee for the month. <u>
                                @if($invoice->InvoiceMonths && count($invoice->InvoiceMonths) > 0)
                                    @foreach($invoice->InvoiceMonths as $month)
                                        {{ $month->month }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </u></td>
                        </tr>
                    </tbody>
                </table>

                <div style="height: 350px; overflow: hidden;">
                    <table class="table table-bordered" style="width: 100%;">
                        <tbody>
                            <tr style="background: blue; color: white;">
                                <th width="300px">Particulars</th>
                                <th width="200px">Amount</th>
                            </tr>

                            @if($invoice->InvoiceDetail && count($invoice->InvoiceDetail) > 0)
                                @foreach($invoice->InvoiceDetail as $detail)
                                    <tr>
                                        <td>{{ $detail->fee_name }}</td>
                                        <td>{{ $detail->amount }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            @if($invoice->discount > 0)
                                <tr>
                                    <th>Discount</th>
                                    <th>{{ $invoice->discount }}</th>
                                </tr>
                            @endif

                            <tr>
                                <th class="text-right">Payable within due date</th>
                                <th>{{ $invoice->net_amount }}/-</th>
                            </tr>
                            <tr>
                                <td class="text-right">Payable after due date</td>
                                <td>{{ $invoice->net_amount + $invoice->late_fee }}/-</td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="margin-top: 15px;">Amount In Words: <u>{{ $invoice->net_amount }}</u></p>
                    {{-- <p style="margin-top: 15px;">Amount In Words: <u>{{ numberToWords($invoice->net_amount) }}</u></p> --}}
                </div>
            </div>

        </div> {{-- End of invoice-page --}}
    @endforeach

</div>

<script>
    // Auto print when page loads
    window.onload = function() {
        window.print();
    };
</script>
@endsection