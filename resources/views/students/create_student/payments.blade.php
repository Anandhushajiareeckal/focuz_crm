<div class="card card-default ">
    {{-- collapsed-card --}}
    <div class="card-header">
        <h3 class="card-title">Payment Details</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="alert alert-sm alert-success d-none" id="alert_messge_payment"></div>
            </div>
        </div>
        <form id="payments_form">
            @csrf
            <input type="hidden" name="payment_id" id="payment_id" value="{{ $payment_id_update }}">
            <input type="hidden" name="installment_id" id="installment_id" value="{{ $installment_id_param }}">
            <input type="hidden" name="university_code" id="university_code" value="">
            <input type="hidden" id="payed_amount_inp" value="0">
            <input type="hidden" name="reverse_transaction" id="reverse_transaction" value="false">
            <input type="hidden" class="student_id" name="student_id" value="{{ $student_id_url }}">
            <div class="row">
                <div class="col">
                    <h5 class="text-uppercase font-weight-bold">Payment Details</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="balance_amount">Balance Amount </label><br />
                        <span id="balance_amount" class="text-danger"></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="balance_amount">Payed Amount</label><br />
                        <p id="payed_amount" class="text-success"></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="balance_amount">Student Track ID</label><br />
                        <p id="stud_track_id" class="text-info"></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h5 class="text-uppercase font-weight-bold">Installment Details</h5>

                </div>

            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for=""> Student Chose Installment</label>
                        <br />
                        <span class="font-weight-bold text-info">

                            @if ($installment_amount != '')
                                YES
                            @else
                                NO
                            @endif
                        </span>
                        @if ($pending_amount_installment != 0)

                            <span class="text-danger"> (

                                @if ($pending_amount_installment < 0)
                                    Underpaid
                                @elseif($pending_amount_installment > 0)
                                    Overpaid
                                @endif
                                in Settled Installments)
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="">Is Installment</label>
                        <br />
                        <select name="is_installment" id="is_installment" class="form-control form-control-sm">
                            <option value="">Select Option</option>
                            @php
                                $selected_installment = '';
                                $selected_normal = '';
                                if ($payment_id_update != '') {
                                    if ($installment_id_param != '') {
                                        $selected_installment = 'selected';
                                    } else {
                                        $selected_normal = 'selected';
                                    }
                                }
                            @endphp
                            <option value="installment" {{ $selected_installment }}>Installment</option>
                            <option value="normal" {{ $selected_normal }}>Normal Payment</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group" id="course_div">
                        <label for="course">Select Course <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm mb-3">

                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu" style="cursor:pointer">
                                    <li class="dropdown-item">Add New</li>
                                    <li class="dropdown-item">Edit Selected</li>
                                </ul>
                            </div>

                            <select name="course" id="course" data-size="5" url="load_courses"
                                class="form-control form-control-sm selectpicker" data-live-search="true">
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group" id="course_period_div">
                        <label for="course_period">Select Course Period <span class="text-danger">*</span></label>
                        <select name="course_period" data-size="5" id="course_period"
                            class="form-control form-control-sm selectpicker" data-live-search>
                        </select>
                    </div>
                </div>



                <div class="col-md-6">
                    <div class="form-group" id="payment_method_div">
                        <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" data-size="5"
                            class="form-control form-control-sm selectpicker selectpicker_load "
                            url="load_payment_method" data-live-search>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="form-group">
                        <label for="amount">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="any" class="form-control form-control-sm amount_change"
                            id="amount" name="amount" placeholder="Amount" value="{{ $installment_amount }}">
                    </div>
                </div>
            </div>
            <div class="row d-none" id="bank_selection">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bank">Bank</label>
                        <select name="bank" id="bank" data-size="5" url="load_banks"
                            class="form-control form-control-sm selectpicker selectpicker_load" data-live-search>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="card_type">Card Type</label>
                        <select name="card_type" id="card_type" data-size="5" url="load_card_types"
                            class="form-control form-control-sm selectpicker selectpicker_load" data-live-search>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="promo_code_div">
                        <label for="promo_code">Promo Code</label>
                        <select name="promo_code" id="promo_code" data-size="5"
                            class="form-control form-control-sm selectpicker selectpicker_load "
                            url="load_promo_codes" data-live-search>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="discount_amount">Discount Amount</label>
                        <input type="number" step="any" class="form-control form-control-sm amount_change"
                            id="discount_amount" name="discount_amount" placeholder="Discount Amount">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="transaction_date">Transaction date <span class="text-danger">*</span></label>
                        <input type="text" name="transaction_date" id="transaction_date"
                            class="form-control form-control-sm datepicker_payment" value="{{ date('d-m-Y') }}"
                            placeholder="Enter trans date" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="trans_ref">Transaction Ref</label>
                        <input type="text" class="form-control form-control-sm" id="trans_ref" name="trans_ref"
                            placeholder="Trans ref">
                    </div>
                </div>



            </div>
            <div class="row">
                <div class="col">
                    <h5 class="text-uppercase font-weight-bold">Common Details</h5>
                    <input type="checkbox" name="update_common_details">&nbsp;&nbsp;Update Only the Details Below
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="branch_code_div">
                        <label for="branch_code">Branch <span class="text-danger">*</span></label><br />
                        <select name="branch_code" id="branch_code" class="form-control form-control-sm selectpicker"
                            data-live-search="true" required>
                            <option value="">Select branch</option>
                            @foreach ($branchesAr as $branch)
                                <option value="{{ $branch->id }}__{{ $branch->code }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="admission_date">Admission Date <span class="text-danger">*</span></label><br />
                        <input type="text" class="form-control form-control-sm datepicker_payment"
                            id="admission_date" name="admission_date" placeholder="Next Payment Date"
                            value="{{ date('d-m-Y') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">BDE <span class="text-danger">*</span></label><br />
                        <input type="text" id="created_by_select" class="form-control form-control-sm"
                            placeholder="Enter BDE name">
                        <input type="hidden" name="created_by" id="created_by">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="next_pay_date">Next Payment Date</label><br />
                        <input type="text" class="form-control form-control-sm datepicker_payment"
                            id="next_pay_date" name="next_pay_date" placeholder="Next Payment Date"
                            value="{{ date('d-m-Y', strtotime('+10days')) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="univ_login_id">University Login ID </label><br />
                        <input type="text" name="univ_login_id" id="univ_login_id"
                            class="form-control form-control-sm" placeholder="University Login ID">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="univ_login_pass">University Login Pass</label><br />
                        <input type="text" name="univ_login_pass" id="univ_login_pass"
                            class="form-control form-control-sm" placeholder="University Login Pass">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="stud_status">Student Status <span class="text-danger">*</span></label><br />
                        <select name="stud_status" id="stud_status" class="form-control form-control-sm">
                            <option value="">Select Option</option>
                            <option value="active" selected>Active</option>
                            <option value="dropped">Dropped</option>
                        </select>
                    </div>
                </div>





                <div class="col-md-12">
                    <div class="alert alert-sm alert-info">
                        <span>Note :- Set Up Course Installments for automated next payment
                            date</span><br />
                        <span>Note :- You can use multiple payment methods (Card, Cash, Cheque) on
                            this screen if needed. <a href="{{ asset('images/payment_method.jpg') }}"
                                target="blank">View
                                Example</a></span><br />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        const course_id_param = "{{ $course_id_param }}";
        if (course_id_param != "") {
            const student_id_post = $('.student_id').val();
            load_courses_data(course_id_param);

        }


        $("#created_by_select").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: 'post',
                    url: "{{ route('username_autocomplete') }}",
                    data: {
                        term: request.term,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                event.preventDefault();

                $('#created_by_select').val(ui.item.label);

                $('#created_by').val(ui.item.value);
            }
        });

        $('#course').on('show.bs.select', function(e) {
            e.preventDefault();
            load_courses_data();
        });

        $('#payment_method').change(function(e) {
            e.preventDefault();
            var array = ['Bank Transfer', 'Credit Card', 'Debit Card', 'Cheque'];
            if (array.includes($(this).find(':selected').text())) {
                $('#bank_selection').removeClass('d-none');
            } else {
                $('#bank_selection').addClass('d-none');
            }
        });
        $('#reverse_payment').click(function(e) {
            e.preventDefault();
            if ($('#payment_id').val() != "") {
                $('#reverse_transaction').val('true');
            }
        });
        $('.amount_change').change(function(e) {
            e.preventDefault();

            if ($('#course_period').find(':selected').text() == '') {
                window.alert("Please select couse and course period")
            } else {
                const course_feeText = $('#course_period').find(':selected').text().split(' - ');
                const course_fee = parseFloat(course_feeText[2]);
                let amount = $('#amount').val();
                let discount_amount = $('#discount_amount').val();
                if (amount != 0 || amount != '') {
                    amount = parseFloat(amount);
                } else {
                    amount = 0;
                }
                if (discount_amount != 0 || discount_amount != '') {
                    discount_amount = parseFloat(discount_amount);
                } else {
                    discount_amount = 0;
                }
                let payed_amount = parseFloat($('#payed_amount_inp').val());
                if (isNaN(payed_amount)) {
                    payed_amount = 0;
                }
                const net_amount = amount + discount_amount + payed_amount;
                $('#balance_amount').html(course_fee - net_amount);
            }
        });


        $(document).on('click', '#register_new_payment', function(e) {
            e.preventDefault();
            $('#payment_id').val("");
        });



        $('.datepicker_payment').datepicker({
            changeYear: true,
            changeMonth: true,
            yearRange: "-5:+2",
            dateFormat: "dd-mm-yy", // Set the date format
        })


        $('.selectpicker_load').on('show.bs.select', function(e) {
            const url = $(this).attr('url');
            const id = '#' + $(this).attr('id');

            loadOptions({}, url, updateOptions, id);
        });

        $('#course').on('change.bs.select', function(e) {
            e.preventDefault();
            const course_id_param = "{{ $course_id_param }}";
            const course_schedule_id_param = "{{ $course_schedule_id_param }}";
            const course_id = $(this).val();
            load_course_period(course_id_param, course_schedule_id_param, course_id);

        });

        $('#course_period').on('change.bs.select', function(e) {
            e.preventDefault();
            const course_schedule_id = $(this).val();
            loadPayedAmount(course_schedule_id);
        });

    });

    async function load_course_period(course_id_param, course_schedule_id_param, course_id) {
        preloader.load('loader-container');
        try {
            const response = await new Promise((resolve, reject) => {
                $.ajax({
                    url: `{{ url('load_courses_period') }}`,
                    type: 'post',
                    data: {
                        'course_id': course_id,
                        'student_id': $('.student_id').val(),
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        resolve(response)
                    },
                    error: function(xhr, _, _) {
                        reject(xhr)
                    }
                });
            });

            preloader.stop('loader-container');
            const selected_course_id = response.selected_course_id;

            const merged_courses = response.merged_courses;
            trigger_change = "#course_period";
            if (merged_courses.length == 1) {
                selected_id = merged_courses[0].id;
            } else if (selected_course_id != '') {
                selected_id = selected_course_id;
            } else if (course_schedule_id_param != '' && course_id ==
                course_id_param) {
                selected_id = course_schedule_id_param;
            } else {
                selected_id = "";
                trigger_change = "";
            }

            await updateOptions(merged_courses, '#course_period', selected_id,
                trigger_change);
        } catch (error) {
            console.log(error)
            preloader.stop('loader-container');
            showAlert(
                "Error loading payment details. Contact IT Support.")
        }
    }

    function load_courses_data(course_id_param = "") {
        const student_id = $('.student_id').val();
        const params_courses = {
            'student_id': student_id,
            "_token": "{{ csrf_token() }}"
        }
        preloader.load('loader-container');
        $("#course").empty(); // Clear existing options
        $.ajax({
            url: `{{ url('load_courses') }}`,
            type: 'post',
            data: params_courses,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const courses_selected = response.courses_selected;
                const courses_unselected = response.courses_unselected;
                preloader.stop('loader-container');
                if (courses_selected.length > 0) {
                    const $option = $('<option></option>').val("").html("Select Course");
                    $("#course").append($option);
                    var $group = $('<optgroup label="Enrolled Courses">');
                    $("#course").append($group);

                    courses_selected.forEach(item => {
                        const value = item.id; // Use the ID as the value
                        const text = item.name || '';

                        const $option = $('<option></option>').val(value).html(text);

                        if (value == course_id_param) {

                            $option.prop('selected', true); // Select the option if it matches
                        }
                        $group.append($option);
                    });
                }

                if (courses_unselected.length > 0) {
                    var $group2 = $('<optgroup label="Unenrolled Courses">');
                    $("#course").append($group2);

                    courses_unselected.forEach(item => {
                        const value = item.id; // Use the ID as the value
                        const text = item.name || '';
                        $group2.append($('<option></option>').val(value).html(text));
                    });
                }
                $("#course").selectpicker("refresh");
                $("#course").trigger('change');

            },
        });
    }


    async function loadPayedAmount(course_schedule_id) {
        if (course_schedule_id != "") {
            preloader.load()
            const payment_id_update = "{{ $payment_id_update }}";
            try {
                const response = await new Promise((resolve, reject) => {
                    $.ajax({
                        type: "post",
                        url: `{{ url('load_courses_payments') }}`,
                        data: {
                            'course_id': $('#course').val(),
                            'student_id': $('.student_id').val(),
                            'course_schedule_id': course_schedule_id,
                            '_token': "{{ csrf_token() }}",
                            'payment_id_update': payment_id_update,
                        },
                        success: function(response) {
                            resolve(response)
                        }
                    });
                });

                preloader.stop();
                if (response != 'failed') {

                    $('#payed_amount').html(response.amount_formatted);
                    $('#payed_amount_inp').val(response.amount);
                    $('#stud_track_id').html(response.student_track_id);
                    $('#branch_code').val(response.branch_code);
                    $('#admission_date').val(response.admission_date);
                    $('#univ_login_id').val(response.univ_login_id);
                    $('#univ_login_pass').val(response.univ_login_pass);
                    $('#created_by_select').val(response.emp_name);
                    $('#created_by').val(response.emp_id);
                    $('#stud_status').val(response.stud_status);
                    $('#branch_code').selectpicker('refresh');
                    $('#payment_id').val(response.payment_id)
                    $('#balance_amount').html(response.balance_amount);
                    if (payment_id_update != '') {
                        if (response.discount_amount) {
                            $('#discount_amount').val(response.discount_amount);
                        }
                        $('#next_pay_date').val(response.next_pay_date);
                        $('#transaction_date').val(response.transaction_date);
                        $('#transaction_notes').val(response.transaction_notes);
                        $('#amount').val(response.amount);
                        let load_data = {
                            '#payment_method': ['load_payment_method', response.payment_method,
                                'trigger_change'],
                            '#promo_code': ['load_promo_codes', response.promo_code],
                            '#bank': ['load_banks', response.bank],
                            '#card_type': ['load_card_types', response.card_type],
                        };

                        for (let selector in load_data) {
                            let [loadFunction, data, triggerChange] = load_data[selector];
                            triggerChange = triggerChange || null;
                            await loadOptions({}, loadFunction, updateOptions, selector, data, triggerChange);
                        }

                    }else{
                        $('#payment_id').val('');
                    }


                }
            } catch (error) {
                showAlert("Error loading course payment details. Please contact IT Support")
            }

        }
    }

    function save_payments_form() {
        preloader.load()
        $.ajax({
            url: "{{ route('save_payments_info') }}",
            type: 'POST',
            data: $('#payments_form').serialize(),
            success: function(response) {
                preloader.stop()
                console.log(response)
                showAlert(response.message);
                $('#payments_form .form-control').removeClass('error-outline');
                $(`#payments_form .bootstrap-select`).css('outline', 'none');
                // $('#payment_id').val(response.payment_id)
                $('#stud_track_id').html(response.student_track_id);
                $('#alert_messge_payment').addClass('d-none');
                $('#alert_messge_payment').hide();
                if (response.status != 'no_invoice') {
                    $('#invoice_generate').removeClass('d-none');
                }
                // updateUrlStudentForm(4,);
                if (response.prolfile_completed) {
                    updateProgress(response.prolfile_completed[0], response.prolfile_completed[1],
                        'primary');
                }

                loadPayedAmount($('#course_period').val());
                // $('#register_new_payment').removeClass('d-none');
                // $('#reverse_transaction').val('false');
                // $('#reverse_payment').removeClass('d-none');


            },
            error: function(xhr) {
                preloader.stop()
                $('#alert_messge_payment').addClass('d-none');
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.error) {
                        $('#alert_messge_payment').addClass('alert-info');
                        $('#alert_messge_payment').removeClass('d-none');
                        $('#alert_messge_payment').html(xhr.responseJSON.error);
                        $('#alert_messge_payment').scrollTop(0);
                    } else {
                        let errors = xhr.responseJSON.errors;

                        if (xhr.responseJSON.error_modal) {
                            $('#modal_create_student').modal('show');
                            $('#modal_create_student #modal_body').html(errors);
                            $('#modal_create_student #modal_title').html(
                                "Create Student Track ID Series Number");
                        } else {

                            $('#education_form .form-control').removeClass('error-outline');
                            $('#form-messages').html('');
                            $(`#education_form .bootstrap-select`).css('outline', 'none');
                            $('#alert_messge_payment').html('');
                            $('#alert_messge_payment').removeClass('d-none alert-success').addClass(
                                'alert-info');
                            let message = "";
                            $.each(errors, function(key, value) {


                                if (key == 'course' || key == 'course_period' || key ==
                                    'promo_code' ||
                                    key ==
                                    'payment_method' || key == 'branch_code') {
                                    $(`#${key}_div .bootstrap-select`).css('outline',
                                        '1px solid red');
                                } else if (key == 'created_by') {
                                    $('#created_by_select').addClass('error-outline');
                                } else {
                                    $('#' + key).addClass('error-outline');
                                }
                                message = value[0].replace(".", ", ")
                                $('#alert_messge_payment').append(message);
                            });

                        }

                    }
                } else {
                    $('#alert_messge_payment').addClass('alert-info');
                    $('#alert_messge_payment').removeClass('d-none');
                    $('#alert_messge_payment').html(
                        "Something went wrong, Please contact IT support");
                }

            }

        });
    }
</script>
