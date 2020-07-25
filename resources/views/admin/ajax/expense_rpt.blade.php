                                <div class="row">
                                  <div class="col-xs-6 col-sm-6 col-md-6">
                                    <h3>Expense Summary: ({{ $Input['from_date'] }} - {{ $Input['to_date'] }})</h3>

                                    <table class="table table-bordered">
                                      <tbody>
                                        @if($sum_salary > 0)
                                        <tr>
                                          <th>Salary</th>
                                          <th>{{ $sum_salary }}</th>
                                        </tr>
                                        @endif
                                        @if($sum_bills > 0)
                                        <tr>
                                          <th>Bills</th>
                                          <th>{{ $sum_bills }}</th>
                                        </tr>
                                        @endif
                                        @if($sum_maintenance > 0)
                                        <tr>
                                          <th>Maintenance</th>
                                          <th>{{ $sum_maintenance }}</th>
                                        </tr>
                                        @endif
                                        @if($sum_others > 0)
                                        <tr>
                                          <th>Others</th>
                                          <th>{{ $sum_others }}</th>
                                        </tr>
                                        @endif
                                      </tbody>
                                    </table> 

                                  </div>
                                  @if($Input['type'] == '')
                                  <div class="col-xs-6 col-sm-6 col-md-6">
                                  <br>
                                    <canvas id="doughnutChart"></canvas>
                                    <h3 style="margin-left: 70px" class="no-print"> Total: {{ $summary->sum('amount')  }} /=</h3>
                                  </div>
                                  @endif
                                </div>
                                  <div class="hr-line-dashed"></div>
                                  <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                      <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Desc</th>
                                        <th>Amount</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach($summary AS $row)
                                      <tr>
                                        <td>{{ $row->id }}</td>
                                        <td>{{ $row->date }}</td>
                                        <td>{{ $row->type }}</td>
                                        <td>{{ $row->description }}</td>
                                        <td>{{ $row->amount }}</td>
                                      </tr>
                                      @endforeach
                                    </tbody>
                                    <tfoot>
                                      <tr>
                                        <th colspan="4" class="text-right">Total: </th>
                                        <th>{{ $summary->sum('amount')  }}</th>
                                      </tr>
                                    </tfoot>
                                  </table>

                                  @if($Input['type'] == '')

                                  <script type="text/javascript">
                                    $(document).ready(function(){
                                        var doughnutData = [
                                            {
                                                value: {{ $sum_salary }},
                                                color: "#a3e1d4",
                                                highlight: "#1ab394",
                                                label: "Salary"
                                            },
                                            {
                                                value: {{ $sum_bills }},
                                                color: "#dedede",
                                                highlight: "#1ab394",
                                                label: "Bills"
                                            },
                                            {
                                                value: {{ $sum_maintenance }},
                                                color: "#A4CEE8",
                                                highlight: "#1ab394",
                                                label: "Maintenance"
                                            },
                                            {
                                                value: {{ $sum_others }},
                                                color: "#A4CEE8",
                                                highlight: "#1ab394",
                                                label: "Ohers"
                                            }
                                        ];

                                        var doughnutOptions = {
                                            segmentShowStroke: true,
                                            segmentStrokeColor: "#fff",
                                            segmentStrokeWidth: 2,
                                            percentageInnerCutout: 45, // This is 0 for Pie charts
                                            animationSteps: 100,
                                            animationEasing: "easeOutBounce",
                                            animateRotate: true,
                                            animateScale: false
                                        };

                                        var ctx = document.getElementById("doughnutChart").getContext("2d");
                                        var DoughnutChart = new Chart(ctx, {
                                          'type': 'doughnut',
                                          data: doughnutData,
                                          options: doughnutOptions,
                                        });
                                    });
                                  </script>
                                @endif