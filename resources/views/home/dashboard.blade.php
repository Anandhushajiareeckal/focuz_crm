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

                <!-- Change University Button -->
<div class="d-flex justify-content-end mb-3">
    <button type="button" id="openUniversitiesModal" class="btn btn-dark" data-toggle="modal" data-target="#changeUniversitiesModal">
        <i class="fas fa-university"></i> Change University
    </button>
</div>


<!-- Modal -->
<div class="modal fade custom-modal" id="changeUniversitiesModal" tabindex="-1" role="dialog" aria-labelledby="changeUniversitiesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changeUniversitiesModalLabel">Select University</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body p-3">
        <!-- Search input -->
        <div class="mb-3">
          <input type="text" id="universitySearch" class="form-control" placeholder="Search universities...">
        </div>

        <!-- Scrollable Grid container for universities -->
        <div class="universities-container">
            <div class="row" id="universities_grid" data-url="{{ route('get_universities') }}">
                <div class="col-12 text-center text-muted">
                    <i class="fas fa-spinner fa-spin"></i> Loading universities...
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Styles -->
<style>
.custom-modal .modal-dialog {
        max-width: 60%; /* wider modal */
}
.universities-container {
    max-height: 70vh; /* scrollable height */
    overflow-y: auto;
    overflow-x: hidden; /* remove horizontal scroll */
    padding-right: 5px; /* for scrollbar spacing */
}

/* University Card Styles */
.university-card { 
    cursor:pointer; 
    border:1px solid #e3e3e3; 
    border-radius:8px; 
    padding:12px; 
    transition:all .2s ease; 
    text-align:center; 
    background-color:#f8f9fa; /* light background for all cards */
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.university-card:hover { 
    transform:translateY(-3px); 
    box-shadow:0 6px 18px rgba(0,0,0,0.1); 
    border-color:#007bff;
}

/* Scrollbar Styling */
.universities-container::-webkit-scrollbar {
    width: 6px;
}
.universities-container::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.2);
    border-radius: 3px;
}

/* Optional: make cards consistent height */
.university-card h6, .university-card small {
    margin:0;
}
</style>





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



<!-- Script -->
<script>
$(document).ready(function(){

    var allUniversities = [];
    var universitiesLoaded = false;

    $('#openUniversitiesModal').click(function(){
        var grid = $('#universities_grid');

        if(!universitiesLoaded){
            grid.html('<div class="col-12 text-center"><i class="fas fa-spinner fa-spin"></i> Loading universities...</div>');
            $.ajax({
                url: grid.data('url'),
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    allUniversities = response;
                    renderUniversities(allUniversities);
                    universitiesLoaded = true;
                },
                error: function(){
                    grid.html('<div class="col-12 text-danger text-center">Failed to load universities.</div>');
                }
            });
        }
    });

    function renderUniversities(universities){
        var grid = $('#universities_grid');
        if(!universities || universities.length === 0){
            grid.html('<div class="col-12 text-center text-muted">No universities found.</div>');
            return;
        }

        var html = '';
        universities.forEach(function(u){
            html += `
            <div class="col-sm-6 col-md-4 mb-3">
                <div class="university-card" data-id="${u.id}">
                    <h6>${u.name}</h6>
                    <small>${u.university_code ?? 'N/A'}</small>
                </div>
            </div>`;
        });
        grid.html(html);
    }

    $('#universitySearch').on('keyup', function(){
        var term = $(this).val().toLowerCase();
        var filtered = allUniversities.filter(u => u.name.toLowerCase().includes(term) || (u.university_code ?? '').toLowerCase().includes(term));
        renderUniversities(filtered);
    });

    $(document).on('click', '.university-card', function(){
    var university_id = $(this).data('id');
    window.location.href = "{{ route('view_students') }}" + "?university_id=" + university_id;
});


});

</script>

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

@endsection
