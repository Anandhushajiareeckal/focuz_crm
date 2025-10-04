@php

@endphp
<form id="filter_form" action="{{ route('view_students') }}" method="post">
    @csrf
    <div class="row mb-2">
        <div class="col">
            <button id="clear_filter" class="btn btn-sm btn-dark" type="button">
                <i class="fa fa-eraser"></i>
                Clear Filters
            </button>
        </div>
    </div>
    <div class="row">
        <!-- Column 1 -->
        <div class="col-md-4 col-12  col-sm-6">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control form-control-sm filter_inputs" id="name" name="name"
                    placeholder="Enter name" value="{{ $name }}">
            </div>
        </div>
        <div class="col-md-4 col-12  col-sm-6">
            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control form-control-sm" id="gender" name="gender">
                    <option value="">Select...</option>
                    @foreach ($genderAr as $genderKey => $genderText)
                        @if ($gender == $genderKey)
                            <option value="{{ $genderKey }}" selected>{{ $genderText }}</option>
                        @else
                            <option value="{{ $genderKey }}">{{ $genderText }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4 col-12  col-sm-6">
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" class="form-control form-control-sm filter_inputs" id="phone_number"
                    name="phone_number" placeholder="Enter phone number" value="{{ $phone_number }}">
            </div>
        </div>
        <div class="col-md-4 col-12  col-sm-6">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control form-control-sm filter_inputs" id="email" name="email"
                    placeholder="Enter email" value="{{ $email }}">
            </div>
        </div>

        <div class="col-md-4 col-12 col-sm-6" id="country_div">
            <div class="form-group">
                <label for="country">Country</label>
                <select class="form-control form-control-sm filter_inputs selectpicker_country selectpicker"
                    data-size="10" id="country" name="country" data-live-search="true" style="width: 100%;">
                    <option value="">Select country</option>
                    @foreach ($countriesAr as $country_data)
                        @if ($country_data->id == $country)
                            <option value="{{ $country_data->id }}" selected>{{ $country_data->name }}</option>
                        @else
                            <option value="{{ $country_data->id }}">{{ $country_data->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4 col-12  col-sm-6" id="state_div">
            <div class="form-group">
                <label for="state">State</label>
                <select class="form-control form-control-sm filter_inputs selectpicker" data-size="10" id="state"
                    name="state" data-live-search="true" style="width: 100%;">
                    <option value="">Select State</option>
                    @if ($states !== null && !empty($states))
                        @foreach ($states as $state_data)
                            @if ($state_data->id == $state)
                                <option value="{{ $state_data->id }}" selected>{{ $state_data->name }}</option>
                            @else
                                <option value="{{ $state_data->id }}">{{ $state_data->name }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-4 col-12  col-sm-6" id="city_div">
            <div class="form-group">

                <div class="form-group">
                    <label for="state">City</label>
                    <select class="form-control form-control-sm filter_inputs selectpicker" data-actions-box="true"
                        multiple data-size="10" id="city" name="city[]" data-live-search="true"
                        style="width: 100%;">
                        <option value="">Select city</option>
                        @foreach ($cities as $city_data)
                            @if (in_array($city_data->id, $city))
                                <option value="{{ $city_data->id }}" selected>{{ $city_data->name }}</option>
                            @else
                                <option value="{{ $city_data->id }}">{{ $city_data->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
      

        <div class="col-md-4 col-12  col-sm-6">
            <div class="form-group">

                <div class="form-group">
                    <label for="course">Course</label>
                    <select class="form-control form-control-sm filter_inputs selectpicker" data-actions-box="true"
                        multiple data-size="10" id="course" name="course[]" data-live-search="true"
                        style="width: 100%;">
                        @foreach ($coursesAr as $course_data)
                            @if (in_array($course_data->id, $course))
                                <option value="{{ $course_data->id }}" selected>{{ $course_data->name }}</option>
                            @else
                                <option value="{{ $course_data->id }}">{{ $course_data->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <div class="col-md-4 col-12  col-sm-6">
            <div class="form-group">
                <label for="pending_payments">Pending Payments</label>
                <select class="form-control form-control-sm filter_inputs" id="pending_payments"
                    name="pending_payments">
                    <option value="">Select...</option>
                    @foreach ($paymentStatusAr as $key => $paymentStatus)
                        @if ($pending_payments == $key)
                            <option value="{{ $key }}" selected>
                                {{ $paymentStatus }}
                            </option>
                        @else
                            <option value="{{ $key }}">
                                {{ $paymentStatus }}
                            </option>
                        @endif
                    @endforeach

                </select>
            </div>
        </div>




        <div class="col-md-4 col-12  col-sm-6">
            <div class="form-group">
                <label for="pending_profile_completion">Pending Profile Completion</label>
                <select class="form-control form-control-sm filter_inputs" id="pending_profile_completion"
                    name="pending_profile_completion">
                    <option value="">Select...</option>

                    @foreach ($profile_completionAr as $key => $value)
                        @if ($pending_profile_completion == $key)
                            <option value="{{ $key }}" selected>{{ $value }}</option>
                        @else
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-md-12">
            <button type="button" class="btn btn-dark btn-sm" onclick="filter_form()">Filter</button>
        </div>
        
    </div>
</form>
