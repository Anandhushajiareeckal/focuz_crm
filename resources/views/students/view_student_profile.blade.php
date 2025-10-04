@extends('layouts.layout')

@section('content')
<section class="content">
    <div class="container px-5">
        <div class="row">
            <div class="col">
                <div class="progress mb-2">
                    @if ($profile_completed == 0)
                    @php
                    $profile_completed_per = 15;
                    @endphp
                    @else
                    @php
                    $profile_completed_per = $profile_completed * 25;
                    @endphp
                    @endif
                    <div class="progress-bar bg-{{ $completed_levels[$profile_completed_per . '%'] }}" role="progressbar"
                        aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                        style="width: {{ $profile_completed_per }}%">
                        <span>{{ $profile_completed * 25 }}% Completed </span>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="bs-stepper linear">
            <div class="bs-stepper-header" role="tablist">
                <div class="step active step_click" data-target="#personal_info" step="1" style="cursor: pointer">
                    <button type="button" class="step-trigger " role="tab" step="1"
                        style="cursor: pointer" aria-controls="personal_info" id="personal_info-trigger"
                        aria-selected="true">
                        <span class="bs-stepper-circle">1</span>
                        <span class="bs-stepper-label">Personal Info</span>
                    </button>
                </div>

                <div class="line">
                </div>
                <div class="step step_click" data-target="#educations_part" step="2" style="cursor: pointer">
                    <button type="button" class="step-trigger" role="tab"
                        aria-controls="educations_part" id="educations_part-trigger" aria-selected="false" disabled>
                        <span class="bs-stepper-circle">2</span>
                        <span class="bs-stepper-label">Education</span>
                    </button>
                </div>
                <div class="line"></div>

                <div class="step step_click" data-target="#document_upload" step="3" style="cursor: pointer">
                    <button type="button" class="step-trigger" role="tab" aria-controls="document_upload"
                        id="document_upload-trigger" aria-selected="false" disabled>
                        <span class="bs-stepper-circle">3</span>
                        <span class="bs-stepper-label">Document Upload</span>
                    </button>
                </div>
                <div class="line"></div>
                <div class="step step_click" data-target="#payments_part" step="4" style="cursor: pointer">
                    <button type="button" class="step-trigger " role="tab"
                        aria-controls="payments_part" id="payments_part-trigger" aria-selected="false" disabled>
                        <span class="bs-stepper-circle">4</span>
                        <span class="bs-stepper-label">Payments</span>
                    </button>
                </div>
            </div>
            <div class="bs-stepper-content">
                <div id="personal_info" class="content active dstepper-block" role="tabpanel"
                    aria-labelledby="personal_info-trigger">
                    @include('students.student_profile.profile_personal_info')
                </div>
                <div id="educations_part" class="content" role="tabpanel" aria-labelledby="educations_part-trigger">
                    @include('students.student_profile.view_educations')
                </div>
                <div id="document_upload" class="content" role="tabpanel" aria-labelledby="document_upload-trigger">
                    @include('students.student_profile.view_documents')
                </div>
                <div id="payments_part" class="content" role="tabpanel" aria-labelledby="payments_part-trigger">
                    @include('students.student_profile.view_payments')
                </div>
               
            </div>
        </div>


</section>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // stepper.to(3)

        $('#save_student_details').on('click', function() {
            var currentStepIndex = window.stepper._currentIndex;
            if (window.confirm("Are you sure you want to update the student's details?")) {
                if (currentStepIndex == 0) {
                    save_students_personal_info();

                } else if (currentStepIndex == 1) {
                    save_educational_qualification();
                } else if (currentStepIndex == 2) {

                    save_docs_form();
                } else if (currentStepIndex == 3) {
                    save_payments_form();
                }

            }
        });

    })

    function loadOptions(params, url, func, elem, selected_id = '', trigger_change = '') {
        params._token = "{{ csrf_token() }}";
        preloader.load('loader-container');
        $.ajax({
            url: `{{ url('${url}') }}`,
            type: 'post',
            data: params,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                preloader.stop('loader-container');
                func(response, elem, selected_id, trigger_change);
            },
        });
    }

    function updateOptions(data, elem, selected_id = 0, trigger_change = '') {
        const $elem = $(elem);
        $elem.empty(); // Clear current options
        $elem.append(`<option value="">Select Option</option>`);

        data.forEach(item => {
            let selected = selected_id == item.id ? 'selected' : '';
            let option = '';

            if (item.state) {
                option = `<option 
                        value="${item.id}" 
                        state_name="${item.state.name}" 
                        state_id="${item.state.id}" 
                        country_id="${item.state.country.id}" 
                        country_name="${item.state.country.name}" 
                        ${selected}>
                            ${item.name}, ${item.state.name}, ${item.state.country.name}
                    </option>`;
            } else if (item.country_id) {
                const country_name = item.country ? item.country.name : item.country_name;
                option = `<option value="${item.id}" country_name="${country_name}" country_id="${item.country_id}" ${selected}>
                    ${item.name}, ${country_name}
                </option>`;
            } else {
                option = `<option value="${item.id}" ${selected}>${item.name}</option>`;
            }

            $elem.append(option);
        });

        $elem.selectpicker('refresh'); // Refresh the selectpicker once

        if (trigger_change) {
            $elem.trigger('change');
        }
    }
</script>
@endsection