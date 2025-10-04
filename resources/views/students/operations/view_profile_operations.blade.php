<link rel="stylesheet" href="{{ asset('/css/bs-stepper.min.css') }}">
<script src="{{ asset('/js/bs-stepper.min.js') }}"></script>
<section class="pt-5 fixed-top pb-5">
    <div class="container-full ">
        <div class="row bg-light shadow-sm  pt-3 pl-5  pb-2 ">
            <div class="col-md-2 d-none d-sm-none d-md-block"></div>
            <div class="col-sm-12 col-md-10 col-12">
                <a class="btn btn-sm btn-dark" href="{{ route('add_students', [1, $student_id]) }}"><i
                        class="fa fa-edit"></i>
                    &nbsp;Edit Profile </a>
                <button class="btn btn-dark btn-sm" id="pre_stepper" onclick="stepper.previous()"
                    @if ($step == 1) disabled @endif>
                    <i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous&nbsp;
                </button>
                <button type="submit" class="btn btn-dark btn-sm" id="next_stepper" onclick="stepper.next()">
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
        $('.step_click').click(function(e) {
            e.preventDefault();
            const step = $(this).attr('step');
            window.stepper.to(step);
            // alert(step)
        });

        stepperEl.addEventListener('show.bs-stepper', function(event) {
            // You can call prevent to stop the rendering of your step
            // event.preventDefault()
            const step = event.detail.indexStep + 1;
            const student_id = "{{ $student_id }}";
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

    function updateUrlStudentForm(step, student_id) {
        let url = "{{ env('APP_URL') }}";
        // console.log(url)
        const pathname = "view_profile";
        let urlNew = url + pathname;
        // console.log(urlNew)
        if (isNaN(student_id)) {
            urlNew = `${urlNew}/${step}`;
        } else {
            urlNew = `${urlNew}/${step}/${student_id}`;
        }
        window.history.replaceState(null, null, urlNew);
    }
</script>
