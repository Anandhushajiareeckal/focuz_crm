@extends('layouts.layout')
@section('content')
    <link rel="stylesheet" href="{{ asset('/css/datatables.min.css') }}">
    <script src="{{ asset('/js/datatables.min.js') }}"></script>
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col text-right">
                    <a class="btn btn-sm btn-dark" href="{{ route('payments_approve', ['pending']) }}">
                        <i class="fa fa-credit-card"></i>&nbsp;&nbsp;Pending Payments
                    </a>
                    <a class="btn btn-sm btn-dark" href="{{ route('payments_approve', ['reversed']) }}">
                        <i class="fa fa-credit-card"></i>&nbsp;&nbsp;Reversed Payments
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">View Payment Approvals</h3>
                        </div>
                        <div class="card-body  table-responsive" id="view_students_card">
                            @if (!(is_array($payments_data) && empty($payments_data)))
                                <table class="table table-sm table-bordered table-striped" style="font-size: 10pt"
                                    id="view_payments_table">
                                    <thead>
                                        <tr>
                                            <th>SL <input type="checkbox" id="selectAll"></th>
                                            <th>Name</th>
                                            <th>Track ID</th>
                                            <th>Course</th>
                                            <th>Payment Method</th>
                                            <th>Bank</th>
                                            <th>Discount Code</th>
                                            <th>Amount</th>
                                            <th>Discount Amount</th>
                                            <th>Total</th>
                                            <th>Approve</th>
                                            <th>Reject</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments_data as $key => $payment)
                                            @php
                                                $student = App\Models\Students::where(
                                                    'id',
                                                    $payment->student_id,
                                                )->first(['first_name', 'last_name', 'email', 'phone_number']);

                                                $track_id = App\Models\CoursePayments::where(
                                                    'student_id',
                                                    $payment->student_id,
                                                )
                                                    ->where('course_id', $payment->course_id)
                                                    ->where('course_schedule_id', $payment->course_schedule_id)
                                                    ->value('student_track_id');

                                                $total_amount = $payment->amount;
                                                $discount_amount = '';
                                                if (
                                                    $payment->discount_amount !== null ||
                                                    $payment->discount_amount != 0
                                                ) {
                                                    $total_amount += $payment->discount_amount;
                                                    $discount_amount = number_format($payment->discount_amount, 2);
                                                }
                                            @endphp

                                            <tr id="row_{{ $payment->id }}">
                                                <td>

                                                    <input type="checkbox" class="approve_invoices"
                                                        value="{{ $payment->id }}">

                                                </td>
                                                <td><a data-toggle="popover" title="View Profile"
                                                        href="{{ route('view_profile', [1, $payment->student_id]) }}"
                                                        target="_blank">
                                                        {{ $student->first_name }} {{ $student->last_name }}
                                                    </a>
                                                </td>
                                                <td>{{ $track_id }}</td>

                                                <td>
                                                    <a data-toggle="popover" title="View Course"
                                                        href="{{ route('view_courses', [$payment->course_id]) }}"
                                                        target="_blank">
                                                        {{ $payment->courses->university->university_code }}
                                                        {{ $payment->courses->streams->code }}
                                                        {{ $payment->courses->specialization }}
                                                    </a>

                                                </td>
                                                <td>{{ $payment->payment_methods->method_name }}</td>
                                                <td>{{ optional($payment->banks)->bank_name }}</td>
                                                <td data-toggle="popover" title="{{ $payment->discounts->description }}">
                                                    {{ $payment->discounts->promocode }}
                                                </td>
                                                <td>{{ number_format($payment->amount, 2) }}</td>
                                                <td>{{ $discount_amount }}</td>
                                                <td>
                                                    <a data-toggle="popover" title="Edit Payment"
                                                        href="{{ route('add_students', [4, $payment->student_id, $payment->course_id, $payment->course_schedule_id, 'payment_' . $payment->id]) }}"
                                                        target="_blank">{{ number_format($total_amount, 2) }}</a>
                                                </td>
                                                <td align="middle">
                                                    @if ($payment->status == 'pending' || $payment->status == 'reversed')
                                                        <button class="btn btn-sm btn-primary approve_payment"
                                                            ref="{{ $payment->id }}">Approve</button>
                                                    @else
                                                        <i class="fa fa-file-pdf text-danger download_invoice"
                                                            ref="{{ $payment->id }}"></i>
                                                    @endif
                                                </td>
                                                <td align="middle">
                                                    @if ($payment->status == 'pending')
                                                        <button class="btn btn-sm btn-primary reject_payment"
                                                            ref="{{ $payment->id }}">Reject</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        @if (!(is_array($payments_data) && empty($payments_data)))
                            <div class="pagination">
                                {{ $payments_data->links() }}
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

    @endphp



    <script>
        $(document).ready(function() {

            $('#selectAll').change(function(e) {
                e.preventDefault();
                if ($(this).prop('checked')) {

                    $('#view_payments_table .approve_invoices').prop('checked', true);
                } else {
                    $('#view_payments_table .approve_invoices').prop('checked', false);
                }
            });

            var table = $("#view_payments_table").DataTable({
                lengthMenu: [10, 25, 100, 500, 1000, 5000, 10000], // Pagination options
                pageLength: 25, // Default number of rows per page
                order: [
                    [1, 'asc']
                ],
                stateSave: true,
                columnDefs: [{
                        targets: [0],
                        orderable: false
                    } // Disable sorting for both the first and second columns
                ]
            });

            $('#approve_invoices').click(function(e) {
                e.preventDefault();
                var checkedIds = [];
                // Get all rows, not just the visible ones
                table.rows().nodes().to$().find('input.approve_invoices:checked').each(function() {
                    checkedIds.push($(this).val());
                });
                if (checkedIds.length == 0) {
                    window.alert("Please select at least one checkbox")
                } else {
                    if (window.confirm("Are you sure wish to approve the invoices?")) {
                        preloader.load();
                        $.ajax({
                            type: "post",
                            url: "{{ route('approve_payments') }}",
                            data: {
                                '_token': "{{ csrf_token() }}",
                                "checkedIdsJson": checkedIds
                            },
                            success: function(response) {
                                preloader.stop();
                                console.log(response)
                                process_complete_fun(response, table)
                            },
                            error: function(xhr) {
                                preloader.stop();
                                showAlert("Something went wrong, Please contact IT support");
                            }
                        });
                    }
                }

            });

            $('#reject_invoices').click(function(e) {
                e.preventDefault();
                var checkedIds = [];
                // Get all rows, not just the visible ones
                table.rows().nodes().to$().find('input.approve_invoices:checked').each(function() {
                    checkedIds.push($(this).val());
                });
                if (checkedIds.length == 0) {
                    window.alert("Please select at least one checkbox")
                } else {
                    if (window.confirm("Are you sure wish to remove the payments?")) {
                        preloader.load();
                        $.ajax({
                            type: "post",
                            url: "{{ route('reject_payments') }}",
                            data: {
                                '_token': "{{ csrf_token() }}",
                                "checkedIdsJson": checkedIds
                            },
                            success: function(response) {
                                preloader.stop();
                                process_complete_fun(response, table)
                            },
                            error: function(xhr) {
                                preloader.stop();
                                showAlert("Something went wrong, Please contact IT support");
                            }
                        });
                    }
                }

            });

            $(document).on('click', '.approve_payment', function(e) {
                e.preventDefault();
                var checkedIds = [$(this).attr('ref')];
                if (window.confirm("Are you sure wish to approve the invoices?")) {
                    preloader.load();
                    $.ajax({
                        type: "post",
                        url: "{{ route('approve_payments') }}",
                        data: {
                            '_token': "{{ csrf_token() }}",
                            "checkedIdsJson": checkedIds
                        },
                        success: function(response) {
                            preloader.stop();
                            process_complete_fun(response, table)
                        },
                        error: function(xhr) {
                            preloader.stop();
                            showAlert("Something went wrong, Please contact IT support");
                        }

                    });
                }
            });

            $(document).on('click', '.reject_payment', function(e) {
                e.preventDefault();
                var checkedIds = [$(this).attr('ref')];
                if (window.confirm("Are you sure wish to reject the payment?")) {
                    preloader.load();
                    $.ajax({
                        type: "post",
                        url: "{{ route('reject_payments') }}",
                        data: {
                            '_token': "{{ csrf_token() }}",
                            "checkedIdsJson": checkedIds
                        },
                        success: function(response) {
                            preloader.stop();
                            process_complete_fun(response, table)
                        },
                        error: function(xhr) {
                            preloader.stop();
                            showAlert("Something went wrong, Please contact IT support");
                        }
                    });
                }



            });

        });

        function process_complete_fun(response, table) {
            const rows_remove_ids = response.rows_remove_ids;
            showAlert(response.message);
            if (rows_remove_ids.length != 0) {
                rows_remove_ids.forEach(function(row_count) {
                    table.rows(function(idx, data, node) {
                        return data.DT_RowId == 'row_' + row_count;
                    }).remove().draw();
                });
            }
        }



        function showAlert(message, alert_type = 'success') {
            $('#modalMessage').html(message);
            $('#alertModal').modal('show');
            $('#selectAll').prop('checked', false);

        }

        function getFilterParams(excel = "false") {
            // filter_table();
            const params = {
                '_token': "{{ csrf_token() }}",
                'name': "{{ $name }}",
                'phone_number': "{{ $phone_number }}",

            };
            return params;
        }

        function searchForm(elem_id = "#filter_form") {
            $.ajax({
                type: "post",
                url: "{{ route('view_students') }}",
                data: $(elem_id).serialize(),
                success: function(response) {
                    // console.log(response)
                    preloader.stop()
                    $('.modal').modal('hide');
                    $('#view_students_card').html(response);

                }
            });
        }
    </script>
@endsection
