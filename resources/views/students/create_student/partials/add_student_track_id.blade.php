<form id="studentTrackNoForm">
    @csrf <!-- Laravel CSRF token for security -->
    <div class="form-group">
        <label>University Name</label>
        <p id="universityName">{{ $UniversityName }}</p>
    </div>

    <div class="form-group">
        <label>Branch Name</label>
        <p id="branchName">{{ $branchName }}</p>
    </div>


    <div class="form-group">
        <label>Last Available Number </label>
        <h5 class="text-danger">{{ $student_track_id }}</h5>
    </div>
    <div class="form-group">
        <label for="numberInput">Enter Number</label>
        <input type="number" class="form-control" id="numberInput" name="numberInput"
            placeholder="Enter the Next Series Number" required>
    </div>

    <input type="hidden" id="course_code" name="course_code" value="{{ $course_id }}">
    <input type="hidden" id="branch_code" name="branch_code" value="{{ $branch_id }}">



    <button type="button" onclick="submit_track_form()" id="create_series_no" class="btn btn-primary">Submit</button>
</form>

<script>
    function submit_track_form() {
        preloader.load()
        $.ajax({
            type: "post",
            url: "{{ route('save_student_track_number') }}",
            data: $('#studentTrackNoForm').serialize(),
            success: function(response) {
                preloader.stop();
                // console.log(response)
                $('#numberInput').removeClass('error-outline');
                $('#modal_create_student').modal('hide');
                save_payments_form();
            },
            error: function(xhr) {
                preloader.stop()
                // console.log(xhr.responseJSON.error)
                if (xhr.responseJSON.error) {
                    window.alert(xhr.responseJSON.error);
                } else {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key).addClass('error-outline');
                    });
                }

            }
        });
    }
</script>
