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
                <button class="btn btn-sm btn-warning" id="export_excel">
                    <i class="fa fa-download" aria-hidden="true"></i>
                </button>
                <button class="btn btn-sm btn-primary" id="filter_options">
                    <i class="fa fa-filter"></i>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">View Student</h3>
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
$name = '';
$phone_number = '';
$gender = '';
$email = '';
$country = '';
$state = '';
$city = [];
$pending_payments = '';
$pending_profile_completion = '';
$course = [];
if (count($dataAr) > 0) {
    foreach ($dataAr as $key => $value) {
        if ($value !== null) {
            $$key = $value;
        }
    }
}
@endphp

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
    // Initialize DataTable without search box
    $("#view_students").DataTable({
        lengthMenu: [10, 25, 100, 500, 1000, 5000, 10000],
        pageLength: 25,
        searching: false // hide the search box
    });

    // Export to Excel
    $('#export_excel').click(function(e) {
        e.preventDefault();
        const filterParams = getFilterParams("true");
        preloader.load();
        $.ajax({
            type: "post",
            url: "{{ route('view_students') }}",
            data: filterParams,
            success: function(response) {
                preloader.stop();
                if (response.status == 'success') {
                    $('#download').attr('href', response.filePath);
                    $('#download')[0].click();
                }
            }
        });
    });

    // Filter modal
    $('#filter_options').click(async function(e) {
        e.preventDefault();
        const filterParams = getFilterParams();
        preloader.load()
        $.ajax({
            type: "post",
            url: "{{ route('load_view_student_filter') }}",
            data: filterParams,
            success: async function(response) {
                preloader.stop()
                $('#modal_body').html(response);
                $('#modal_filter #country').selectpicker();
                $('#modal_filter #state').selectpicker();
                $('#modal_filter #city').selectpicker();
                $('#modal_filter #course').selectpicker();

                $('#modal_filter #country').on('changed.bs.select', async function(e) {
                    const current_country_id = $(this).val();
                    await loadOptions({current_country_id}, 'load_states', updateOptions, '#modal_filter #state');
                });

                $('#modal_filter #state').on('changed.bs.select', async function(e) {
                    const current_state_id = $(this).val();
                    await loadOptions({current_state_id}, 'load_cities', updateOptions, '#modal_filter #city');
                });

                $('.modal-body').on('click', '#clear_filter', function() {
                    $('#modal_filter .filter_inputs').val("");
                    $('#modal_filter .selectpicker').val([]).selectpicker('refresh');
                });

                $('#modal_filter').modal('show');
            }
        });
    });
});

// Submit filter form
function filter_form() {
    $('.modal-body #filter_form').submit();
}

// Get filter parameters
function getFilterParams(excel = "false") {
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
        'excel': excel
    };
}

// AJAX search form submission
function searchForm(elem_id = "#filter_form") {
    $.ajax({
        type: "post",
        url: "{{ route('view_students') }}",
        data: $(elem_id).serialize(),
        success: function(response) {
            preloader.stop();
            $('.modal').modal('hide');
            $('#view_students_card').html(response);
        }
    });
}

// Load options for selects
function loadOptions(params, url, func, elem, selected_id = '', trigger_change = '') {
    params._token = "{{ csrf_token() }}";
    preloader.load('loader-container');
    $.ajax({
        url: `{{ url('${url}') }}`,
        type: 'post',
        data: params,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(response) {
            preloader.stop('loader-container');
            func(response, elem, selected_id, trigger_change);
        },
    });
}

// Update select options
function updateOptions(data, elem, selected_id = 0, trigger_change = '') {
    const $elem = $(elem);
    $elem.empty();
    $elem.append(`<option value="">Select Option</option>`);

    data.forEach(item => {
        let selected = Array.isArray(selected_id) 
            ? selected_id.includes(String(item.id)) ? "selected" : "" 
            : selected_id == item.id ? 'selected' : '';
        let option = '';

        if (item.state) {
            option = `<option value="${item.id}" state_name="${item.state.name}" state_id="${item.state.id}" country_id="${item.state.country.id}" country_name="${item.state.country.name}" ${selected}>${item.name}, ${item.state.name}, ${item.state.country.name}</option>`;
        } else if (item.country_id) {
            const country_name = item.country ? item.country.name : item.country_name;
            option = `<option value="${item.id}" country_name="${country_name}" country_id="${item.country_id}" ${selected}>${item.name}, ${country_name}</option>`;
        } else {
            option = `<option value="${item.id}" ${selected}>${item.name}</option>`;
        }

        $elem.append(option);
    });

    $elem.selectpicker('refresh');
    if (trigger_change) $elem.trigger('change');
}
</script>
@endsection
