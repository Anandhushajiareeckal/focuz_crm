<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">View Student Profile</h3>
            </div>
            <div class="card-body  table-responsive">
                <table id="view_students" style="font-size:10pt;" class="table table-bordered">
                    <tr class="table-primary">
                        <td colspan="4">Personal Detail</td>
                    </tr>
                    @php
                        $class_label = 'bg-light color-palette';
                    @endphp
                    <tr>
                        <td class="{{ $class_label }}">First Name</td>
                        <td>{{ $studentData->first_name }}</td>
                        <td class="{{ $class_label }}">Last Name</td>
                        <td>{{ $studentData->last_name }}</td>
                    </tr>
                    <tr>
                        <td class="{{ $class_label }}">Email</td>
                        <td><a href="mailto:{{ $studentData->email }}">{{ $studentData->email }}</a></td>
                        <td class="{{ $class_label }}">Phone</td>
                        <td><a href="tel:{{ $studentData->phone_number }}">{{ $studentData->phone_number }}</a></td>
                    </tr>
                    <tr>
                        <td class="{{ $class_label }}">Alternative Number</td>
                        <td>{{ $studentData->alternative_number }}</td>
                        <td class="{{ $class_label }}">Gender</td>
                        <td> {{ ucwords($studentData->gender) }}</td>
                    </tr>
                    <tr>
                        <td class="{{ $class_label }}">Father Name</td>
                        <td>{{ $studentData->fathers_name }}</td>
                        <td class="{{ $class_label }}">Mother Name</td>
                        <td>{{ $studentData->mothers_name }}</td>
                    </tr>

                    <tr>
                        <td class="{{ $class_label }}">Location</td>
                        <td>{{ $studentData->city->name }}, {{ $studentData->state->name }},
                            {{ $studentData->country->name }}</td>
                        <td class="{{ $class_label }}">Postal Code</td>
                        <td>{{ $studentData->postal_code }}</td>
                    </tr>
                    <tr>
                        <td class="{{ $class_label }}">Address</td>
                        <td> {{ $studentData->address }}</td>
                        <td class="{{ $class_label }}">Date of birth</td>
                        <td>{{ date('d-m-Y', strtotime($studentData->date_of_birth)) }}</td>
                    </tr>
                    <tr>
                        <td class="{{ $class_label }}">Religion</td>
                        <td> 
                            @if ($studentData->religion)
                                {{ $studentData->religion->religion_name }}
                            @endif
                            </td>
                        <td class="{{ $class_label }}">Religion Category</td>
                        <td>
                            @if ($studentData->religion_category)
                                {{ $studentData->religion_category->religion_category }}
                            @endif    
                       </td>
                    </tr>
                    <tr>
                        <td class="{{ $class_label }}">ID Card</td>
                        <td>
                            @if ($studentData->identity_card)
                                {{ $studentData->identity_card->name }}
                            @endif
                        </td>
                        <td class="{{ $class_label }}">ID Number</td>
                        <td> {{ $studentData->identity_card_no }}</td>
                    </tr>
                    <tr>
                        <td class="{{ $class_label }}">Employment status</td>
                        <td>
                            @if ($studentData->employment_status)
                                {{ $studentData->employment_status->status_name }}
                            @endif
                        </td>
                        <td class="{{ $class_label }}">Marrital status</td>
                        <td>
                            @if ($studentData->marital_status)
                                {{ $studentData->marital_status->marital_status }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="{{ $class_label }}">Emergency Contact Person</td>
                        <td> {{ $studentData->emergency_contact_name }}</td>
                        <td class="{{ $class_label }}">Emergency Contact Number</td>
                        <td> {{ $studentData->emergency_contact_phone }}</td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
</div>
