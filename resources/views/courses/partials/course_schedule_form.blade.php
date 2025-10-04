<table id="scheduleTable" class="table table-sm table-striped">
    <input type="hidden" name="course_id" id="course_id" value="{{ $course_id }}">
    <thead>
        <tr>

            <th>Course Fee</th>
            <th>Commission</th>
            <th>Other Fees</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (!$courseSchedules->isEmpty())
            @foreach ($courseSchedules as $schedule)
                <tr>

                    <input type="hidden" name="course_schedule_id[]" value="{{ old('id[]', $schedule->id) }}">
                    <td><input type="text" name="course_fee[]" class="form-control form-control-sm form_inputs"
                            placeholder="Enter the course fee" value="{{ old('course_fee[]', $schedule->course_fee) }}">
                    </td>
                    <td><input type="text" name="commission[]" class="form-control form-control-sm form_inputs"
                            placeholder="Enter the commission" value="{{ old('commission[]', $schedule->commission) }}">
                    </td>
                    <td><input type="text" name="other_fees[]" class="form-control form-control-sm form_inputs"
                            placeholder="Enter the other fees" value="{{ old('other_fees[]', $schedule->other_fees) }}">
                    </td>
                    <td><input type="text" name="start_date[]"
                            class="form-control form-control-sm datepicker form_inputs"
                            placeholder="Select the start date"
                            value="{{ $schedule->start_date ? date('d-m-Y', strtotime($schedule->start_date)) : '' }}">
                    </td>
                    <td><input type="text" name="end_date[]"
                            class="form-control form-control-sm datepicker form_inputs"
                            placeholder="Select the end date"
                            value="{{ $schedule->end_date ? date('d-m-Y', strtotime($schedule->end_date)) : '' }}">
                    </td>
                    <td>

                        <select name="status[]" class="form-control form-control-sm form_inputs">
                            @php
                                if ($schedule->status == 'active') {
                                    $selected_active = 'selected';
                                    $selected_inactive = '';
                                } elseif ($schedule->status == 'inactive') {
                                    $selected_active = '';
                                    $selected_inactive = 'selected';
                                } else {
                                    $selected_active = '';
                                    $selected_inactive = '';
                                }
                            @endphp
                            <option value="active" {{ $selected_active }}>Active</option>
                            <option value="inactive" {{ $selected_inactive }}>In Active</option>
                        </select>
                    </td>
                    <td class="text-center remove_icon"><i class="fa fa-times text-danger data_exist" style="cursor:pointer"
                            onclick="removeRow(this, {{ $schedule->id }}, {{ $course_id }})"></i></td>
                </tr>
            @endforeach
        @endif
        <!-- Add an empty row if no course schedules are available -->
        @php
            $new_form_row = '<tr>
      
                <input type="hidden" name="course_schedule_id[]" value="">
                <td><input type="text" name="course_fee[]" class="form-control form-control-sm form_inputs" placeholder="Enter the course fee"></td>
                <td><input type="text" name="commission[]" class="form-control form-control-sm form_inputs" placeholder="Enter the commission"></td>
                <td><input type="text" name="other_fees[]" class="form-control form-control-sm form_inputs" placeholder="Enter the other fees"></td>
                <td><input type="text" name="start_date[]" class="form-control form-control-sm datepicker form_inputs" placeholder="Select the start date"></td>
                <td><input type="text" name="end_date[]" class="form-control form-control-sm datepicker form_inputs" placeholder="Select the end date"></td>
                <td>
                    <select name="status[]"  class="form-control form-control-sm form_inputs">
                        <option value="active">Active</option>
                        <option value="inactive">In Active</option>
                    </select>
                </td>
                <td class="text-center remove_icon"><i class="fa fa-times text-danger data_non_exist" style="cursor:pointer" onclick="removeRow(this)"></i></td>
            </tr>';
        @endphp
        {!! $new_form_row !!}
    </tbody>
</table>

<button type="button" class="add-row-btn btn btn-sm btn-primary" onclick="addRow()">Add Row</button>
<button type="button" class="add-row-btn btn btn-sm btn-dark" onclick="saveCourse()">Save Schedules</button>

