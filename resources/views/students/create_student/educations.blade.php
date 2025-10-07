<!-- Educational Qualification Card -->
<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">Educational Qualification</h3>
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
        <form id="education_form">
            @csrf
            <input type="hidden" class="student_id" name="student_id" value="{{ $student_id_url }}">
            <input type="hidden" id="education_id" name="education_id" value="">

            <div class="row">
                <!-- SSLC Details -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sslc_board">SSLC Board <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="sslc_board"
                               name="sslc_board" placeholder="Enter SSLC Board"
                               value="{{ $educationData->sslc_board }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sslc_passout">SSLC Passout Year <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="sslc_passout"
                               name="sslc_passout" placeholder="Enter Passout Year"
                               value="{{ $educationData->sslc_passout }}">
                    </div>
                </div>

                <!-- Intermediate Details -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="intermediate_board">Intermediate Board <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="intermediate_board"
                               name="intermediate_board" placeholder="Enter Intermediate Board"
                               value="{{ $educationData->intermediate_board }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="intermediate_passout">Intermediate Passout Year <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="intermediate_passout"
                               name="intermediate_passout" placeholder="Enter Passout Year"
                               value="{{ $educationData->intermediate_passout }}">
                    </div>
                </div>

                <!-- Last Completed Course -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="other_degree_name">Last Completed Course <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="other_degree_name"
                               name="other_degree_name" placeholder="Other Course Name"
                               value="{{ $educationData->other_degree_name }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="other_institute_name">University/ Board/Institute <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="other_institute_name"
                               name="other_institute_name" placeholder="Other Institute Name"
                               value="{{ $educationData->other_college_name }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="passout_year">Passout Year <span class="text-danger">*</span></label>
                        <input type="text" name="passout_year" id="passout_year"
                               class="form-control form-control-sm" placeholder="Enter pass out year"
                               value="{{ $educationData->graduation_year }}" />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="gpa">GPA</label>
                        <input type="text" class="form-control form-control-sm" id="gpa" name="gpa"
                               placeholder="Enter GPA" value="{{ $educationData->gpa }}">
                    </div>
                </div>
                
            </div>
        </form>
    </div>
</div>


{{-- ✅ New University Details Section --}}
<div class="card card-default ">
    <div class="card-header">
        <h3 class="card-title">University Details</h3>
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
        <form id="university_form">
            @csrf
            <input type="hidden" class="student_id" name="student_id" value="{{ $student_id_url }}">
            <input type="hidden" id="university_id" name="university_id" value="">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="abc_id">ABC ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="abc_id"
                               name="abc_id" placeholder="Enter ABC ID"
                               value="{{ $educationData->abc_id ?? '' }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="deb_id">DEB ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="deb_id"
                               name="deb_id" placeholder="Enter DEB ID"
                               value="{{ $educationData->deb_id ?? '' }}">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>



<script>
    $(document).ready(function() {

        // const institution_id = "{{ $educationData->institution_id }}";
        // if (institution_id != '') {
        //     const params_default = {
        //         'university_id': institution_id
        //     };
        //     loadOptions(params_default, 'load_university', updateOptions, '#university', institution_id);
        // }
        // $('#university').on('show.bs.select', function(e) {
        //     const $searchInput = $(this).closest('.bootstrap-select').find('.bs-searchbox input');
        //     $searchInput.on('keyup', function() {
        //         const name = $(this).val();
        //         const params = {
        //             'name': name
        //         };

        //         loadOptions(params, 'load_university', updateOptions, '#university', '', '',
        //             'save');
        //     });
        // });
    });

    function save_educational_qualification() {
    preloader.load();

    // Combine both form data
    let formData = $('#education_form, #university_form').serialize();

    $.ajax({
        url: "{{ route('save_edcation_info') }}",
        type: 'POST',
        data: formData,
        success: function(response) {
            preloader.stop();
            showAlert(response.success);
            $('#education_form .form-control, #university_form .form-control').removeClass('error-outline');
            $('#education_id, #university_id').val(response.education_id);
            $('#alert_messge_edu').addClass('d-none');
            updateProgress(response.prolfile_completed[0], response.prolfile_completed[1], 'warning');
        },
        error: function(xhr) {
            preloader.stop();
            let errors = xhr.responseJSON.errors;
            $('#education_form .form-control, #university_form .form-control').removeClass('error-outline');
            $('#alert_messge_edu').html('').addClass('d-none');
            let message = "";
            $.each(errors, function(key, value) {
                $('#' + key).addClass('error-outline');
                message = value[0] + " ";
            });
            $('#alert_messge_edu').removeClass('d-none').addClass('alert-info').append(message);
        }
    });
}



//     function save_university_details() {
//     preloader.load();
//     $.ajax({
//         url: "{{ route('save_edcation_info') }}", // same controller route
//         type: 'POST',
//         data: $('#university_form').serialize(),
//         success: function(response) {
//             preloader.stop();
//             showAlert(response.success);
//             $('#university_form .form-control').removeClass('error-outline');
//             $('#university_id').val(response.education_id);
//             $('#alert_messge_university').addClass('d-none');
//             updateProgress(response.prolfile_completed[0], response.prolfile_completed[1], 'warning');
//         },
//         error: function(xhr) {
//             preloader.stop();
//             let errors = xhr.responseJSON.errors;
//             $('#university_form .form-control').removeClass('error-outline');
//             $('#alert_messge_university').html('');
//             $('#alert_messge_university').addClass('d-none');
//             let message = "";
//             $.each(errors, function(key, value) {
//                 $('#alert_messge_university').addClass('alert-info');
//                 $('#alert_messge_university').removeClass('d-none');
//                 $('#alert_messge_university').show();
//                 $('#' + key).addClass('error-outline');
//                 message = value[0] + " ";
//             });
//             $('#alert_messge_university').append(message);
//         }
//     });
// }

</script>
