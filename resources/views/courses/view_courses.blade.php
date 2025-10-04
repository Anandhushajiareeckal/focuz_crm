@extends('layouts.layout')
@section('content')
    <link rel="stylesheet" href="{{ asset('/css/datatables.min.css') }}">
    <script src="{{ asset('/js/datatables.min.js') }}"></script>
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">View Courses</h3>
                        </div>
                        <div class="card-body  table-responsive">
                            <form action="{{ route('view_courses') }}" method="post">
                                @csrf
                                <div class="row mt-2 mb-2">
                                    <div class="col-sm-5">

                                        <select name="universities_filter[]" id="university_filter"
                                            class="form-control form-control-sm selectpicker" data-live-search="true"
                                            data-actions-box="true" multiple>

                                            @foreach ($universities_filters as $university_loop)
                                                @php
                                                    if (in_array($university_loop->id, $university_ids)) {
                                                        $selected_univ = 'selected';
                                                    } else {
                                                        $selected_univ = '';
                                                    }
                                                @endphp

                                                <option value="{{ $university_loop->id }}" {{ $selected_univ }}>
                                                    {{ $university_loop->name }}
                                                    ({{ $university_loop->university_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-sm btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
                            @if (!(is_array($course_schedule_data) && empty($course_schedule_data)))
                                <table class="table table-sm table-bordered table-striped" style="font-size: 10pt"
                                    id="courses_table">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>University</th>
                                            <th>Course</th>
                                            <th>Course Fee</th>
                                            <th>Course Commission</th>
                                            <th>Other Fees</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Course Duration</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($course_schedule_data as $key => $course_schedule)
                                            <tr>
                                                @php
                                                    $code = '';

                                                    $university = App\Models\Universities::where(
                                                        'id',
                                                        $course_schedule->course->university_id,
                                                    )->first(['name', 'university_code']);
                                                    $startDate = \Carbon\Carbon::parse($course_schedule->start_date);
                                                    $endDate = \Carbon\Carbon::parse($course_schedule->end_date);
                                                    $yearsDifference = $startDate->diffInYears($endDate);
                                                @endphp
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $university->name }} ({{ $university->university_code }})</td>
                                                <td>{{ $course_schedule->course->streams->code }}
                                                    {{ $course_schedule->course->specialization }}
                                                </td>
                                                <td>{{ number_format($course_schedule->course_fee, 2) }} </td>
                                                <td>{{ number_format($course_schedule->commission, 2) }} </td>
                                                <td>{{ number_format($course_schedule->other_fees, 2) }} </td>
                                                <td>{{ date('d-m-Y', strtotime($startDate)) }} </td>
                                                <td>{{ date('d-m-Y', strtotime($endDate)) }} </td>
                                                <td>{{ $yearsDifference }} </td>
                                                <td>{{ ucwords($course_schedule->status) }} </td>
                                                <td>
                                                    <i class="fa fa-edit text-info edit_course"
                                                        s_id="{{ $course_schedule->course->stream_id }}"
                                                        u_id="{{ $course_schedule->course->university_id }}"
                                                        c_id="{{ $course_schedule->course_id }}" status="all"
                                                        specialization="{{ $course_schedule->course->specialization }}"
                                                        style="cursor: pointer"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>


    <div class="modal" id="modal_create" aria-modal="true" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="modal_body">

                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {

            var table = $("#courses_table").DataTable({
                order: [
                    [0, 'asc']
                ],
                lengthMenu: [10, 25, 100],
                pageLength: 25
            });



            $(document).on('click', '.edit_course', function(e) {
                e.preventDefault();
                const u_id = $(this).attr('u_id');
                const s_id = $(this).attr('s_id');
                const c_id = $(this).attr('c_id');
                const status = $(this).attr('status');
                const specialization = $(this).attr('specialization');
                preloader.load();
                $.ajax({
                    type: "post",
                    url: "{{ url('get_specialization_keys') }}",
                    data: {
                        'specialization': specialization,
                        'u_id': u_id,
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(specialization_keys) {
                        preloader.stop();
                        manage_course(u_id, s_id, c_id, specialization_keys, status);
                    }
                });

            });



        });
    </script>
@endsection
