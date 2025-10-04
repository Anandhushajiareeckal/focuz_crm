<form id="course_add_form">
    @csrf <!-- Laravel CSRF token for security -->
    <input type="hidden" name="id_exist" value="{{ $course_data->id }}">

    <div class="row">
        <div class="col">
            <div class="alert alert-sm alert-info d-none" id="alert_modal">

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group" id="university_div">
                <label for="university">University</label>
                <select name="university" id="university" data-size="5" data-live-search="true"
                    class="form-control form-control-sm selectpicker form_inputs">
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group" id="stream_div">
                <label for="stream">Stream</label>
                <select name="stream" id="stream" class="form-control form-control-sm selectpicker form_inputs"
                    data-size="5" data-live-search="true"></select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group" id="specialization_div">
                <label for="specialization">Specialization <span class="text-info" style="font-size: 11px">(Type & press
                        Enter to save new)</span></label>
                <select name="specialization[]" id="specialization"
                    class="form-control form-control-sm selectpicker form_inputs" data-actions-box="true"
                    data-live-search="true" data-size="5" multiple></select>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label for="status_load">Status</label>
                <select name="status_load" id="status_load" class="form-control form-control-sm form_inputs">
                    <option value="active" selected>Active</option>
                    <option value="inactive">In Active</option>
                    <option value="all">All</option>
                </select>
            </div>
        </div>
        <div class="col-sm-4">

            <button type="button" onclick="getCourseScheduleForm()" class="btn btn-sm btn-dark">Load Course Schedule
                Form</button>
        </div>
    </div>


    <!-- Date Fields (Start Date and End Date in the same row) -->
    <div id="course_schedule_rows" class="mt-2">

    </div>

    <!-- Submit Button -->

</form>

<script>
    $(document).ready(function() {
        $('#selectpicker').selectpicker();

    });

    async function getCourseScheduleForm() {
        $('#alert_modal').addClass('d-none');
        preloader.load();
        $('.selectpicker').selectpicker('refresh');
        try {
            const response = await new Promise((resolve, reject) => {
                $.ajax({
                    type: "POST",
                    url: "{{ route('get_course_schedule_form') }}", // Assuming 'save_promo' is the named route to store the discount
                    data: {
                        'university': $('#university').val(),
                        'stream': $('#stream').val(),
                        'specialization': $('#specialization').val(),
                        'status_load': $('#status_load').val(),
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        resolve(response); // Resolve with the actual response
                    },
                    error: function(xhr) {
                        reject(xhr); // Reject with the error response
                    }
                });
            });

            // Process response and update the DOM
            console.log(response)
            $('#modal_body #course_schedule_rows').html(response);

            // Initialize date picker
            $('#modal_body .datepicker').datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,

            });

            // Reset modal and form styles
            $('#alert_modal').addClass('d-none');
            $('#alert_modal').html("");
            $('.form_inputs').removeClass('error-outline');

        } catch (xhr) {
            // Stop loading animation in the finally block
            preloader.stop();

            // Handle error
            $('#alert_modal').addClass('d-none');
            $('#alert_modal').removeClass('alert-success');
            $('#alert_modal').removeClass('alert-info');
            $('#alert_modal').html("");
            $('.form_inputs').removeClass('error-outline');

            if (xhr.responseJSON && xhr.responseJSON.error) {
                window.alert(xhr.responseJSON.error); // Display single error
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                let errors = xhr.responseJSON.errors;
                let error_keys = [];
                const selectpicker_ids = ['university', 'stream', 'specialization'];

                // Process each error and highlight fields
                $.each(errors, function(key, value) {
                    if (selectpicker_ids.includes(key)) {
                        $(`#modal_body #${key}_div .bootstrap-select`)
                            .addClass('error-outline');
                    } else {
                        $('#modal_body #' + key).addClass('error-outline');
                    }
                    error_keys.push(value);
                });

                $('#alert_modal').removeClass('d-none alert-success').addClass('alert-info');
                $('#alert_modal').html(error_keys.join(", "));

            } else {
                $('#alert_modal').removeClass('d-none alert-success').addClass('alert-info');
                $('#alert_modal').html("Something went wrong, Please contact IT support");
            }
        } finally {
            preloader.stop(); // Ensure preloader stops even if there is an error
        }
    }
</script>
