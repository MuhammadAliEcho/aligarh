@extends('admin.layouts.printable')
@section('title', 'Bulk Challan | ')

@section('head')
    <style>
        body {
            font-size: 13px;
        }

        .invoice-page {
            page-break-after: always;
            width: 100%;
            margin: 0 auto;
            border: none;
        }

        .invoice-row {
            display: flex;
            width: 100%;
        }

        .invoice-copy {
            width: 515px;
            border: 1px solid black;
            padding: 10px;
            box-sizing: border-box;
            min-height: 842px;
            position: relative;
            font-size: 14px;
            margin-right: 10px
        }

        .invoice-header h2,
        .invoice-header h3 {
            margin: 0;
        }

        .table {
            width: 100%;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 4px;
        }

        .invoice-title {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 10px;
            font-weight: bold;
            color: darkred;
        }

        .logo {
            width: 80px;
        }

        .bank-logo {
            width: 43px;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid black !important;
            padding: 0px;
        }

        .table>tbody>tr>td {
            padding: 1px;
        }

        .fz-12 {
            font-size: 12px;
        }

        .fz-13 {
            font-size: 13px;
        }

        .fz-14 {
            font-size: 14px;
        }

        .fz-16 {
            font-size: 16px;
        }
    </style>
@endsection

@section('content')
    <div class="">

        @foreach ($invoices as $index => $invoice)
            @php
                $student = $students[$index] ?? null;
                $copies = ['Student\'s Copy', 'School\'s Copy', 'Bank\'s Copy'];
            @endphp

            @if (!$student)
                <div class="invoice-page">
                    <p><strong>No student data found for Invoice #{{ $invoice->id ?? 'N/A' }}</strong></p>
                </div>
                @continue
            @endif

            <div class="invoice-page">
                <div class="invoice-row">

                    @foreach ($copies as $copyTitle)
                        <div class="invoice-copy" style="width: 420px;">

                            {{-- Header --}}
                            <table style="width: 420px;">
                                <tr>
                                    <td rowspan="2" style="padding: 5px;width: 80px;">
                                        <img src="{{ tenancy()->tenant->system_info['general']['logo'] ? route('system-setting.logo') : URL('/img/logo-1.png') }}"
                                            class="logo">
                                    </td>
                                    <td>
                                        <h2 class="text-center text-success">
                                            {{ tenancy()->tenant->system_info['general']['name'] }}</h2>
                                    </td>
                                    <td rowspan="2" style="width: 85px;"></td>
                                </tr>
                                <tr>
                                    <td class="text-center">{{ tenancy()->tenant->system_info['general']['address'] }}. Tel
                                        {{ tenancy()->tenant->system_info['general']['contact_no'] }}</td>
                                </tr>
                            </table>

                            {{-- Bank Info --}}
                            <table style="border-top: 1px solid black; width: 408px;">
                                <tr>
                                    <td>{{ tenancy()->tenant->system_info['general']['bank']['name'] }}</td>
                                    <td rowspan="3"><img src="{{ URL('/img/bank.png') }}" class="bank-logo"></td>
                                </tr>
                                <tr>
                                    <td>{{ tenancy()->tenant->system_info['general']['bank']['address'] }}</td>
                                </tr>
                                <tr>
                                    <td>Account No. {{ tenancy()->tenant->system_info['general']['bank']['account_no'] }}
                                    </td>
                                </tr>
                            </table>

                            <h4 class="text-center text-danger" style="width: 404px; border: 1px solid black;">
                                {{ $copyTitle }}</h4>

                            {{-- Student Info --}}
                            <table style="margin-top: 10px;">
                                <tr>
                                    <td width="250px">R.No. <u>{{ $invoice->id }}</u></td>
                                    <td width="250px">Issue Date:
                                        <u>{{ \Carbon\Carbon::parse($invoice->created_at)->format('F j, Y') }}</u></td>
                                </tr>
                                <tr>
                                    <td>
                                        @if ($invoice->paid_amount)
                                            <span class="label label-success hidden-print">PAID</span>
                                        @else
                                            <span class="label label-success hidden-print">UNPAID</span>
                                        @endif
                                    </td>
                                    <td>Due Date: <u>{{ $invoice->due_date }}</u></td>
                                </tr>
                                <tr>
                                    <td>Name: <u>{{ $student->name ?? 'N/A' }}</u></td>
                                    <td>Father's Name: <u>{{ $student->father_name ?? 'N/A' }}</u></td>
                                </tr>
                                <tr>
                                    <td>Class: <u>{{ optional($student->std_class)->name ?? 'N/A' }}</u></td>
                                    <td>G.R No.: <u>{{ $student->gr_no ?? 'N/A' }}</u></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Fee for the month:
                                        <u>
                                            @if ($invoice->InvoiceMonths && count($invoice->InvoiceMonths) > 0)
                                                @foreach ($invoice->InvoiceMonths as $month)
                                                    {{ $month->month }}{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                            @else
                                                N/A
                                            @endif
                                        </u>
                                    </td>
                                </tr>
                            </table>

                            {{-- Fee Table --}}
                            <table class="table table-bordered">
                                <tr style="background-color: blue; color: white;">
                                    <th>Particulars</th>
                                    <th>Amount</th>
                                </tr>
                                @foreach ($invoice->InvoiceDetail as $detail)
                                    <tr>
                                        <td>{{ $detail->fee_name }}</td>
                                        <td>{{ $detail->amount }}</td>
                                    </tr>
                                @endforeach

                                @if ($invoice->discount > 0)
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
                                    <td class="text-right">After due date</td>
                                    <td>{{ $invoice->net_amount + $invoice->late_fee }}/-</td>
                                </tr>
                            </table>

                            <p style="margin-top: 10px; text-transform: capitalize;" class="inwords fz-12">
                                Amount in words: <u>{{ $invoice->net_amount }}</u>
                            </p>

                            {{-- Signature and Terms --}}
                            <p style="margin-top: 20px; margin-bottom: 5px; border-bottom: 1px solid;">Accountant Signature
                            </p>

                            <div>
                                @php
                                    $terms =
                                        tenancy()->tenant->system_info['general']['chalan_term_and_Condition'] ?? '';
                                @endphp
                                {!! nl2br(e($terms)) !!}
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach

    </div>

    <script>
        window.onload = function() {
            $('.inwords').each(function() {
                const text = $(this).text().trim();
                const numMatch = text.match(/\d+/);
                if (numMatch) {
                    const num = parseInt(numMatch[0]);
                    const words = toWords(num);
                    $(this).text('Amount in words: ' + words);
                }
            });
            window.print();
        };
    </script>
@endsection
