<div class="card card-default">

    <div class="card-header">
        <h3 class="card-title">Personal Info</h3>
        <div class="card-tools">

            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="alert alert-sm alert-success d-none" id="alert_messge">

                </div>
            </div>
        </div>
        <form id="form_personal_info" method="post">
            @csrf
            <input type="hidden" class="student_id" name="student_id" value="{{ $student_id_url }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="fname" name="fname"
                            placeholder="First name" value="{{ $studentData->first_name }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" class="form-control form-control-sm" id="lname" name="lname"
                            placeholder="Last name" value="{{ $studentData->last_name }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Father's Name  <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="father_name" name="father_name"
                            placeholder="Father name" value="{{ $studentData->fathers_name }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="mother_name">Mother's Name  <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="mother_name" name="mother_name"
                            placeholder="Mother name" value="{{ $studentData->mothers_name }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="marital_status">Marrital Status </label>

                        <select name="marital_status" id="marital_status" class="form-control form-control-sm">
                            <option value="">Select Marital Status</option>
                            @foreach ($marital_statusAr as $marital_status)
                                <option value="{{ $marital_status->id }}"
                                    {{ $marital_status->id == $studentData->marital_status_id ? 'selected' : '' }}>
                                    {{ $marital_status->marital_status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employment_status">Employement Status </label>

                        <select name="employment_status" id="employment_status" class="form-control form-control-sm">
                            <option value="">Select Employment Status</option>
                            @foreach ($employment_statusesAr as $employment_status)
                                <option value="{{ $employment_status->id }}"
                                    {{ $employment_status->id == $studentData->employment_status_id ? 'selected' : '' }}>
                                    {{ $employment_status->status_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="religion">Religion</label>

                        <select name="religion" id="religion" class="form-control form-control-sm">
                            <option value="">Select Religion</option>
                            @foreach ($religionsAr as $religion)
                                @if ($religion->id == $studentData->religion_id)
                                    <option value="{{ $religion->id }}" selected>{{ $religion->religion_name }}
                                    </option>
                                @else
                                    <option value="{{ $religion->id }}">{{ $religion->religion_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="religion_category">Religion Category</label>

                        <select name="religion_category" id="religion_category" class="form-control form-control-sm">
                            <option value="">Select Religion</option>
                            @foreach ($religion_categoriesAr as $religion_category)
                                @if ($religion_category->id == $studentData->religion_category_id)
                                    <option value="{{ $religion_category->id }}" selected>
                                        {{ $religion_category->religion_category }}</option>
                                @else
                                    <option value="{{ $religion_category->id }}">
                                        {{ $religion_category->religion_category }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="locations_div">
                <div class="col-md-6" id="city_div">
                    <div class="form-group">
                        <label for="city">City</label><br />
                        <div class="input-group  mb-3">

                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu" style="cursor:pointer">
                                    {{-- <li class="dropdown-item" id="auto_fill_btn" checked="checked">Auto Fill

                                        <input type="checkbox" class="d-none" id="auto_fill">
                                    </li> --}}
                                    <li class="dropdown-item">Add New</li>
                                    <li class="dropdown-item">Edit Selected</li>
                                </ul>
                            </div>

                            <select class="form-control selectpicker" data-size="10" id="city" name="city"
                                data-live-search="true" style="width: 100%;">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="state_div">
                    <div class="form-group">
                        <label for="state">State <span class="text-danger">*</span></label>
                        <select class="form-control selectpicker" data-size="10" id="state" name="state"
                            data-live-search="true" style="width: 100%;">
                        </select>
                    </div>
                </div>

                <div class="col-md-6" id="country_div">
                    <div class="form-group">
                        <label for="country">Country <span class="text-danger">*</span></label>
                        <select class="form-control selectpicker_country selectpicker" data-size="10" id="country"
                            name="country" data-live-search="true" style="width: 100%;">
                        </select>
                    </div>
                </div>
                <div class="col-md-6" id="postal_div">
                    <div class="form-group">
                        <label for="postal_code">Postal code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="postal_code"
                            name="postal_code" placeholder="Postal code" value="{{ $studentData->postal_code }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="address">House Name / No <span class="text-danger">*</span></label>
                        <textarea name="address" id="address" class="form-control form-control-sm" placeholder="Enter the address">{{ nl2br($studentData->address) }}</textarea>
                    </div>
                </div>
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        <label for="nationality">Nationality</label>
                        <select class="form-control selectpicker" data-size="10" id="nationality" name="nationality"
                            data-live-search="true" style="width: 100%;">
                        </select>
                    </div>
                </div> --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gender">Gender <span class="text-danger">*</span></label>

                        <select name="gender" id="gender" class="form-control form-control-sm" required>
                            <option value="">Select option</option>
                            @foreach ($genderOptionsAr as $genderOption)
                                @if ($genderOption == $studentData->gender && $genderOption != '')
                                    <option value="{{ $genderOption }}" selected>{{ ucwords($genderOption) }}
                                    </option>
                                @else
                                    <option value="{{ $genderOption }}">{{ ucwords($genderOption) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="text" name="email" id="email" class="form-control form-control-sm"
                        placeholder="Enter the email" value="{{ $studentData->email }}" />
                        <!-- <div class="input-group  input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <input type="checkbox" name="email_update" id="email_update"
                                        {{ $checked_update_email }} />&nbsp;Update
                                </span>
                            </div>
                           
                        </div> -->



                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" id="phone" class="form-control form-control-sm"
                            placeholder="Enter the phone number" value="{{ $studentData->phone_number }}" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alt_phone">Alternative Phone</label>
                        <input type="text" name="alt_phone" id="alt_phone" class="form-control form-control-sm"
                            placeholder="Enter the alternative phone number"
                            value="{{ $studentData->alternative_number }}" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                        @if ($studentData->date_of_birth != null)
                            @php
                                $dob = date('d-m-Y', strtotime($studentData->date_of_birth));
                            @endphp
                        @else
                            @php
                                $dob = "";
                            @endphp
                        @endif
                        <input type="text" name="dob" id="dob"
                            class="form-control form-control-sm datepicker" placeholder="Select date of birth"
                            value="{{$dob}}" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_type">Identity Card Type <span class="text-danger">*</span></label>
                        <select name="id_type" id="id_type" class="form-control form-control-sm">
                            <option value="">Select Option</option>
                            @foreach ($idCardAr as $idCard)
                                @if ($studentData->identity_card_id == $idCard->id && $studentData->identity_card_id != '')
                                    <option value="{{ $idCard->id }}" selected>{{ $idCard->name }}</option>
                                @else
                                    <option value="{{ $idCard->id }}">{{ $idCard->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_num">Identity Number <span class="text-danger">*</span></label>
                        <input type="text" name="id_num" id="id_num" class="form-control form-control-sm"
                            placeholder="Enter ID number" value="{{ $studentData->identity_card_no }}" />
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        <label for="emergency_contact_name">Emergency Contact Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name"
                            class="form-control form-control-sm" placeholder="Enter Emergency Contact Name"
                            value="{{ $studentData->emergency_contact_name }}" />
                    </div>
                </div> --}}
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        <label for="emergency_contact_tel">Emergency Contact Number <span
                                class="text-danger">*</span></label>
                        <input type="text" name="emergency_contact_tel" id="emergency_contact_tel"
                            class="form-control form-control-sm" placeholder="Enter Emergency Tel"
                            value="{{ $studentData->emergency_contact_phone }}" />
                    </div>
                </div> --}}
                
            </div>
        </form>


    </div>

</div>

<script>
    $(document).ready(function() {
        const city_id = "{{ $studentData->city_id }}";



        if (city_id != "") {
            let params_default = {
                'city_id': city_id,
            }
            loadOptions(params_default, 'load_cities', updateOptions, '#city', city_id, 'trigger_change');

            const nationality_id = "{{ $studentData->nationality_id }}";
            if (nationality_id != "") {
                params_default = {
                    'current_country_id': nationality_id,
                }
                loadOptions(params_default, 'load_countries', updateOptions,
                    '#nationality', nationality_id);
            }
        }
        // save_qualification
        // save_payments
        // save_all
        $('#dob').datepicker({
            changeYear: true,
            changeMonth: true,
            yearRange: '1965:-4',
            dateFormat: "dd-mm-yy", // Set the date format
        });


        // $('#auto_fill_btn').click(function() {
        //     var $locationsDiv = $('#locations_div');
        //     var $cityDiv = $('#city_div');
        //     var $stateDiv = $('#state_div');
        //     var $countryDiv = $('#country_div');
        //     var $postal_div = $('#postal_div');
        //     if ($('#auto_fill').is(':checked')) {
        //         $('#auto_fill').prop('checked', false);
        //         // Auto-fill is checked, order: city, state, country
        //         $locationsDiv.append($cityDiv);
        //         $locationsDiv.append($stateDiv);
        //         $locationsDiv.append($countryDiv);
        //     } else {
        //         $('#auto_fill').prop('checked', true);
        //         // Auto-fill is unchecked, order: country, state, city
        //         $locationsDiv.append($countryDiv);
        //         $locationsDiv.append($stateDiv);
        //         $locationsDiv.append($cityDiv);
        //     }
        //     $locationsDiv.append($postal_div);
        // });



        $('#locations_div #city').on('show.bs.select', function(e) {

            const $searchInput = $(this).closest('.bootstrap-select').find('.bs-searchbox input');
            // const auto_fill = $('#locations_div #auto_fill').is(':checked');
            // if (auto_fill == true) {
            // console.log('name')
            $searchInput.on('keyup', function() {
                const name = $(this).val();

                const params = {
                    'name': name
                };
                loadOptions(params, 'load_cities', updateOptions, '#locations_div #city');
            });
            // }
        });



        $('#locations_div #city').on('changed.bs.select', async function(e, clickedIndex, isSelected,
            previousValue) {
            e.preventDefault();
            $('#pre_city_id').val($(this).val());
            const state_id = $(this).find(':selected').attr('state_id');
            const state_name = $(this).find(':selected').attr('state_name');
            const country_id = $(this).find(':selected').attr('country_id');
            const country_name = $(this).find(':selected').attr('country_name');
            let data = [{
                'id': state_id,
                'name': state_name,
                'country_id': country_id,
                'country_name': country_name,
            }];

            await updateOptions(data, '#state', state_id);

            $('#locations_div #state').selectpicker('refresh');
            update_country($(this));
        });

        $('#locations_div #state').on('show.bs.select', async function(e) {
            const $searchInput = $(this).closest('.bootstrap-select').find('.bs-searchbox input');
            // let auto_fill = $('#locations_div #auto_fill').is(':checked');

            // if (auto_fill == true) {
            $searchInput.on('keyup', async function() {
                const name = $(this).val();
                const params = {
                    'name': name
                };
                await loadOptions(params, 'load_states', updateOptions,
                    '#locations_div #state');
                update_country($(this));
            });
            // } else {
            //     let pre_city_id = $('#locations_div #city').find(':selected').val();
            //     if (pre_city_id === null || pre_city_id == '') {
            //         pre_city_id = '';
            //     }
            //     const params = {
            //         'current_state_id': $(this).val(),
            //     }
            //     await loadOptions(params, 'load_cities', updateOptions,
            //         '#locations_div #city', pre_city_id);
            // }
        });



        $('#locations_div #state').on('changed.bs.select', async function(e, clickedIndex, isSelected,
            previousValue) {
            e.preventDefault();
            $('#pre_state_id').val($(this).val());
            update_country($(this));
            const current_state_id = $(this).val()
            let pre_city_id = $('#locations_div #city').find(':selected').val();
            if (pre_city_id === null || pre_city_id == '') {
                pre_city_id = '';
            }
            const params = {
                'current_state_id': current_state_id,
            }
            await loadOptions(params, 'load_cities', updateOptions,
                '#locations_div #city', pre_city_id);


        });

        $('#locations_div #country').on('show.bs.select', function(e) {
            const $searchInput = $(this).closest('.bootstrap-select').find('.bs-searchbox input');
            // const auto_fill = $('#auto_fill').val();
            $searchInput.on('keyup', async function() {
                const name = $(this).val();
                const params = {
                    'name': name
                };
                await loadOptions(params, 'load_countries', updateOptions,
                    '#locations_div #country');
            });
        });



        $('#locations_div #country').on('changed.bs.select', async function(e, clickedIndex, isSelected,
            previousValue) {
            e.preventDefault();
            const current_country_id = $(this).find(':selected').val();
            const pre_country_id = $('#locations_div #state').find(':selected').attr('country_id');
            let pre_state_id = $('#locations_div #state').find(':selected').val();
            if (pre_state_id === null || pre_state_id == '') {
                pre_state_id = '';
            }

            if (current_country_id != pre_country_id) {
                const params = {
                    'current_country_id': current_country_id,
                }
                await loadOptions(params, 'load_states', updateOptions,
                    '#locations_div #state', pre_state_id);
            }
        });

        $('#nationality').on('show.bs.select', function(e) {
            const $searchInput = $(this).closest('.bootstrap-select').find('.bs-searchbox input');
            $searchInput.on('keyup', async function() {
                const name = $(this).val();
                const params = {
                    'name': name
                };
                await loadOptions(params, 'load_countries', updateOptions,
                    '#nationality');
            });
        });



        $('#locations_div .selectpicker').on('hidden.bs.select', function() {
            const $searchInput = $(this).closest('.bootstrap-select').find('.bs-searchbox input');
            $searchInput.off('keyup');
        });

    });



    async function update_country(this_) {
        const country_id = $(this_).find(':selected').attr('country_id');
        const country_name = $(this_).find(':selected').attr('country_name');
        const data = [{
            'id': country_id,
            'name': country_name,
        }];
        await updateOptions(data, '#country', country_id);
        $('#locations_div #country').selectpicker('refresh');
    }




    function showAlert(message, alert_type = 'success') {
        $('#modalMessage').html(message);
        $('#alertModal').modal('show');
        setTimeout(function() {
            $('#alertModal').modal('hide');
        }, 3000);
    }

    function save_students_personal_info() {
        preloader.load();
        $.ajax({
            url: "{{ route('save_personal_info') }}",
            type: 'POST',
            data: $('#form_personal_info').serialize(),
            success: function(response) {
                preloader.stop();
                showAlert(response.success);
                $('#alert_messge').addClass('d-none');
                $('#alert_messge').hide();
                $('#form_personal_info .form-control').removeClass('error-outline');
                $('.student_id').val(response.student_id);
                $(`#locations_div .bootstrap-select`).css('outline', 'none');
                $('#email_update').prop('checked', true);
                const step_value = parseInt($('#step_value').val());
                updateUrlStudentForm(step_value, response.student_id);
                $('#next_stepper').prop('disabled', false);
                updateProgress(response.prolfile_completed[0], response.prolfile_completed[1], 'danger');
                $('#email_update').prop('checked', true);
            },
            error: function(xhr) {
                preloader.stop();
                let errors = xhr.responseJSON.errors;
                // console.log(errors)
                $('#form_personal_info .form-control').removeClass('error-outline');
                $(`#locations_div .bootstrap-select`).css('outline', 'none');
                $('#alert_messge').html('');
                $('#alert_messge').addClass('d-none');
                let message = "";
                $.each(errors, function(key, value) {
                    $('#alert_messge').addClass('alert-info');
                    $('#alert_messge').removeClass('d-none');
                    $('#alert_messge').show();
                    if (key == 'country' || key == 'city' || key ==
                        'state') {
                        $(`#${key}_div .bootstrap-select`).css('outline',
                            '1px solid red');
                    } else {
                        $('#' + key).addClass('error-outline');
                    }
                    message = value[0] + " ";
                });
                $('#alert_messge').append(message);
            }
        });
    }
</script>
