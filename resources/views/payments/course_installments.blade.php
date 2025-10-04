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
                            <h3 class="card-title">View Courses Installments</h3>
                        </div>
                        <div class="card-body  table-responsive">
                            @if (!(is_array($course_installment_data) && empty($course_installment_data)))
                                <table class="table table-sm table-bordered table-striped" style="font-size: 10pt"
                                    id="courses_table">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>University</th>
                                            <th>Course</th>
                                            <th>Date Period</th>
                                            <th>Next Pay Date</th>
                                            <th>Course Fee</th>
                                            <th>Installment Amount</th>
                                            <th>No Installments</th>
                                            <th>Total Amount</th>
                                            <th>Paid Amount</th>
                                            <th>Due Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">

                                        @foreach ($course_installment_data as $key => $course_installment)
                                            <tr>
                                                @php
                                                    $code = '';
                                                    $university = App\Models\Universities::where(
                                                        'id',
                                                        $course_installment->course->university_id,
                                                    )->first(['name', 'university_code']);

                                                @endphp
                                                <td class="align-middle text-center">{{ $key + 1 }}</td>
                                                <td class="align-middle">{{ $university->university_code }}</td>
                                                <td class="align-middle">{{ $course_installment->course->streams->code }}
                                                    {{ $course_installment->course->specialization }}
                                                </td>


                                                </td>
                                                <td class="align-middle">
                                                    {{ date('d-m-Y', strtotime($course_installment->start_date)) }} to
                                                    {{ date('d-m-Y', strtotime($course_installment->end_date)) }}
                                                </td>
                                                <td class="align-middle">
                                                    @if ($course_installment->next_pay_date)
                                                        {{ date('d-m-Y', strtotime($course_installment->next_pay_date)) }}
                                                    @endif
                                                </td>
                                                <td class="text-right align-middle">
                                                    @php
                                                        $other_fees = 0;
                                                        if (!$course_installment->course_schedule->other_fees) {
                                                            $other_fees = 0;
                                                        } else {
                                                            $other_fees =
                                                                $course_installment->course_schedule->other_fees;
                                                        }
                                                        $course_fee =
                                                            $other_fees +
                                                            $course_installment->course_schedule->course_fee;
                                                        $total_fee_topay =
                                                            $course_installment->number_of_installments *
                                                            $course_installment->installment_amount;
                                                        $paid_amount = $course_installment->paid_amount;
                                                        $pending_amount = $total_fee_topay - $paid_amount;
                                                    @endphp
                                                    {{ number_format($course_fee, 2) }}
                                                </td>
                                                <td class="text-right align-middle">
                                                    {{ number_format($course_installment->installment_amount, 2) }}</td>
                                                <td class="text-center align-middle">
                                                    {{ $course_installment->completed_installments }} /
                                                    {{ $course_installment->number_of_installments }} </td>
                                                <td class="text-right align-middle">
                                                    {{ number_format($total_fee_topay, 2) }}</td>
                                                <td class="text-right align-middle">
                                                    {{ number_format($paid_amount, 2) }}</td>
                                                <td class="text-right align-middle">
                                                    @if ($pending_amount != 0)
                                                        <a class="btn btn-sm btn-link" data-toggle="popover"
                                                            title="Process Payment" target="_blank"
                                                            href="{{ route('add_students', [4, $course_installment->student_id, $course_installment->course_id,$course_installment->course_schedule_id, $course_installment->id]) }}">
                                                            {{ number_format($pending_amount, 2) }}</a>
                                                    @else
                                                        {{ number_format($pending_amount, 2) }}
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">

                                                    <i class="fa fa-bars" style="cursor: pointer" data-toggle="dropdown"
                                                        aria-expanded="false"></i>

                                                    <ul class="dropdown-menu" style="cursor:pointer">
                                                        <li class="dropdown-item">
                                                            <a style="cursor: pointer" class="btn btn-sm btn-link"
                                                                s_id="{{ $course_installment->course->stream_id }}"
                                                                u_id="{{ $course_installment->course->university_id }}"
                                                                c_id="{{ $course_installment->course_id }}" status="all"
                                                                specialization="{{ $course_installment->course->specialization }}">
                                                                <i class="fa fa-edit text-info edit_course"></i>&nbsp;Edit
                                                                Installment</a>
                                                        </li>
                                                        <li class="dropdown-item">
                                                            <a class="btn btn-sm btn-link" target="_blank"
                                                                href="{{ route('view_profile', [1, $course_installment->student_id]) }}">
                                                                <i class="fa fa-eye"></i>&nbsp; View Profile</a>
                                                        </li>

                                                        <li class="dropdown-item">
                                                            <a class="btn btn-sm btn-link" target="_blank"
                                                                href="{{ route('add_students', [4, $course_installment->student_id, $course_installment->course_id, $course_installment->course_schedule_id, $course_installment->id]) }}">
                                                                <i class="fa fa-money-bill-alt"></i>&nbsp; Process
                                                                Payment</a>
                                                        </li>

                                                        <li class="dropdown-item">
                                                            <a class="btn btn-sm btn-link" target="_blank"
                                                                href="{{ route('add_students', [4, $course_installment->student_id]) }}">
                                                                <i class="fa fa-credit-card"></i>&nbsp; View Installment
                                                                History</a>
                                                        </li>

                                                    </ul>


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