<script>
    function saveCourse() {
        preloader.load();
        $.ajax({
            type: "post",
            url: "{{ url('save_course_data') }}",
            data: $('#course_add_form').serialize(),
            success: function(response) {
                preloader.stop();

                $('#course_id').val(response.course_id);
                if (response.updatedCourseIds && response.updatedCourseIds.length) {
                    response.updatedCourseIds.forEach(function(updatedCourse, index) {
                        let elemUpdate = $('input[name="course_schedule_id[]"]').eq(
                            index);
                        elemUpdate.val(updatedCourse.course_schedule_id);
                        let currentIcon = $('.remove_icon').eq(index).find('i');

                        currentIcon
                            .attr('onclick', function() {
                                // Dynamically set the onclick with the correct parameters
                                return "removeRow(this," + updatedCourse.course_schedule_id +
                                    "," + response.course_id + ")";
                            });
                    });
                }
                $('#alert_modal').removeClass('d-none alert-info').addClass('alert-success');
                $('#alert_modal').addClass('alert-success');

                $('#alert_modal').html("Successfull");
                $('.form_inputs').removeClass('error-outline');
            },
            error: function(xhr) {
                preloader.stop();

                $('.form_inputs').removeClass('error-outline');

                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;

                    $('.form_inputs').removeClass('error-outline');

                  

                    let error_messages = {}; 


                    $.each(errors, function(key, error_message) {
                        var parts = key.split(/[.\[\]]+/);
                        var name = parts[0];
                        var index = parseInt(parts[1]);

                        if (index) {
                            var inputField = $('#modal_body input[name="' + name + '[]"]').eq(
                                index);
                            inputField.addClass('error-outline');
                            key_error = `ROW ${index + 1}`
                        } else {
                            key_error = 'ERROR';
                        }

                        if (!error_messages[key_error]) {
                            if (inputField) {
                                error_messages[key_error] =
                                    `<strong>${key_error} : </strong>`;
                            } else {
                                error_messages[key_error] =
                                    `<strong>${key_error} : </strong>`;
                            }
                        }

                        $.each(error_message, function(i, message) {
                            let formattedMessage = message
                                .replace(key, `${name}`)
                                .replace("_", " ")
                                .replace(".", ". ")
                            error_messages[key_error] += formattedMessage;
                        });


                    });
                    // Now, build the final message to display in the modal by concatenating all the row messages
                    let final_error_messages = '';
                    $.each(error_messages, function(row, message) {
                        final_error_messages += message + '<br />';
                    });

                    // Display the error messages in the modal
                    $('#alert_modal').removeClass('d-none');
                    $('#alert_modal').html(final_error_messages);


                }
            }
        });
    }

    function addRow() {
        const tbody = document.querySelector('#scheduleTable tbody');
        const row = document.createElement('tr');
        row.innerHTML = @json($new_form_row);
        tbody.appendChild(row);

        $('#modal_body .datepicker').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            minDate: 0
        });
    }

    function removeRow(button, scheduleId = '', courseId = '') {
        const row = button.closest('tr');
        if (scheduleId == '') {
            row.remove();
        } else {
            if (confirm('Are you sure you want to remove this entry?')) {
                $.ajax({
                    type: "post",
                    url: "{{ url('remove_course_schedule') }}", // The server endpoint
                    data: {
                        'schedule_id': scheduleId,
                        'course_id': courseId,
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        row.remove();
                        $('#alert_modal').removeClass('d-none alert-info').addClass('alert-success');
                        $('#alert_modal').html(response.message);
                    },
                    error: function(xhr, status, error) {

                        $('#alert_modal').removeClass('d-none alert-success').addClass('alert-info');
                        if (xhr.responseJSON.message) {
                            $('#alert_modal').html(xhr.responseJSON.message);
                        } else {
                            $('#alert_modal').html("Somthing went wrong, Please contact IT support");
                        }

                    }
                });
            }
        }

    }
</script>
