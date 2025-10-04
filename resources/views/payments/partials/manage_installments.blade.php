<style>
    .ui-autocomplete {
        z-index: 9999 !important;
        max-height: 200px;
        overflow: auto;
    }
</style>
<div class="row">
    <div class="col">
        <div class="alert alert-info d-none" id="alert_modal"></div>
    </div>
</div>
<form id="installment_form">
    @csrf
    <input type="hidden" id="exist_id" name="exist_id"
        value="{{ old('installment_id', isset($installment) ? $installment->id : '') }}">
    <input type="checkbox" class="d-none" id="exist_update" name="exist_update"
        value="{{ old('installment_id', isset($installment) ? $installment->id : '') }}">
    <div class="row">
        <!-- First Column: Student Selection -->
        <div class="col-sm-4">
            <div class="form-group">
                <label for="student_id">Student <span class="text-danger"> * </span></label>
                <div class="input-group date" id="student_id_group" data-target-input="nearest">
                    <input id="student_autocomplete" type="text" class="form-control form-control-sm form_inputs"
                        data-target="#student_id_group"
                        value="{{ old('student_id', isset($installment) ? $installment->first_name : '') }}"
                        placeholder="Search student by name, email">
                    <div class="input-group-append" data-target="#student_id_group" data-toggle="datetimepicker"
                        required>
                        <div class="input-group-text" style="cursor: pointer" onclick="clear_student_autocomplete()"><i
                                class="fa fa-eraser"></i></div>
                    </div>
                </div>

                <input type="hidden" id="student_id" name="student_id"
                    value="{{ old('student_id', isset($installment) ? $installment->student_id : '') }}">
            </div>
        </div>

        <!-- Second Column: Course and Schedule Selection -->
        <div class="col-sm-8">
            <div class="form-group" id="course_div">
                <label for="course">Course <span class="text-danger"> * </span></label>
                <select class="form-control form-control-sm form_inputs selectpicker" id="course" name="course"
                    required>
                    <option value="">Select Course</option>

                </select>
            </div>
        </div>



        <div class="col-sm-4">
            <div class="form-group">
                <label for="start_date">Start Date <span class="text-danger"> * </span></label>
                <input type="text" placeholder="Select date from"
                    class="form-control form-control-sm form_inputs datepicker" id="start_date" name="start_date"
                    value="{{ old('start_date', isset($installment) ? $installment->start_date : '') }}" required>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="end_date">End Date <span class="text-danger"> * </span></label>
                <input type="text" placeholder="Select date to"
                    class="form-control form-control-sm form_inputs datepicker" id="end_date" name="end_date"
                    value="{{ old('end_date', isset($installment) ? $installment->end_date : '') }}" required>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="end_date">Next Reminder Date  <span class="text-danger"> * </span></label>
                <input type="text" placeholder="Select Reminder date"
                    class="form-control form-control-sm form_inputs datepicker" id="reminder_date" name="reminder_date"
                    value="{{ old('next_reminder_date', isset($installment) ? $installment->next_reminder_date : '') }}"
                    required>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="next_reminder_days">Reminder Days (Monthly / No of Days)  <span class="text-danger"> * </span></label>
                <input type="text" placeholder="Enter Reminder Days (Monthly / No of Days)"
                    class="form-control form-control-sm form_inputs" id="next_reminder_days" name="next_reminder_days"
                    value="{{ old('next_reminder_days', isset($installment) ? $installment->next_reminder_days : '') }}"
                    required>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="due_days">Due Days (No of days after reminder date)</label>
                <input type="number" placeholder="Enter no of days due after reminder date"
                    class="form-control form-control-sm form_inputs" id="due_days" name="due_days"
                    value="{{ old('due_days', isset($installment) ? $installment->due_days : '') }}" required>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="installment_amount"> Amount / Installment <span class="text-danger"> * </span></label>
                <input type="number" step="0.01" placeholder="Enter installment amount"
                    class="form-control form-control-sm form_inputs change_installment" id="installment_amount"
                    name="installment_amount"
                    value="{{ old('installment_amount', isset($installment) ? $installment->installment_amount : '') }}"
                    required>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="installment_amount">No Of Installments <span class="text-danger"> * </span></label>
                <input type="number" placeholder="Enter no of Installments"
                    class="form-control form-control-sm form_inputs change_installment" id="number_of_installments"
                    name="number_of_installments"
                    value="{{ old('number_of_installments', isset($installment) ? $installment->number_of_installments : '') }}"
                    required>
            </div>
        </div>
        <div class="col-sm-4">
            <label for="">Total Amount</label>
            <br />
            <span id="total_amount" class="font-weight-bold text-info"></span>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" id="installment_btn"
            class="btn btn-sm btn-dark">{{ isset($installment) ? 'Update Installment' : 'Save Installment' }}</button>
    </div>

</form>

