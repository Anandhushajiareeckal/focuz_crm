@extends('layouts.layout')
@section('content')
<link rel="stylesheet" href="{{ asset('/css/datatables.min.css') }}">
<script src="{{ asset('/js/datatables.min.js') }}"></script>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col text-right">
                <a class="btn btn-sm btn-dark" href="{{ route('view_students', ['new']) }}">
                    <i class="fa fa-eraser"></i>&nbsp;&nbsp;New Students
                </a>
                <a class="btn btn-sm btn-dark" href="{{ route('view_students', ['unpaid']) }}">
                    <i class="fa fa-eraser"></i>&nbsp;&nbsp;UnPaid Students
                </a>
                <a class="btn btn-sm btn-dark" href="{{ route('view_students') }}">
                    <i class="fa fa-eraser"></i>&nbsp;&nbsp;Clear Filter
                </a>
              <a href="{{ route('view_students', ['excel' => 'true']) }}" id="export_excel" class="btn btn-sm btn-warning">
                    <i class="fa fa-download" aria-hidden="true"></i>&nbsp;&nbsp;Export Excel
                </a>

                <button class="btn btn-sm btn-primary" id="filter_options">
                    <i class="fa fa-filter"></i>&nbsp;&nbsp;Filter
                </button>
                <!-- Hidden download link -->
                <a id="download" style="display:none;"></a>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">View Students</h3>
                    </div>
                    <div class="card-body table-responsive" id="view_students_card">
                        @if (!(is_array($students_data) && empty($students_data)))
                            @include('students.view_students.view_student_data')
                        @endif
                    </div>
                    @if (!(is_array($students_data) && empty($students_data)))
                        <div class="pagination">
                            {{ $students_data->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@php
    // Load filter values
    $name = $phone_number = $gender = $email = $country = $state = '';
    $city = $course = [];
    $pending_payments = $pending_profile_completion = '';
    if(count($dataAr) > 0){
        foreach($dataAr as $key => $value){
            if($value !== null){
                $$key = $value;
            }
        }
    }
@endphp

<!-- Filter Modal -->
<div class="modal" id="modal_filter" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Filter Students</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body"></div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $("#view_students").DataTable({
        lengthMenu: [10, 25, 100, 500, 1000, 5000, 10000],
        pageLength: 25
    });

    // Export Excel
document.getElementById('export_excel').addEventListener('click', function() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('view_students') }}";

    const data = {
        _token: "{{ csrf_token() }}",
        excel: "true",
        name: "{{ $name }}",
        phone_number: "{{ $phone_number }}",
        email: "{{ $email }}"
    };

    for (const key in data) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = data[key];
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
});


    // Open filter modal
    $('#filter_options').click(function(e){
        e.preventDefault();
        const filterParams = getFilterParams();
        preloader.load();
        $.ajax({
            type: "POST",
            url: "{{ route('load_view_student_filter') }}",
            data: filterParams,
            success: function(response){
                preloader.stop();
                $('#modal_body').html(response);
                // initialize selectpickers
                $('#modal_filter .selectpicker').selectpicker();
                $('#modal_filter').modal('show');
            }
        });
    });
});

// Get filter values
function getFilterParams(excel = "false"){
    return {
        '_token': "{{ csrf_token() }}",
        'name': "{{ $name }}",
        'phone_number': "{{ $phone_number }}",
        'email': "{{ $email }}",
        'country': "{{ $country }}",
        'state': "{{ $state }}",
        'city': "{{ implode(',', $city) }}",
        'gender': "{{ $gender }}",
        'course': "{{ implode(',', $course) }}",
        'pending_payments': "{{ $pending_payments }}",
        'pending_profile_completion': "{{ $pending_profile_completion }}",
        'excel': excel,
    };
}
</script>
@endsection
