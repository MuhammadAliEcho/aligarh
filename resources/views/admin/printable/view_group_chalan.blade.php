@extends('admin.layouts.printable')

@section('title', 'Student Group Challan | ')

@section('head')
<style type="text/css">
    body {
        padding: 0 10px;
        margin: 0;
        font-size: 12px;
        font-family: 'Arial', sans-serif;
        color: #000;
    }

    .invoice-title h2,
    .invoice-title h3 {
        display: inline-block;
    }

    .table {
        width: 100%;
        margin-top: 2px;
        border-collapse: collapse;
    }

    .table > tbody > tr > .no-line {
        border-top: none;
    }

    .table > thead > tr > .no-line {
        border-bottom: none;
    }

    .table > tbody > tr > .thick-line {
        border-top: 2px solid black;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid black !important;
        padding: 2px 3px;
        vertical-align: middle;
    }

    .table > tbody > tr > td {
        padding: 2px 3px;
    }
    /* .table > tbody > tr > th {
        padding: 2px 3px;
    } */

    #stdcopy {
        padding: 0px 5px;
    }

    a[href]:after {
        content: none;
    }

    h4.text-danger {
        margin-top: 10px;
        margin-bottom: 10px;
        padding: 4px;
        font-weight: bold;
        font-size: 16px;
        background: #f9f9f9;
    }

    .container-fluid {
        padding-left: 5px;
        display: flex; /* Arrange copies in a single row */
        flex-wrap: nowrap; /* Prevent wrapping to new lines */
        gap: 10px; /* Space between copies */
    }

    /* Fix for v-for loop to prevent overlap and align in one row */
    .row {
        border: 1px solid black;
        padding: 2px;
        width: 507px;
        box-sizing: border-box;
        flex: 0 0 auto; /* Prevent shrinking or growing */
    }

    /* Ensuring tables take full width and look neat */
    table {
        width: 100%;
        border-spacing: 0;
    }

    td u,
    th u {
        text-decoration: underline;
    }

    ol {
        padding-left: 15px;
        margin-top: 10px;
        font-size: 10px;
    }

    ol li {
        margin-bottom: 3px;
    }

    p {
        margin: 0;
        padding: 0;
    }

    /* Optional: For better print spacing */
    @media print {
        body {
            margin: 0;
            padding: 0;
        }

        .row {
            page-break-inside: avoid;
        }
    }
</style>
@endsection

