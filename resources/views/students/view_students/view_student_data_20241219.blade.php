<table id="view_students" style="font-size:10pt;" class="table table-bordered table-striped dataTable dtr-inline">
    <thead>
        <tr>
            <th>Actions</th>
            <th>Name</th>
            <th>Track Id</th>
            <th>Email</th>
            <th>Phone</th>

            <th>University</th>
            <th>Course</th>
            <th>Course Fee</th>
            <th>Pending Amount</th>
            <th>Payed Amount</th>
            <th>Payment Status</th>
            
            <th>Profile Status</th>
            <th>Next Payment Date</th>
        </tr>
    </thead>
    <tbody id="tbody_view_students">


        @foreach ($students_data as $student)
            @php
                $pending_amount = '';
                $payed_amount = '';
                $payment_status = '';
                $next_payment_date = '';
                $class_payment_date = '';
                $class = '';
                $course_fee = '';
                $student_track_id = '';
                $course_stream_name = '';
                $course_specialization = '';
                $university_current = '';
                $payments = App\Models\CoursePayments::where('student_id', $student->id)
                    ->latest()
                    ->first([
                        'payment_status',
                        'amount',
                        'discount',
                        'course_id',
                        'course_schedule_id',
                        'next_payment_date',
                        'student_track_id',
                    ]);

                if ($payments) {
                    $course_fee = App\Models\CourseSchedules::where('id', $payments->course_schedule_id)
                        ->where('status', 'active')
                        ->value('course_fee');
                    $course_selected = App\Models\Courses::with(['university:id,name','streams:id,code'])
                        ->where('id', $payments->course_id)
                        ->first();
                    $course_stream_name = $course_selected->code;
                    $university_current = $course_selected->university->name;
                    $course_specialization = $course_selected->specialization;
                    $course_nameID = $course_selected->course_name_id;

                    $student_track_id = $payments->student_track_id;
                    $payed_amount = $payments->amount + $payments->discount;
                    $pending_amount = number_format($course_fee - $payed_amount, 2);
                    $payed_amount = number_format($payed_amount, 2);
                    $payment_status = $payments->payment_status;
                    if ($payments->next_payment_date == null) {
                        $next_payment_date = '';
                        $class_payment_date = 'text-danger';
                    } else {
                        $next_payment_date = date('d-m-Y', strtotime($payments->next_payment_date));
                        if ($payments->next_payment_date <= date('Y-m-d')) {
                            $class_payment_date = 'text-danger';
                        } else {
                            $class_payment_date = 'text-success';
                        }
                    }

                    if ($payment_status == 'pending') {
                        $class = 'text-danger';
                    } else {
                        $class = 'text-success';
                    }
                }
            @endphp

            <tr>
                <td>
                    <button type="button" class="btn btn-sm btn-dark" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="dropdown-menu" style="cursor:pointer">
                        <li class="dropdown-item">
                            <a target="_blank" href="{{ route('view_profile', [1, $student->id]) }}">View
                                Profile</a>

                        </li>
                        <li class="dropdown-item">
                            <a target="_blank" target="blank" href="{{ route('add_students', [1, $student->id]) }}">Edit
                                Profile</a>
                        </li>

                    </ul>
                </td>

                <td class="{{ $class }}">
                    <a target="_blank" data-toggle="popover" title="View Profile"
                        href="{{ route('view_profile', [1, $student->id]) }}">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </a>
                </td>
                <td>
                    <a target="_blank" target="blank" data-toggle="popover" title="Edit Profile"
                        href="{{ route('add_students', [1, $student->id]) }}">{{ $student_track_id }}</a>
                </td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->phone_number }}</td>

                <td>{{ $university_current }}</td>
                <td>{{ $course_stream_name }} {{ $course_specialization }}</td>
                <td>{{ $course_fee }}</td>
                <td>{{ $pending_amount }}</td>
                <td>{{ $payed_amount }}</td>

                <td class="{{ $class }}"> {{ ucwords($payment_status) }}</td>
                <td> {{ $student->profile_completion * 25 }}%</td>
                <td class="{{ $class_payment_date }}"> {{ $next_payment_date }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
