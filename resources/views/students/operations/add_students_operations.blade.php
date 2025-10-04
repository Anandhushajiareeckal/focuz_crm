<link rel="stylesheet" href="{{ asset('/css/bs-stepper.min.css') }}">
<script src="{{ asset('/js/bs-stepper.min.js') }}"></script>
<section class="pt-5 fixed-top pb-5">
    <div class="container-full ">
        <div class="row bg-light shadow-sm  pt-3 pl-5  pb-2 ">
            <div class="col-md-2 d-none d-sm-none d-md-block"></div>
            <div class="col-sm-12 col-md-10 col-12">
                <button class="btn btn-sm btn-dark" id="save_student_details"><i class="fa fa-save"></i>
                    &nbsp;Save </button>
                <button class="btn btn-sm btn-dark d-none" id="reverse_payment"><i class="fa fa-backward"></i>
                    &nbsp;Reverse Payment </button>
                <button class="btn btn-sm btn-dark d-none" id="invoice_generate"><i class="fa fa-file-pdf-o"></i>
                    &nbsp;Print Invoice </button>
                <button class="btn btn-dark btn-sm" id="pre_stepper" onclick="stepper.previous()"
                    @if ($step == 1) disabled @endif>
                    <i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous&nbsp;
                </button>
                <button type="submit" class="btn btn-dark btn-sm" id="next_stepper" onclick="stepper.next()"
                    @if ($student_id_url === null) disabled @endif>
                    &nbsp;Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>
                </button>
                <input type="hidden" name="step_value" id="step_value" value="{{ $step }}">
            </div>

        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        var stepperEl = document.querySelector('.bs-stepper')
        window.stepper = new Stepper(stepperEl);
        const step_value = parseInt($('#step_value').val());
        window.stepper.to(step_value);
        if (step_value == 1) {
            $('#pre_stepper').prop('disabled', true);
        }



        stepperEl.addEventListener('show.bs-stepper', function(event) {
            // You can call prevent to stop the rendering of your step
            // event.preventDefault()
            const step = event.detail.indexStep + 1;
            const student_id = parseInt($('.student_id').val());
            $('#pre_stepper').prop('disabled', false);
            $('#next_stepper').prop('disabled', false);
            if (step == 1) {
                $('#pre_stepper').prop('disabled', true);
            } else if (step == 4) {
                $('#next_stepper').prop('disabled', true);
            }
            updateUrlStudentForm(step, student_id);
            $('#step_value').val(step);
        })


    });

    function updateUrlStudentForm(step, student_id, course_id = '', course_schedule_id = '') {
        let url = "{{ env('APP_URL') }}";
        // console.log(url)
        const pathname = "add_students";
        let urlNew = url + pathname;
        // console.log(urlNew)
        if (isNaN(student_id)) {
            urlNew = `${urlNew}/${step}`;
        } else {
            urlNew = `${urlNew}/${step}/${student_id}`;
        }
        if (course_id) {
            urlNew += `/${course_id}/${course_schedule_id}`;
            const installment_id_param = $('#installment_id_param').val();
            if (installment_id_param) {
                urlNew += `/${installment_id_param}`;
            }
        }

        window.history.replaceState(null, null, urlNew);
    }
</script>