@section('content')
    <div class="container-fluid" style="padding-left: 5px;">
        <div v-for="(copy, index) in copies" :key="index" class="row"
            style="border: 1px solid black; margin-right: 15px; width: 507px;">
            <div id="address" style="width: 500px;">
                <table style="width: 420px">
                    <tbody>
                        <tr>
                            <td rowspan="3" style="padding: 5px">
                                <img alt="image" width="80px"
                                    src="{{ tenancy()->tenant->system_info['general']['logo'] ? route('system-setting.logo') : URL('/img/logo-1.png') }}">
                            </td>
                            <td>
                                <h2 class="text-center text-success">{{ tenancy()->tenant->system_info['general']['name'] }}
                                </h2>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">{{ tenancy()->tenant->system_info['general']['address'] }}. Tel
                                {{ tenancy()->tenant->system_info['general']['contact_no'] }}</td>
                        </tr>
                    </tbody>
                </table>
                <table style="width: 500px">
                    <tbody>
                        <tr style="border-top:1px solid black">
                            <td>{{ tenancy()->tenant->system_info['general']['bank']['name'] }}</td>
                            <td rowspan="3" style="padding-top: 10px">
                                <img alt="image" src="{{ URL('/img/bank.png') }}" style="width: 43px;">
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

            <h4 class="text-center text-danger" style="width: 500px; border:1px solid black"> @{{ copy }} </h4>

            <div id="stdcopy">
                <table>
                    <tbody>
                        <tr>
                            <td width="250px">Bill No. <u>@{{ invoiceIds }}</u></td>
                            <td width="250px">Issue Date. <u>@{{ formatDate(new Date()) }}</u></td>
                        </tr>
                        <tr>
                            {{-- <td><span class="label label-info hidden-print">GROUP INVOICE</span></td> --}}
                            <td>Due Date. <u>@{{ dueDate }}</u></td>
                        </tr>
                        <tr>
                            <td>Students. <u>@{{ studentNames }}</u></td>
                            <td>Guardian. <u>{{ $guardian->name }}</u></td>
                        </tr>
                        <tr>
                            <td>Class. <u>@{{ classNames }}</u></td>
                            <td>Total Students. <u>@{{ totalStudents }}</u></td>
                        </tr>
                        <tr>
                            <td colspan="2">Fee for the month. <u>@{{ uniqueMonths | join }}</u></td>
                        </tr>
                    </tbody>
                </table>

                <div style="min-height: 255px">
                    <table class="table table-bordered">
                        <tbody>
                            <tr style="background: blue; color: white;">
                                <th width="300px">
                                    <span>Particulars</span>
                                </th>
                                <th width="200px">Amount</th>
                            </tr>

                            <tr v-for="(amount, feeName) in consolidatedFees">
                                <td>@{{ feeName }}</td>
                                <td>@{{ amount }}</td>
                            </tr>

                            <tr v-if="totalDiscount > 0">
                                <th>Discount</th>
                                <th>@{{ totalDiscount }}</th>
                            </tr>

                            <tr>
                                <th class="text-right">Payable within due date</th>
                                <th>@{{ totalPayable }}/-</th>
                            </tr>
                            <tr>
                                <td class="text-right">Payable after due date</td>
                                <td>@{{ totalPayable + (150 * totalStudents) }}/-</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="width: 500px;margin-top: 15px;">Amount In Words: <u>@{{ inwords() }}</u></p>
                </div>

                <p style="margin-top: 20px; margin-bottom: 5px; border-bottom: 1px solid">Accountant Signature</p>

                <ol style="margin-bottom: 0px">
                    @php
                        $terms = tenancy()->tenant->system_info['general']['chalan_term_and_Condition'];
                        $formatted_terms = nl2br(e($terms));
                    @endphp
                    {!! $formatted_terms !!}
                </ol>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
@section('vue')
    <script type="text/javascript">
        var app = new Vue({
            el: '#app',
            data: {
                groupInvoices: {!! json_encode($groupInvoice, JSON_NUMERIC_CHECK) !!},
                guardian: {!! json_encode($guardian, JSON_NUMERIC_CHECK) !!},
                consolidatedFees: {!! json_encode($consolidatedFees) !!},
                totalPayable: {!! json_encode($totalAmount) !!},
                totalDiscount: {!! json_encode($totalDiscount) !!},
                studentNamesForNull: {!! json_encode($studentNames) !!},
                classNamesForNull: {!! json_encode($classNames) !!},
                uniqueMonths: {!! json_encode($uniqueMonths) !!},
                totalStudents: {!! json_encode($invoiceCount) !!},
                currentDate: '',
                dueDate: {!! json_encode($dueDate) !!},
                copies: ['Student\'s Copy', 'School\'s Copy']
                // copies: ['Student\'s Copy', 'School\'s Copy', 'Bank\'s Copy']
            },
            mounted(){
                setTimeout(() => {
                    window.print();
                }, 800);
            },
            computed: {
                invoiceIds: function() {
                    return this.groupInvoices.map((group) => group?.due_invoice?.id).filter(id => id != null).join(', ');
                },
                filteredInvoices() {
                    return this.groupInvoices.filter(group => group?.due_invoice);
                },
                studentNames() {
                    if (this.hasAnyDueInvoice) {
                        return this.filteredInvoices.map(group => group.name).join(', ');
                    } else {
                        return this.studentNamesForNull.join(', ');
                    }
                },
                classNames(){
                    if (this.hasAnyDueInvoice) {
                        return this.filteredInvoices.map(group => group.std_class.prifix).join(', ');
                    } else {
                        return this.classNamesForNull.join(', ');
                    }
                },
                hasAnyDueInvoice(){
                    return this.groupInvoices.some(group => group?.due_invoice);
                } 

            },
            filters: {
                join: function(array) {
                    return array?.join(', ');
                }
            },
            methods: {
                inwords: function() {
                    var inWords = toWords(this.totalPayable);
                    return inWords.charAt(0).toUpperCase() + inWords.slice(1);
                },

                formatDate: function(dateString) {
                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString(undefined, options);
                }
            }
        });
    </script>
@endsection
