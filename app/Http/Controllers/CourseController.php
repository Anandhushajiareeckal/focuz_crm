<?php

namespace App\Http\Controllers;

use App\Models\CoursePayments;
use App\Models\CourseSchedules;
use App\Models\Courses;
use App\Models\Employees;
use App\Models\Payments;
use App\Models\Specializations;
use App\Models\Streams;
use App\Models\Universities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{

    public function load_courses(Request $request)
    {
        $student_id = $request->input('student_id');
        $courses_selected = CoursePayments::where('student_id', $student_id)->where('status', 'active')->pluck('course_id');
        $courses_unselected = DB::table('courses')
            ->join('universities', 'courses.university_id', '=', 'universities.id')
            ->join('streams', 'courses.stream_id', '=', 'streams.id')
            ->whereNotIn('courses.id', $courses_selected)
            ->where('courses.status', 'active')
            ->select(
                'courses.id',
                DB::raw("CONCAT(streams.code, ' ', courses.specialization, ', ', universities.name, ' (', universities.university_code,')') AS name")
            )
            ->get();

        $courses_selected = DB::table('courses')
            ->join('universities', 'courses.university_id', '=', 'universities.id')
            ->join('streams', 'courses.stream_id', '=', 'streams.id')
            ->whereIn('courses.id', $courses_selected)
            ->select(
                'courses.id',
                DB::raw("CONCAT(streams.code, ' ', courses.specialization, ', ', universities.name , ' (', universities.university_code,')') AS name")
            )
            ->get();


        $returnData = [
            "courses_unselected" =>  $courses_unselected,
            "courses_selected" => $courses_selected
        ];
        return response()->json($returnData);
    }

    public function loadCoursesAPI(Request $request)
    {
        $searchTerm = $request->input('q');
        if ($searchTerm != '') {
            $courses = DB::table('courses')
                ->join('universities', 'courses.university_id', '=', 'universities.id')
                ->join('streams', 'courses.stream_id', '=', 'streams.id')
                ->where('courses.status', 'active')
                ->where(function ($query) use ($searchTerm) {
                    $query->where('streams.name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('universities.name', 'LIKE', '%' . $searchTerm . '%');
                })
                ->limit(100)
                ->select(
                    DB::raw("CONCAT(streams.name, '__', universities.name) AS name"),

                )
                ->get();
            $courseNames = $courses->pluck('name');
        } else {
            $courses = [];
        }
        return response()->json([
            'status' => 'success',
            'data' => $courseNames
        ]);
    }

    public function load_courses_period(Request $request)
    {
        $course_id = $request->input('course_id');
        $student_id = $request->input('student_id');
        $courses_selected = CoursePayments::where('student_id', $student_id)
            ->where('course_id', $course_id)
            ->pluck('course_schedule_id')->toArray();

        if (count($courses_selected) > 0) {
            $selected_course_id = $courses_selected[0];
        } else {
            $selected_course_id = '';
        }
        $courses = CourseSchedules::select(DB::raw("CONCAT(start_date, ' - ', end_date, ' - ',  course_fee, ' (Enrolled)') as name, id"))
            ->whereIn('id', $courses_selected)
            ->get();

        $courses_not_selected = CourseSchedules::select(DB::raw("CONCAT(start_date, ' - ', end_date, ' - ',  course_fee) as name, id"))
            ->where('course_id', $course_id)
            ->whereNotIn('id', $courses_selected)
            ->where('status', 'active')
            ->get();
        $merged_courses = $courses->merge($courses_not_selected);
        $return_data = [
            'selected_course_id' => $selected_course_id,
            'merged_courses' => $merged_courses
        ];
        return response()->json($return_data);
    }

    public function loadCoursePayments(Request $request)
    {
        $student_id = $request->input('student_id');
        $course_id = $request->input('course_id');
        $course_schedule_id = $request->input('course_schedule_id');
        $course_payment = CoursePayments::join('branches', 'course_payments.branch_id', '=', 'branches.id')
            ->where('course_payments.course_schedule_id', $course_schedule_id)
            ->where('course_payments.student_id', $student_id)
            ->where('course_payments.status', 'active')
            ->select(
                'course_payments.amount',
                'course_payments.created_by',
                'course_payments.university_loginid',
                'course_payments.university_loginpass',
                'course_payments.admission_date',
                'course_payments.status',
                'course_payments.discount',
                'course_payments.payment_id',
                'course_payments.student_track_id',
                'branches.code as branch_code', // Selecting the branch code,
                'branches.id as branch_id' // Selecting the branch code
            )
            ->first();
        if (!$course_payment) {
            return "failed";
        }
        $course_fee = CourseSchedules::where('course_id', $course_id)
            ->where('status', 'active')
            ->value('course_fee');

        $employee = Employees::where('id', $course_payment->created_by)
            ->first(['id', 'first_name', 'last_name', 'email']);
        $full_emp_name = $employee->first_name;
        if ($employee->last_name) {
            $full_emp_name .= ' ' . $employee->last_name;
        }
        $full_emp_name .= ', ' . $employee->email;

        $amount = $course_payment->amount;
        $student_track_id = $course_payment->student_track_id;
        if ($course_payment->discount && $course_payment->discount  !== null && $course_payment->discount != 0) {
            $amount += $course_payment->discount;
        }

        $branch_code = $course_payment->branch_id . '__' . $course_payment->branch_code;
        $balance_amount = number_format($course_fee - $amount, 2);
        $return_data = [
            'balance_amount' => $balance_amount,
            'amount_formatted' => number_format($amount, 2),
            'amount' => $amount,
            'student_track_id' => $student_track_id,
            'payment_id' => $course_payment->payment_id,
            'univ_login_id' => $course_payment->university_loginid,
            'univ_login_pass' => $course_payment->university_loginpass,
            'univ_login_pass' => $course_payment->university_loginpass,
            'stud_status' => $course_payment->status,
            'emp_name' => $full_emp_name,
            'emp_id' => $employee->id,
            'univ_login_pass' => $course_payment->university_loginpass,
            'branch_code' => $branch_code,


            'admission_date' => date('d-m-Y', strtotime($course_payment->admission_date))
        ];
        if ($request->input('payment_id_update')) {
            $payment_data = Payments::where('id', $request->input('payment_id_update'))->first();
            $return_data['next_pay_date'] = date('d-m-Y', strtotime($payment_data->next_payment_date));
            $return_data['transaction_date'] = date('d-m-Y', strtotime($payment_data->payment_date));
            $return_data['transaction_notes'] = $payment_data->transaction_ref;
            $return_data['amount'] = $payment_data->amount;
            $return_data['payment_method'] = $payment_data->payment_method_id;
            $return_data['promo_code'] = $payment_data->promocode;
            $return_data['terminal'] = $payment_data->terminal_id;
            $return_data['card_type'] = $payment_data->card_type_id;
            $return_data['bank'] = $payment_data->bank_id;
        }



        return response()->json($return_data);
    }

    public function ViewCourses(Request $request, $course_id = null)
    {


        $universities = Universities::where('status', 'active')->get();
        $course_schedule_data = CourseSchedules::with([
            'course:id,specialization,stream_id,university_id',
            'course.university:id,name',
            'course.streams:id,code'
        ])->where('status', 'active');
        $university_ids = [];
        if ($course_id) {
            $course_schedule_data->where('course_id', $course_id);
        } else if ($request->input('universities_filter')) {
            $university_ids = $request->input('universities_filter');

            $course_schedule_data = $course_schedule_data->whereHas('course.university', function ($query) use ($university_ids) {
                $query->whereIn('university_id', $university_ids);
            });
        }

        $course_schedule_data = $course_schedule_data->paginate(500);

        return view('courses.view_courses', [
            'course_schedule_data' => $course_schedule_data,
            'universities_filters' => $universities,
            'university_ids' => $university_ids
        ]);
    }

    public function ManageCourses(Request $request)
    {
        if ($request->input('edit_id') !== null) {
            $course_data = CourseSchedules::where("id", $request->input('edit_id'))->first();
        } else {
            $course_data = new CourseSchedules();
        }
        return view(
            'courses.partials.manage_course',
            [
                "course_data" => $course_data,

            ]
        );
    }

    public function LoadStreams(Request $request)
    {
        if ($request->input('save_as_new')) {
            return 1;
        }

        $streams = Streams::select(
            'id',
            DB::raw("CONCAT(code, ' - ', name) AS name")
        )->get();
        return response()->json($streams);
    }

    public function LoadSpecialization(Request $request)
    {

        $university_id = $request->input('university_id');

        $specializations = Specializations::select(
            'id',
            'name'
        )->where('university_id', $university_id)->get();
        return response()->json($specializations);
    }

    public function SaveSpecialization(Request $request)
    {
        $university_id = $request->input('university_id');
        $specialization_name = $request->input('name');
        $existingSpecialization = Specializations::where('university_id', $university_id)
            ->where('name', $specialization_name)
            ->first();

        // If it doesn't exist, create a new specialization
        if (!$existingSpecialization) {
            $specialization = new Specializations();
            $specialization->university_id = $university_id;
            $specialization->name = $specialization_name;
            $specialization->save();

            return response()->json(['message' => 'Specialization created successfully!'], 200);
        } else {
            return response()->json(['message' => 'Specialization already exists.'], 200);
        }
    }

    public function GetCourseScheduleForm(Request $request)
    {

        $rules = [
            'university' => 'required|string|max:255',
            'stream' => 'required|string|max:255',
            'specialization' => 'required',
            'status_load' => 'required|in:active,inactive,all|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        $university = $request->input('university');
        $stream_id = $request->input('stream');
        $specialization = $request->input('specialization');
        $status_load = $request->input('status_load');
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $specializationNames = Specializations::whereIn('id', $specialization)
            ->pluck('name')->toArray();
        sort($specializationNames);
        $specialization_cond = implode(", ", $specializationNames);

        $course_id_query = Courses::where('specialization', $specialization_cond)
            ->where('stream_id', $stream_id)
            ->where('university_id', $university)
            ->first();

        if ($course_id_query) {
            $course_id = $course_id_query->id;
            $courseSchedules = CourseSchedules::where('course_id', $course_id);
            if ($status_load != 'all') {
                $courseSchedules = $courseSchedules->where('status', $status_load);
            }
            $courseSchedules = $courseSchedules->get();
        } else {
            $course_id = "";
            $courseSchedules = collect();
        }

        return view('courses.partials.course_schedule_form', [
            'courseSchedules' => $courseSchedules,
            'course_id' => $course_id
        ]);
    }

    public function saveCourseData(Request $request)
    {
        $rules = [
            'university' => 'required|string|max:255',
            'stream' => 'required|string|max:255',
            'specialization' => 'required',
            'course_fee.*' => 'required|numeric|min:0',
            'commission.*' => 'nullable|numeric|min:0',
            'other_fees.*' => 'nullable|numeric|min:0',
            'start_date.*' => 'required|date',
            'end_date.*' => 'required|date|after_or_equal:start_date.*',
            'status.*' => 'required|string|in:active,inactive|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);



        $uniqueCombinations = [];
        foreach ($request->course_fee as $index => $course_fee) {
            $combination = [
                'course_fee' => $course_fee,
                'start_date' => date('Y-m-d', strtotime($request->start_date[$index])),
                'end_date' => date('Y-m-d', strtotime($request->end_date[$index])),
            ];

            $hash = md5(json_encode($combination));
            if (isset($uniqueCombinations[$hash])) {
                $validator->after(function ($validator) use ($index) {

                    $validator->errors()->add("row_$index", "Duplicate data found in row " . ($index + 1));
                });
            } else {
                $uniqueCombinations[$hash] = true;
            }
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $university = $request->input('university');
        $stream = $request->input('stream');
        $specialization = $request->input('specialization');

        $specializationNames = Specializations::whereIn('id', $specialization)
            ->pluck('name')->toArray();
        sort($specializationNames);
        $specialization_names = implode(", ", $specializationNames);

        $course_data = Courses::where('university_id', $university)
            ->where('stream_id', $stream)
            ->where('specialization', $specialization_names)->first();
        if (!$course_data) {
            $course_data = new Courses();
            $course_data->specialization = $specialization_names;
            $course_data->status = 'active';
            $course_data->university_id = $university;
            $course_data->stream_id = $stream;
            $course_data->department_id = 4;
            $course_data->save();
        }
        $course_id = $course_data->id;

        $updatedCourseIds = [];
        foreach ($request->course_schedule_id as $index => $course_schedule_id) {
            $start_date = date('Y-m-d', strtotime($request->start_date[$index]));
            $end_date = date('Y-m-d', strtotime($request->end_date[$index]));

            if ($course_schedule_id) {
                $courseSchedule = CourseSchedules::where('course_id', $course_id)
                    ->where('id', $course_schedule_id)
                    ->where('start_date', $start_date)
                    ->where('end_date', $end_date)
                    ->first();
                if (!$courseSchedule) {
                    $courseSchedule = new CourseSchedules();
                }
            } else {
                $courseSchedule = new CourseSchedules();
            }

            $courseSchedule->course_id = $course_id;
            $courseSchedule->course_fee = $request->course_fee[$index];
            $courseSchedule->other_fees = ($request->other_fees[$index] ? $request->other_fees[$index] : 0);
            $courseSchedule->commission = ($request->commission[$index] ? $request->commission[$index] : 0);
            $courseSchedule->start_date = $start_date;
            $courseSchedule->end_date = $end_date;
            $courseSchedule->start_date = $start_date;
            $courseSchedule->status = $request->status[$index];
            $courseSchedule->save();
            $course_schedule_id = $courseSchedule->id;
            $updatedCourseIds[] = ['course_schedule_id' => $course_schedule_id];
        }
        $courses_active_count = CourseSchedules::where('course_id', $course_id)
            ->where('status', 'active')->count();
        $course = Courses::find($course_id);
        if ($courses_active_count == 0) {
            $course->status = 'inactive';
        } else {
            $course->status = 'active';
        }
        $course->save();
        $return_data = ['course_id' => $course_id, 'updatedCourseIds' => $updatedCourseIds];
        return $return_data;
    }

    public function removeCourseSchedule(Request $request)
    {
        $scheduleId = $request->get('schedule_id');
        $course_id = $request->get('course_id');
        $exist = CoursePayments::where('course_id', $course_id)
            ->where('course_schedule_id', $scheduleId)->exists();
        $existpaments = Payments::where('course_id', $course_id)
            ->where('course_schedule_id', $scheduleId)->exists();
        if ($exist || $existpaments) {
            $returnData = [
                "message" =>  "It is linked to associated records and cannot be removed",
            ];
            $status = 422;
        } else {
            $returnData = [
                "message" =>  "Successfully removed",
            ];
            $status = 200;

            CourseSchedules::where('course_id', $course_id)
                ->where('id', $scheduleId)
                ->delete();

            $schedule_exist = CourseSchedules::where('course_id', $course_id)->exists();

            if (!$schedule_exist) {
                Courses::where('id', $course_id)->delete();
            }
        }
        return response()->json($returnData, $status = $status);
    }

    public function getSpecializationKeys(Request $request)
    {
        $specializations = explode(", ", $request->input('specialization'));
        $university_id = explode(", ", $request->input('u_id'));

        $specializationids = Specializations::whereIn('name', $specializations)
            ->where('university_id', $university_id)
            ->pluck('id')->toArray();
        $string_ids = array_map(function ($id) {
            return (string) $id; // Adds quotes around each ID
        }, $specializationids);
        return $string_ids;
    }

    public function loadStudentCourses(Request $request)
    {
        $student_id = $request->input('studentId');


        if (!$student_id) {
            return response()->json(['error' => 'Something Went Wrong, Please contact IT support'], 422);
        }


        $courses_selected_ids = CoursePayments::where('student_id', $student_id)
            ->pluck('course_schedule_id')
            ->toArray();
        if (empty($courses_selected_ids)) {
            return response()->json(['error' => 'No courses have been selected by the student'], 422);
        }
        $courses_selected = DB::table('course_schedules')
            ->join('courses', 'courses.id', '=', 'course_schedules.course_id')
            ->join('universities', 'courses.university_id', '=', 'universities.id')
            ->join('streams', 'courses.stream_id', '=', 'streams.id')
            ->whereIn('course_schedules.id', $courses_selected_ids) // Ensure $courses_selected_ids is an array
            ->select(
                DB::raw("CONCAT(course_schedules.id, '__', courses.id) AS id"),
                DB::raw("CONCAT(streams.code, ' ', courses.specialization, ', ', universities.university_code, ' (', course_schedules.start_date, ' to ', course_schedules.end_date, ' -- ', COALESCE(course_schedules.course_fee, 0) + COALESCE(course_schedules.other_fees, 0), ')') AS name")
            )
            ->get();
        if (count($courses_selected) == 1) {
            $selected_id = $courses_selected[0]->id;
        } else {
            $selected_id = '';
        }
        $return_data = [
            'selected_id' => $selected_id,
            'data' =>  $courses_selected
        ];
        return $return_data;
    }
}