<script>
    $(document).ready(function() {

        $('.change_installment').change(function(e) {
            e.preventDefault();
            const number_of_installments = parseFloat($('#number_of_installments').val());
            const installment_amount = parseFloat($('#installment_amount').val());
          
            if (number_of_installments && installment_amount) {
                const total_amount = number_of_installments * installment_amount;
                console.log(number_of_installments)
                const formatter = new Intl.NumberFormat('en-US', {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                const formatted_total = formatter.format(total_amount);

                $('#modal_body #total_amount').html(formatted_total);
            }

        });

        $('#installment_btn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission

            $('.form_inputs').removeClass('error-outline');


            $.ajax({
                url: "{{ route('save_installment') }}", // Change this to your appropriate route
                type: 'POST',
                data: $('#installment_form').serialize(),
                success: function(response) {
                    $('.form_inputs').removeClass('error-outline');
                    $('#alert_modal').removeClass('d-none alert-info').addClass(
                        'alert-success');
                    $('#alert_modal').html(response.message);
                    $('#modal_body #exist_id').val(exist_id);
                },
                error: function(xhr) {
                    $('.form_inputs').removeClass('error-outline');
                    $('#alert_modal').removeClass('d-none alert-success').addClass(
                        'alert-info');
                    if (xhr.responseJSON) { // Validation errors
                        if (xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            let error_messages = [];
                            const selectpicker_ids = ['course'];

                            $.each(errors, function(key, error_message) {
                                if (key == 'student_id') {
                                    key = 'student_autocomplete'
                                }
                                if (selectpicker_ids.includes(key)) {
                                    $(`#modal_body #${key}_div .bootstrap-select`)
                                        .addClass('error-outline');
                                } else {
                                    $('#modal_body #' + key).addClass(
                                        'error-outline');
                                }

                                $.each(error_message, function(i, message) {
                                    let formattedMessage = message
                                        .replace("_", " ")
                                        .replace(".", ". ")
                                    error_messages.push(formattedMessage);
                                });
                            });
                            $('#alert_modal').removeClass('d-none');
                            $('#alert_modal').html(error_messages.join(", "));
                        } else {

                            if (xhr.responseJSON.error) {
                                $('#alert_modal').html(response.responseJSON.error);
                                if (xhr.responseJSON.exist_id) {
                                    $('#exist_update').removeClass('d-none');
                                    $('#exist_update').val(exist_id);
                                }
                            } else {
                                $('#alert_modal').html(
                                    "Something went wrong, Please contact IT support");
                            }

                        }

                    } else {
                        $('#alert_modal').html(
                            "Something went wrong, Please contact IT support");
                    }
                }
            });
        });

        $('#modal_body .datepicker').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            minDate: 0
        });

        $('#student_autocomplete').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('search_students') }}",
                    method: "post",
                    data: {
                        term: request.term,
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        console.log(data)
                        response(data); // Pass the results to the autocomplete widget
                    },
                    error(xhr) {
                        $('#modal_body #alert_modal').html(
                            'Something Went Wrong, Please Contact IT support');
                        $('#modal_body #alert_modal').removeClass('d-none')
                    }
                });
            },
            select: function(event, ui) {
                // Set the selected student's ID when a result is selected
                $('#student_id').val(ui.item.id);


                loadCourses(ui.item.id);
            },
            minLength: 2, // Minimum characters before starting search
            autoFocus: true // Automatically select the first result
        });

        // Function to load courses based on the selected student ID
        async function loadCourses(studentId) {
            let params = {
                'studentId': studentId
            }
            await loadOptions(params, "{{ url('load_student_courses') }}", updateOptions,
                '#modal_body #course');

        }

        // Function to load schedules based on the selected course ID
        function loadSchedules(courseId) {
            if (courseId) {
                $.ajax({
                    url: '/get-schedules-by-course/' + courseId,
                    method: 'GET',
                    success: function(data) {
                        $('#course_schedule_id').html(
                            '<option value="">Select Schedule</option>'); // Clear previous options
                        $.each(data.schedules, function(key, value) {
                            $('#course_schedule_id').append('<option value="' + value.id +
                                '">' + value.schedule_name + '</option>');
                        });

                        // If editing an existing installment, select the schedule
                        if ($("#course_schedule_id").val() === '') {
                            $('#course_schedule_id').val(
                                '{{ old('course_schedule_id', isset($installment) ? $installment->course_schedule_id : '') }}'
                            );
                        }
                    },
                    error: function() {
                        alert("Error loading course schedules.");
                    }
                });
            } else {
                $('#course_schedule_id').html('<option value="">Select Schedule</option>');
            }
        }

        // Trigger loading of courses and schedules on page load if student ID is available
        if ($('#student_id').val()) {
            loadCourses($('#student_id').val());
        }

        // Handle form submission via AJAX
        $('#installment_form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var installmentId = $('#installment_id').val();

            $.ajax({
                url: installmentId ? '/installments/' + installmentId :
                '/installments', // If installmentId exists, update; otherwise, create new
                method: installmentId ? 'PUT' : 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response.message);
                    if (response.success) {
                        window.location.href = response.redirect_url;
                    }
                },
                error: function(response) {
                    alert("Error occurred. Please try again.");
                }
            });
        });
    });

    function clear_student_autocomplete() {
        $('#student_autocomplete').val('')
        $('#student_id').val('')
    }
</script>
