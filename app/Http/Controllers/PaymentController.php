<?php

namespace App\Http\Controllers;

use App\Models\Banks;
use App\Models\Branches;
use App\Models\CardTypes;
use App\Models\CourseInstallments;
use App\Models\CoursePayments;
use App\Models\Courses;
use App\Models\CourseSchedules;
use App\Models\Discounts;
use App\Models\Employees;
use App\Models\InstallmentHistory;
use App\Models\PaymentMethods;
use App\Models\Payments;
use App\Models\StudentsTracknos;
use App\Models\Universities;
use App\Http\Controllers\OfferLetterController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use NumberFormatter;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function load_payment_method()
    {
        // $name = $request->input('name');

        $payment_methods = PaymentMethods::select('id', 'method_name AS name')->get();

        return response()->json($payment_methods);
    }

    public function load_promo_codes()
    {
        // $name = $request->input('name');

        $discounts = Discounts::select(DB::raw("CONCAT(promocode, ' - ', description) as name,id"))->get();

        return response()->json($discounts);
    }

    public function load_banks()
    {
        // $name = $request->input('name');

        $banks = Banks::select("id", "bank_name AS name")->get();

        return response()->json($banks);
    }

    public function load_card_types()
    {
        // $name = $request->input('name');

        $card_types = CardTypes::select("id", "type_name AS name")->get();

        return response()->json($card_types);
    }

    public function savePaymentInfo(Request $request)
    {
        $promoCode = $request->input('promo_code');
        $payment_id = $request->input('payment_id');
        $course = Courses::with('university')->find($request->input('course'));
        if ($course && $course->university->university_code) {
            $university_code  = $course->university->university_code;
            $university_id  = $course->university->id;
            $university_code_rule = 'nullable|string|max:255';
        } else {
            $university_id = "";
            $university_code = "NA";
            $university_code_rule = 'required|string|max:255';
        }
        $discount_amount = $request->input('discount_amount', 0);

        if ($discount_amount == 0) {
            $promo_code_rule = 'nullable|string|max:255';
            $promoCode = 1;
            $discount_amount = 0;
        } else {
            $discount_amount = floatval($discount_amount);
            $promo_code_rule = 'required|string|max:255';
        }
        $is_installment = $request->input('is_installment');
        if ($is_installment == 'installment') {
            $installment_id_valid = 'required';
        } else {
            $installment_id_valid = 'nullable';
        }
        $rules = [
            'student_id' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'course_period' => 'required|string|max:255',
            'bank' => 'nullable|string|max:255',
            'card_type' => 'nullable|string|max:255',
            'payment_method' => 'required|string|max:255',
            'branch_code' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
            'promo_code' => $promo_code_rule,
            'discount_amount' => 'nullable|string|max:255',
            'transaction_date' => 'nullable|string|max:255',
            'trans_ref' => 'nullable|string|max:255',
            'next_pay_date' => 'required|string|max:255',
            'admission_date' =>  'required|string|max:255',
            'created_by' =>  'required|string|max:255',
            'stud_status' =>  'required|string|max:255',
            'is_installment' =>  'required|string|in:installment,normal|max:255',
            'installment_id' =>  $installment_id_valid . '|string',
            'university_code' => $university_code_rule,
        ];
        if ($request->input('update_common_details')) {
            $rules = [
                'student_id' => 'required|string|max:255',
                'branch_code' => 'required|string|max:255',
                'admission_date' =>  'required|string|max:255',
                'created_by' =>  'required|string|max:255',
                'course' => 'required|string|max:255',
                'course_period' => 'required|string|max:255',
                'next_pay_date' => 'nullable|string|max:255',
                'stud_status' =>  'required|string|max:255',
                'university_code' => 'nullable|string|max:255'
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $branch_codeAr = explode("__", $request->input('branch_code'));
        $branch_id = $branch_codeAr[0];
        $branch_code = $branch_codeAr[1];
        $course_id = $request->input('course');
        $student_track_id_exist = $this->verifyStudentTrackSeries($course_id, $branch_id);

        $student_track_id__status = $student_track_id_exist['status'];
        if ($student_track_id__status == false) {
            $errorView = $student_track_id_exist['errorView'];
            return response()->json(['errors' => $errorView, 'error_modal' => true], 422);
        }
        $course_fee = CourseSchedules::where('id', $request->input('course_period'))
            ->where('status', 'active')
            ->value('course_fee');
        $student_id =  $request->input('student_id');
        $amount = floatval($request->input('amount'));
        $current_amount_tot = $amount + $discount_amount;
        $current_amount_tot_copy = $current_amount_tot;
        $course_payment = CoursePayments::where('student_id', $student_id)
            ->where('course_schedule_id', $request->input('course_period'))
            ->where('course_id', $course_id)
            ->first();
        if ($course_payment) {
            $currentAmount = $course_payment->amount;;
            $currentDiscountAmount =  $course_payment->discount;
            $paid_amount_course = $currentAmount + $currentDiscountAmount;
            $current_amount_tot += ($currentDiscountAmount + $currentAmount);
            $payment_status_db =  $course_payment->payment_status;
        } else {
            $currentAmount = 0;
            $currentDiscountAmount = 0;
            $paid_amount_course = 0;
            $payment_status_db = '';
        };
        $course_installment_data = CourseInstallments::where('student_id', $student_id)
            ->where('course_id', $course_id)
            ->where('course_schedule_id', $request->input('course_period'))
            ->first();
        if ($is_installment == 'installment') {

            if (!$course_installment_data) {
                $message = '<i class="fa fa-check-circle text-info"></i>&nbsp;&nbsp;You have chosen the installment option, but no installment exists.';
                return response()->json(['error' => $message, 'error_modal' => true], 422);
            }
        }
        if (!$request->input('update_common_details')) {
            if ($payment_status_db == 'completed') {
                $message = '<i class="fa fa-check-circle text-info"></i>&nbsp;&nbsp;The course payment has already been completed.';
                return response()->json(['message' => $message, 'status' => 'no_invoice']);
            } else if ($current_amount_tot > $course_fee) {
                $message = '<i class="fa fa-exclamation-triangle text-warning"></i>&nbsp;&nbsp;The entered amount is greater than the course fee.
                <br />';
                // if ($request->input()) {
                //     $message .= 'To reverse the last transaction, click the available option.';
                // }
                $message .= '<br />
                Maximum amount you can enter is <strong>' . number_format($course_fee - $paid_amount_course, 2) . "</strong>";

                return response()->json(['message' => $message, 'status' => '']);
            }
        }
        $logged_userID = Auth::id();
        $admission_date = date('Y-m-d', strtotime($request->input('admission_date')));
        if ($course_payment === null) {
            $course_payment = new CoursePayments();
            $last_id = StudentsTracknos::where('branch_id', $branch_id)
                ->where('university_id', $university_id)
                ->value('next_number');

            StudentsTracknos::where('branch_id', $branch_id)
                ->where('university_id', $university_id)
                ->increment('next_number');
            $student_track_id = "FCZ/$university_code/$branch_code/$last_id";
            $course_payment->created_by =  $request->input('created_by');
            $course_payment->student_track_id = $student_track_id;
            $course_payment->branch_id  = $branch_id;

            $course_payment->admission_date  =  $admission_date;
            $course_payment->student_id = $student_id;
            $course_payment->course_id = $request->input('course');
            $course_payment->course_schedule_id = $request->input('course_period');
            $course_payment->amount = 0;
            $course_payment->discount = 0;
            $course_payment->university_loginid  =  $request->input('univ_login_id');
            $course_payment->university_loginpass  =  $request->input('univ_login_pass');
            $course_payment->status  =  $request->input('stud_status');
        } else {
            if (Auth::user()->role_id == 1) {
                $course_payment->created_by =  $request->input('created_by');
            }

            $course_payment->admission_date  =  $admission_date;
            $course_payment->branch_id  = $branch_id;
            $course_payment->university_loginid  =  $request->input('univ_login_id');
            $course_payment->university_loginpass  =  $request->input('univ_login_pass');
            $course_payment->status  =  $request->input('stud_status');
            $student_track_id = $course_payment->student_track_id;
        }
        $course_payment->save();
        if (!$request->input('update_common_details')) {
            if ($request->input('payment_id') && $request->input('payment_id') != '') {
                $payments = Payments::where('id', $request->input('payment_id'))->first();
            } else {
                $payments = new Payments();
            }

            $payments->student_id = $student_id;
            $payments->payment_method_id = $request->input('payment_method');
            $payments->amount = $request->input('amount');
            $payments->discount_amount = $request->input('discount_amount');
            $payments->promocode = $promoCode;
            $payments->payment_date = date('Y-m-d', strtotime($request->input('transaction_date')));
            $payments->terminal_id = 1;
            $payments->card_type_id = $request->input('card_type');
            $payments->bank_id = $request->input('bank');
            $payments->transaction_ref = $request->input('trans_ref');
            $payments->notes = '';
            $payments->status = 'pending';
            $payments->course_id = $request->input('course');
            $payments->course_schedule_id = $request->input('course_period');
            $payments->created_by = $logged_userID;
            $payments->next_payment_date = date('Y-m-d', strtotime($request->input('next_pay_date')));
            $payments->save();
            $payment_id = $payments->id;
            if ($is_installment == 'installment') {
                $course_installment_amount = $course_installment_data->installment_amount;
                $amount_difference =  $current_amount_tot_copy  - $course_installment_amount;
                if ($request->input('payment_id') && $request->input('payment_id') != '') {
                    $installment_history = InstallmentHistory::where('payment_id', $request->input('payment_id'))
                        ->first();
                } else {
                    $installment_history = new InstallmentHistory();
                }

                $installment_history->installment_id = $request->input('installment_id');
                $installment_history->paid_amount = $request->input('amount');
                $installment_history->amount_difference = $amount_difference;
                $installment_history->discount = $request->input('discount_amount');
                $installment_history->payment_status = 'pending';
                $installment_history->currency = 'INR';
                $installment_history->payment_date = date('Y-m-d', strtotime($request->input('transaction_date')));
                $installment_history->created_by = Auth::id();
                $installment_history->payment_id = $payment_id;
                $installment_history->save();
            }
            $prolfile_completed = $this->getProfileCompletedState($student_id);
            return response()->json(
                [

                    'status' => '',
                    'message' =>
                    '<i class="fa fa-check-circle text-success"></i>&nbsp;&nbsp;Student payments updated successfully.',
                    'payment_id' => $payment_id,
                    'prolfile_completed' => $prolfile_completed,
                    'student_track_id' => $student_track_id,
                ]
            );
        } else {

            return response()->json(
                [
                    'status' => '',
                    'message' =>
                    '<i class="fa fa-check-circle text-success"></i>&nbsp;&nbsp;Student details updated successfully.',
                    'student_track_id' => $student_track_id,
                ]
            );
        }
    }

    public function verifyStudentTrackSeries($course_id, $branch_id, $return_exist = False)
    {

        $universityCode = Courses::where('id', $course_id)->value("university_id");
        $exists = StudentsTracknos::where('branch_id', $branch_id)
            ->where('university_id', $universityCode)
            ->exists();
        if ($return_exist) {
            return [$exists, $universityCode];
        }
        $branchName = Branches::where('id', $branch_id)->value("name");
        $UniversityName = Universities::where('id', $universityCode)->value("name");
        if ($exists) {
            $errorView = '';
        } else {
            $query = CoursePayments::select(
                'course_payments.*',
                DB::raw('REGEXP_SUBSTR(student_track_id, "[0-9]+$") AS last_number')
            )
                ->join('courses', 'courses.id', '=', 'course_payments.course_id')
                ->where('courses.university_id', $universityCode)
                ->where('course_payments.branch_id', $branch_id)
                ->where('course_payments.student_track_id', 'like', 'FCZ%')
                ->orderByRaw('CAST(REGEXP_SUBSTR(student_track_id, "[0-9]+$") AS UNSIGNED) DESC')
                ->limit(1);
            // return $query;
            if ($query === null) {
                $student_track_id = 'NA';
            } else {
                $result = $query->first();
                if ($result !== null && $result->last_number !== null) {
                    $student_track_id = $result->last_number . ' (' . $result->student_track_id . ')';
                } else {
                    $student_track_id = 'NA';
                }
            }
            $errorView = view(
                'students.create_student.partials.add_student_track_id',
                [
                    'universityCode' => $universityCode,
                    'branch_id' => $branch_id,
                    'branchName' => $branchName,
                    'UniversityName' => $UniversityName,
                    'course_id' => $course_id,
                    'student_track_id' => $student_track_id,

                ]
            )->render();
        }
        return [
            'status' => $exists,
            'errorView' => $errorView,
            'universityCode' => $universityCode
        ];
    }

    public function test_payment()
    {
        // return Auth::user()->role_id;
        $query = CoursePayments::select(
            'course_payments.*',
            DB::raw('REGEXP_SUBSTR(student_track_id, "[0-9]+$", 1, 1) AS last_number')
        )
            ->join('courses', 'courses.id', '=', 'course_payments.course_id')
            ->where('courses.university_id', 9233)
            ->where('course_payments.branch_id', 2)
            ->where('course_payments.student_track_id', 'like', 'FCZ%')
            ->orderByRaw('CAST(REGEXP_SUBSTR(student_track_id, "[0-9]+$", 1, 1) AS UNSIGNED) DESC')
            ->limit(1);
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        dd($sql, $bindings);
    }

    public function SaveStudentTrackNumber(Request $request)
    {
        $rules = [
            'numberInput' => 'required|string|max:255',
            'course_code' => 'required|string|max:255',
            'branch_code' => 'required|string|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $course_code = $request->input('course_code');
        $branch_code = $request->input('branch_code');
        $numberInput = $request->input('numberInput');

        $data_return = $this->verifyStudentTrackSeries($course_code, $branch_code, true);
        $data_exist = $data_return[0];
        $university_id = $data_return[1];

        if ($data_exist === null || $data_exist == "") {

            $trackno = new StudentsTracknos([
                'university_id' => $university_id,
                'branch_id' => $branch_code,
                'next_number' => $numberInput,
            ]);
            $trackno->save();
            return response()->json([
                'message' => 'Track number updated successfully.',
                'next_number' => $trackno->next_number
            ]);
        }
        if ($data_exist) {
            return response()->json(['error' => "An error has occurred. Please refresh the page and try again."], 422);
        }
    }

    public function PaymentApproveView($payment_status = 'pending')
    {
        $payments_data = Payments::with(['banks:id,bank_name'])->where('status', $payment_status);
        $payments_data = $payments_data->paginate(1000);

        return view('payments.payments_approve', ['payments_data' => $payments_data]);
    }

    public function RejectPayments(Request $request)
    {
        $checked_ids = $request->input('checkedIdsJson');
        Payments::whereIn('id', $checked_ids)->update(['status' => 'reversed']);
        $message = '<i class="fa fa-times text-danger"></i>&nbsp;&nbsp;Successefully reversed';
        return response()->json([
            'message' => $message,
            'rows_remove_ids' => $checked_ids
        ]);
    }

    public function ApprovePayments(Request $request)
    {
        $checked_ids = $request->input('checkedIdsJson');
        $verified_by_id = Auth::id();
        $non_approved_invoices = array();
        $approved_invoices = [];
        foreach ($checked_ids as $key => $checked_id) {
            $invoice_data = $this->InvoiceGenerate($checked_id, true);

            if ($invoice_data['course_payment_now'] == 'completed') {
                array_push($non_approved_invoices, $checked_id);
            } else {
                array_push($approved_invoices, $checked_id);
                $course_payment = $invoice_data['course_payment'];
                $course_payment->amount = $invoice_data['amount'];
                $course_payment->discount = $invoice_data['discount_amount'];
                $course_payment->payment_status = $invoice_data['payment_status'];
                $course_payment->payment_id  = $checked_id;
                if ($invoice_data['next_payment_date'] != '0000-00-00') {
                    $course_payment->next_payment_date = $invoice_data['next_payment_date'];
                }
                $course_payment->save();
                $payments = Payments::where('id', $checked_id)->first();
                $payments->verified_by = $verified_by_id;
                $payments->status = 'active';
                $payments->save();

                $installment_hist = InstallmentHistory::where('payment_id', $checked_id)->first();
                if ($installment_hist) {
                    $course_installment = CourseInstallments::where('id', $installment_hist->installment_id)->first();

                    if ($course_installment) {
                        $installment_amount = $course_installment->installment_amount;
                        $total_amount = floatval($invoice_data['amount_paid']);
                        $total_amount += isset($invoice_data['dicount_amount_current']) ? floatval($invoice_data['dicount_amount_current'])  : 0;

                        if ($installment_amount == $total_amount) {
                            $payment_status = 'paid';
                        } elseif ($installment_amount < $total_amount) {
                            $payment_status = 'overpaid';
                        } else {
                            $payment_status = 'underpaid';
                        }

                        $course_installment->paid_amount += $invoice_data['amount_paid'];
                        $course_installment->completed_installments += 1;

                        if (strtolower($course_installment->next_reminder_days) == 'monthly') {
                            $nextReminderDate = strtotime("+1 month", strtotime($course_installment->next_reminder_date));
                        } else {
                            $nextReminderDate = strtotime("+" . $course_installment->next_reminder_days . " days", strtotime($course_installment->next_reminder_date));
                        }

                        $nextDue_date = strtotime("+" . $course_installment->due_days . " days", $nextReminderDate);

                        $course_installment->next_reminder_date = date('Y-m-d', $nextReminderDate);
                        $course_installment->due_date = date('Y-m-d', $nextDue_date);
                        $course_installment->save();
                        $installment_hist->payment_status = $payment_status;
                        $installment_hist->payment_status = $payment_status;
                        $installment_hist->save();
                    } else {
                        // Handle case when course_installment is not found
                    }
                }



                $payments->status = 'active';
                $payments->save();
            }
        }
        $non_approved_invoices_cnt = count($non_approved_invoices);
        $approved_invoices_cnt = count($approved_invoices);
        if ($non_approved_invoices_cnt > 0 && $approved_invoices_cnt  > 0) {
            $message = '<i class="fa fa-info-circle text-info">&nbsp;&nbsp;
            Approval successful. Note: Some students payments are already completed and not approved';
        } else if ($non_approved_invoices_cnt == 0 && $approved_invoices_cnt  > 0) {
            $message = '<i class="fa fa-check-circle text-success">&nbsp;&nbsp;Approval successfully done';
        } else {
            $message = '<i class="fa fa-times text-danger">&nbsp;&nbsp;Approval not successfully done';
        }
        return response()->json([
            'message' => $message,
            'rows_remove_ids' => $approved_invoices
        ]);
    }

     public function InvoicePrint(Request $request)
    {

        $checked_ids = $request->input('checkedIdsJson');
        if (count($checked_ids) == 1) {
            $pdf_path = $this->InvoiceGenerate($checked_ids[0], false, 'print');
            return $pdf_path;
        }
        // foreach ($checked_ids as $checked_id) {

        //     // array_push($pdfPaths, $pdf_path);
        // }
    }

    public function InvoiceGenerate($checked_id, $return_data = false, $print = '')
    {
        $verified_by = Auth::user()->name;
        $digit_format = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $payment_data = Payments::with(
            [
                'courses:id,specialization,university_id,stream_id',
                'courses.streams:id,code', // Add stream relationship (adjust as needed)
                'course_schedule:id,course_fee,other_fees',
                'student:id,first_name,last_name,email,phone_number',
                'payment_methods:id,method_name',
                'created_user:id,name',
                'banks:id,bank_name'
            ]
        )
            ->where('id', $checked_id)
            ->first([
                'student_id',
                'course_id',
                'course_schedule_id',
                'payment_method_id',
                'amount',
                'discount_amount',
                'payment_date',
                'bank_id',
                'transaction_ref',
                'next_payment_date',
                'created_by',
                'bank_id'
            ]);
        $course_id = $payment_data->courses->id;
        $course_schedule_id = $payment_data->course_schedule->id;
        $course_payment = CoursePayments::where('student_id', $payment_data->student->id)
            ->where('course_id', $course_id)
            ->where('course_schedule_id', $course_schedule_id)
            ->first();
        $this->update_profile_completion($payment_data->student->id, 3, 4);
        $university_name = Universities::where('id', $payment_data->courses->university_id)
            ->value('name');
        if (!($course_payment->branch_id)) {
            return $payment_data->student_id;
        }
        $branch_name = Branches::where('id', $course_payment->branch_id)
            ->value('name');
        $course_fee = $payment_data->course_schedule->course_fee + $payment_data->course_schedule->other_fees;
        $amount_paid = floatval($payment_data->amount);
        $discount_amount = floatval($payment_data->discount_amount);

        $current_invoice_tot = $amount_paid + $discount_amount;
        $existingAmount = floatval($course_payment->amount);

        $existingDiscountAmount = floatval($course_payment->discount);
        if ($print == 'print') {
            $course_fee_paid = $existingAmount + $existingDiscountAmount;
            $course_fee_discount = $existingDiscountAmount;;
        } else {
            $course_fee_paid = $existingAmount + $current_invoice_tot + $existingDiscountAmount;
            $course_fee_discount = $existingDiscountAmount + $discount_amount;;
        }


        if ($return_data) {

            if ($course_fee_paid == $course_fee) {
                $payment_status = 'completed';
            } else {
                $payment_status = 'pending';
            }
            return [
                'next_payment_date' => $payment_data->next_payment_date,
                'amount' => $existingAmount + $amount_paid,
                'amount_paid' => $amount_paid,
                'dicount_amount_current' => $discount_amount,
                'discount_amount' => $existingDiscountAmount + $discount_amount,
                'course_payment' => $course_payment,
                'payment_status' => $payment_status,
                'course_payment_now' => $course_payment->payment_status,
            ];
        } else {
            $course_payment_first = Payments::where('student_id', $payment_data->student->id)
                ->where('course_id', $course_id)
                ->where('course_schedule_id', $course_schedule_id)
                ->orderBy('id', 'asc') // or 'desc' depending on your needs
                ->select('id') // select only the 'id' column
                ->first();

            if ($course_payment_first->id == $checked_id) {
                $new_payment = true;
            } else {
                $new_payment = false;
            }
            $amount_paid_words = $digit_format->format($amount_paid);
            $amount_paid_words = ucwords(str_replace('-', ' ', $amount_paid_words)) . ' Only';
            $course_name = $payment_data->courses->streams->code  . ' ' . $payment_data->courses->specialization;
            $invoiceData = [];
            $invoiceData[$checked_id]['receiver_name'] = $payment_data->student->first_name . ' ' . $payment_data->student->last_name;
            $invoiceData[$checked_id]['course_name'] = $course_name;
            $invoiceData[$checked_id]['institution'] = $university_name;
            $invoiceData[$checked_id]['branch'] = $branch_name;
            $invoiceData[$checked_id]['receiver_mobile'] = $payment_data->student->phone_number;
            $invoiceData[$checked_id]['receiver_email'] = $payment_data->student->email;
            $invoiceData[$checked_id]['amount_in_words'] = $amount_paid_words;
            $invoiceData[$checked_id]['transaction_ref'] = $payment_data->transaction_ref;


            $invoiceData[$checked_id]['payment_method'] = $payment_data->payment_methods->method_name;
            $invoiceData[$checked_id]['course_fee'] = $course_fee;
            $invoiceData[$checked_id]['course_fee_paid'] = $course_fee_paid;
            $invoiceData[$checked_id]['course_fee_balance'] = $course_fee - $course_fee_paid;
            $invoiceData[$checked_id]['course_fee_paid_now'] = $amount_paid;
            $invoiceData[$checked_id]['course_fee_discount_paid_now'] = $discount_amount;
            $invoiceData[$checked_id]['course_fee_discount'] = $course_fee_discount;
            $invoiceData[$checked_id]['invoice_date'] = date('d-m-Y');
            $invoiceData[$checked_id]['invoice_number'] = $checked_id;
            $invoiceData[$checked_id]['new_payment'] = $new_payment;
            $invoiceData[$checked_id]['verified_by'] = $verified_by;
            $invoiceData[$checked_id]['created_by'] = $payment_data->created_user->name;
            $invoiceData[$checked_id]['student_track_id'] = $course_payment->student_track_id;

            if ($payment_data->banks !== null && $payment_data->banks->bank_name !== null) {
                $invoiceData[$checked_id]['bank_name'] = $payment_data->banks->bank_name;
            } else {
                $invoiceData[$checked_id]['bank_name'] = "";
            }

            $pdfDirectory = storage_path('app/public/invoices');

            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0777, true);  // Create directory if it doesn't exist
            }

            $invoiceController = new InvoiceController();
            $pdf_path = $invoiceController->generateInvoice($pdfDirectory, $invoiceData[$checked_id]);
            return $pdf_path;
        }
    }

    public function ViewPromotions()
    {
        $promotions_data = Discounts::get();
        return view('payments.promotions', ['promotions_data' => $promotions_data]);
    }

    public function ManagePromotions(Request $request)
    {
        if ($request->input('edit_id') !== null) {
            $promo_data = Discounts::where("id", $request->input('edit_id'))->first();
        } else {
            $promo_data = new Discounts();
        }
        return view(
            'payments.partials.manage_promo',
            [
                "promo_data" => $promo_data,

            ]
        );
    }

    public function SavePromo(Request $request)
    {
        // Define validation rules
        $rules = [
            'promoCode' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'endDate' => 'required|date|after:startDate',
        ];

        if ($request->input('id_exist') !== null) {
            $rules['startDate'] = 'required|date';
        } else {
            $rules['startDate'] = 'required|date|after_or_equal:today';
        }
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $discount = Discounts::firstOrNew(['id' => $request->input('id_exist')]);

        $discount->promocode = $request->promoCode;
        $discount->description = $request->description;
        $discount->discount_amount = $request->amount;
        $discount->start_date = date('Y-m-d', strtotime($request->startDate));
        $discount->end_date = date('Y-m-d', strtotime($request->endDate));
        $discount->save();

        return response()->json(['message' => 'Discount successfully created!'], 200);
    }
}
