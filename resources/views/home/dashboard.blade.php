@extends('layouts.layout')

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="row">

                @include('home.dashboard_card', [
                    'bg_class' => 'bg-info',
                    'value_display' => $count_new_students . '/' . $count_all_students,
                    'caption' => 'New Students',
                    'link_caption' => 'View New Students',
                    'link_redirect' => route('view_students', ['new']),
                ])

                @include('home.dashboard_card', [
                    'bg_class' => 'bg-warning',
                    'value_display' => $total_revenue,
                    'caption' => 'Total Revenue',
                    'link_caption' => 'View Payments',
                    'link_redirect' => route('feature_not_avail'),
                ])

                @include('home.dashboard_card', [
                    'bg_class' => 'bg-danger',
                    'value_display' => $total_pending_amount,
                    'caption' => 'Pending Payments',
                    'link_caption' => 'Pending Payments',
                    'link_redirect' => route('view_students', ['unpaid']),
                ])

                @include('home.dashboard_card', [
                    'bg_class' => 'bg-primary',
                    'value_display' => $total_pending_student_count . "/" . $studentsIdsPendingCompletion,
                    'caption' => 'Unpaid/Incomplete Students',
                    'link_caption' => 'Unpaid Students',
                    'link_redirect' => route('view_students', ['unpaid']),
                ])
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Admissions</h3>
                            <!-- <div class="card-tools">
                                <div id="controls">
                                    <select id="monthSelect">
                                        <option value="Feature" selected>Feature Not Available</option>
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select>
                                </div>
                            </div> -->
                        </div>
                        <div class="card-body table-responsive p-0">
                            <canvas id="admissionsChart" width="400" height="200"></canvas>
                        </div>
                    </div>

                </div>

            </div>
        </div>


    </section>
    <script src="{{ asset('js/chart.js') }}"></script>
    <script src="{{ asset('js/chartjs-plugin-datalabels.js') }}"></script>
    <script>
        $(document).ready(function() {
            const monthSelected = $('#monthSelect').val();
            preloader.load();
            $.ajax({
                type: "post",
                url: "{{ url('get_graph_data') }}",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    preloader.stop();
                    // console.log(response)
                    // Filter out entries with null employee names
                    const filteredData = response.filter(entry => entry.employee_name !== null);

                    // Filter out entries with null employee names
                    const filteredResponse = response.filter(entry => entry.employee_name !== null);

                    // Prepare labels and data for the chart
                    const labels = filteredResponse.map(entry => entry.employee_name);
                    const studentCounts = filteredResponse.map(entry => entry.student_count);

                    const ctx = document.getElementById('admissionsChart').getContext('2d');

                    const chartData = {
                        labels: labels,
                        datasets: [{
                            label: 'Number of Students',
                            data: studentCounts,
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(255, 159, 64, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(201, 203, 207, 0.6)'
                            ],
                            borderColor: 'rgba(255, 255, 255, 1)',
                            borderWidth: 1
                        }]
                    };

                    const config = {
                        type: 'bar', // Change to 'doughnut' for a doughnut chart
                        data: chartData,
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return `${tooltipItem.label}: ${tooltipItem.raw} students`;
                                        }
                                    }
                                }
                            }
                        }
                    };

                    const admissionsChart = new Chart(ctx, config);
                }
            });




        });


        // Update chart based on selected month
        // document.getElementById('monthSelect').addEventListener('change', function() {
        //     const selectedMonth = this.value;
        //     console.log(selectedMonth);

        //     admissionsChart.data.datasets[0].data = monthlyData[selectedMonth];
        //     admissionsChart.update();
        // });
    </script>
@endsection
