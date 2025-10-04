<?php

namespace App\Http\Controllers;

use App\Models\CourseInstallments;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseInstallmentsController extends Controller
{

    public function courseInstallments($installment_id = '', $message = '')
    {

        $course_installment_data = CourseInstallments::select(['id', 'start_date', 'course_schedule_id', 'installment_amount', 'number_of_installments', 'completed_installments', 'next_reminder_date', 'end_date', 'student_id', 'course_id'])
            ->with([
                'student:id,first_name,last_name,email',
                'course:id,university_id,stream_id,specialization',
                'course_schedule:id,course_fee,other_fees'
            ])->where('status', 'active');
        if ($installment_id != '') {
            $course_installment_data = $course_installment_data->where('id', $installment_id);
        }
        $course_installment_data = $course_installment_data->get();
        // dd(DB::getQueryLog());
        return view(
            'payments.course_installments',
            [
                'installment_id' => $installment_id,
                'message' => $message,
                'course_installment_data' => $course_installment_data
            ]
        );
    }

    public function manageCourseInstallment(Request $request)
    {
        $installment_id = $request->input('installemt_id');
        if ($installment_id) {
            $course_installment = CourseInstallments::where('id', $installment_id)->first();
            $student_data = Students::where('id', $course_installment->student_id)->first();
            $name = $student_data->first_name;
            if ($student_data->last_name != '') {
                $name .= ' ' . $student_data->last_name;
            }
            $student_label = $name . ', ' . $student_data->email;
        } else {
            $course_installment = collect();
            $student_data = collect();
        }



        // $return_data = [
        //     'studentID' => $student->id,
        //     'course_id'  => $course_installment->course_schedules . '__' . $course_installment->courses_id,
        //     'label' => $name . ', ' . $student->email,
        //     'start_date' => date('d-m-Y', strtotime($course_installment->start_date)),
        //     'end_date' => date('d-m-Y', strtotime($course_installment->end_date)),
        //     'reminder_date' => $course_installment->installment_amount,
        //     'next_reminder_days' => $course_installment->next_reminder_days,
        //     'due_days' => $course_installment->due_days,
        //     'number_of_installments' => $course_installment->number_of_installments,
        // ];

        return view(
            'payments.partials.manage_installments',
            [
                'installment' => $course_installment,
                'student_label' => $student_label,
                'student_data' => $student_data
            ]
        );
    }

    public function saveInstallment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'course' => 'required|exists:course_schedules,id',  // Assuming the course is related to a "courses" table
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'installment_amount' => 'required|numeric|min:0',
            'number_of_installments' => 'required|integer|min:1',
            'reminder_date' => 'required|date|after:today',
            'next_reminder_days' => 'required|string|min:1',
            'number_of_installments' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $course_split = explode("__", $request->input('course'));
        if (count($course_split) < 2) {
            return response()->json([
                'error' => 'Something went Wrong, Please refresh and try again',
            ], 422);
        }
        $course_schedule_id = $course_split[0];
        $course_id = $course_split[1];
        $installment = CourseInstallments::where('student_id', $request->input('student_id'))
            ->where('course_schedule_id', $course_schedule_id)
            ->where('status', 'active')
            ->first();
        if ($installment) {
            if ($request->input('exist_id')  === null) {
                return response()->json([
                    'error' => 'This installment already exists for the student in this period. Check the box below to override',
                    'exist_id' => $installment->id
                ], 422);
            }
            $return_meesage = 'Installment successfully updated!';
        } else {
            $installment = new CourseInstallments();
            $return_meesage = 'Installment successfully created!';
        }

        $next_reminder_date =  date('Y-m-d', strtotime($request->input('reminder_date')));
        if ($request->input('due_days')) {
            $due_days = $request->input('due_days');
            $due_date = date('Y-m-d', strtotime('+' . $due_days . ' days', strtotime($next_reminder_date)));
        } else {
            $due_days = 0;
            $due_date = $next_reminder_date;
        }
        $installment->student_id = $request->student_id;
        $installment->course_id = $course_id;
        $installment->course_schedule_id = $course_schedule_id;
        $installment->start_date = date('Y-m-d', strtotime($request->input('start_date')));
        $installment->end_date = date('Y-m-d', strtotime($request->input('end_date')));
        $installment->paid_amount = 0;
        $installment->next_reminder_date = $next_reminder_date;
        $installment->due_date = $due_date;
        $installment->next_reminder_days = $request->input('next_reminder_days');
        $installment->due_days = $due_days;
        $installment->installment_amount = $request->input('installment_amount');
        $installment->number_of_installments = $request->input('number_of_installments');
        $installment->created_by = Auth::id();
        $installment->save();

        return response()->json(['message' => $return_meesage, 'exist_id' => $installment->id], 200);
    }
}
