<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Models\Branches;
use App\Models\Cities;
use App\Models\Countries;
use App\Models\CourseInstallments;
use App\Models\CoursePayments;
use App\Models\Courses;
use App\Models\Discounts;
use App\Models\DocumentCategories;
use App\Models\Documents;
use App\Models\EducationalQualifications;
use App\Models\Employees;
use App\Models\EmploymentStatuses;
use App\Models\IdentityCards;
use App\Models\InstallmentHistory;
use App\Models\MaritalStatus;
use App\Models\PaymentMethods;
use App\Models\Payments;
use App\Models\ReligionCategories;
use App\Models\Religions;
use App\Models\States;
use App\Models\Students;
use App\Models\StudentsTracknos;
use App\Models\Terminals;
use App\Rules\EmailDoesNotExist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Framework\MockObject\Builder\Stub;

class StudentController extends Controller
{
    public $marrital_status;
    public $genderOptionsAr;
    public $max_profile_completion_steps;
    public $completed_levels;
    public function __construct()
    {
        $this->genderOptionsAr = array('male', 'female', 'other');
        $this->middleware('auth');
        $this->max_profile_completion_steps = 4;
        $this->completed_levels = [
            "15%" => 'danger',
            "25%" => 'warning',
            "50%" => 'primary',
            "75%" => 'info',
            "100%" => 'success',
        ];
    }
    public function add_students($step = 1, $student_id = null, $course_id_param = null, $course_schedule_id_param = null, $installment_id_param = null)
    {

        $checked_update_email = "";
        if ($student_id !== null) {
            $checked_update_email = "checked";
            $studentData = Students::where('id', $student_id)->first();
            $profile_completed = $studentData->profile_completion;
            $educationData = EducationalQualifications::where('student_id', $student_id)->latest()->first();
            if (!$educationData) {
                $educationData = new EducationalQualifications();
            }
        } else {
            $profile_completed = 0;
            $step = 1;
            $studentData = new Students();
            $educationData = new EducationalQualifications();
        }
        $marital_statusAr = MaritalStatus::select('id', 'marital_status', 'description')
            ->where('status', 'active')
            ->get();

        $employment_statusesAr = EmploymentStatuses::all();
        $branchesAr = Branches::all();
        $religionsAr = Religions::all();
        $religion_categoriesAr = ReligionCategories::all();
        $countriesAr = Countries::all();
        $idCardAr = IdentityCards::all();
        $installment_data = CourseInstallments::query();

        if ($installment_id_param === null) {

            $installment_data = $installment_data->where('student_id', $student_id)
                ->where('course_id', $course_id_param)
                ->where('course_schedule_id', $course_schedule_id_param);
        } else {
            $installment_data = $installment_data->where('id', $installment_id_param);
        }
        $installment_data = $installment_data->first(['id', 'installment_amount']);
        if ($installment_data) {
            $installment_id_param = $installment_data->id;
            $installment_amount = $installment_data->installment_amount;
            $pending_amount_installment = InstallmentHistory::where('installment_id', $installment_data->id)
                ->where('payment_status', '!=', 'pending') // Corrected condition
                ->sum('amount_difference');

        } else {
            $pending_amount_installment = 0;
            $installment_id_param = '';
            $installment_amount = '';
        }

        $documentCategoriesAr = DocumentCategories::where('status', 'active')->get();
        if ($course_schedule_id_param == null) {
            $course_schedule_id_param = '';
        }
        return view(
            'students.add_students',
            [
                'marital_statusAr' => $marital_statusAr,
                'employment_statusesAr' => $employment_statusesAr,
                'religionsAr' => $religionsAr,
                'religion_categoriesAr' => $religion_categoriesAr,
                'countriesAr' => $countriesAr,
                'idCardAr' => $idCardAr,
                'documentCategoriesAr' => $documentCategoriesAr,
                'studentData' => $studentData,
                'genderOptionsAr' => $this->genderOptionsAr,
                'educationData' => $educationData,
                'step' => $step,
                'student_id_url' => $student_id,
                'profile_completed' => $profile_completed,
                'max_profile_completion_steps' => $this->max_profile_completion_steps,
                'completed_levels' => $this->completed_levels,
                'course_id_param' => $course_id_param,
                'course_schedule_id_param' => $course_schedule_id_param,
                'checked_update_email' => $checked_update_email,
                'branchesAr' => $branchesAr,
                'installment_id_param' => $installment_id_param,
                'installment_amount' => $installment_amount,
                'pending_amount_installment' => $pending_amount_installment
            ]
        );
    }



    public function savePersonalInfo(Request $request)
    {
        if ($request->input('email_update') !== null) {
            $email_rule =  ['required', 'email'];
        } else {
            $email_rule =  ['required', 'email', new EmailDoesNotExist];
        }
        if ($request->input('id_num') !== null || $request->input('id_type')) {
            $id_validate = 'required|string|max:50';
        } else {
            $id_validate = 'nullable|string|max:50';
        }
        $id_validate = 'required|string|max:50';
        $rules = [
            'fname' => 'required|string|max:255',
            'lname' => 'nullable|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'marital_status' => 'nullable|string|max:50',
            'employment_status' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:100',
            'religion_category' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'email' => $email_rule,
            'phone' => 'required|string|min:6|max:20',
            'alt_phone' => 'nullable|string|max:20',
            'dob' => 'required|date',
            'id_type' => $id_validate,
            'id_num' => $id_validate,
            // 'emergency_contact_name' => 'nullable|string|max:255',
            // 'emergency_contact_tel' => 'required|string|max:20',
            'gender' =>  'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $studentId = Students::where('email', $request->input('email'))
            ->value('id') ?? $request->input('student_id');

        if ($studentId) {
            $student = Students::find($studentId);
        } else {
            $student = new Students();
        }


        $student->first_name = $request->input('fname');
        $student->last_name = $request->input('lname');
        $student->fathers_name = $request->input('father_name');
        $student->mothers_name = $request->input('mother_name');
        $student->religion_id = $request->input('religion');
        $student->religion_category_id = $request->input('religion_category');
        $student->country_id = $request->input('country');
        $student->city_id = $request->input('city');
        $student->state_id = $request->input('state');
        $student->postal_code = $request->input('postal_code');
        // enrollment_date
        $student->identity_card_id = $request->input('id_type');
        $student->identity_card_no = $request->input('id_num');

        $student->employment_status_id = $request->input('employment_status');
        $student->date_of_birth = date('Y-m-d', strtotime($request->input('dob')));
        $student->email = $request->input('email');
        $student->phone_number = $request->input('phone');
        $student->alternative_number = $request->input('alt_phone');

        $student->address = $request->input('address');
        $student->emergency_contact_name = $request->input('emergency_contact_name');
        // $student->emergency_contact_phone = $request->input('emergency_contact_tel');
        $student->marital_status_id = $request->input('marital_status');

        $student->nationality_id = $request->input('nationality');
        $student->gender = $request->input('gender');
        if (!$studentId) {
            $student->profile_completion = 1;
        }
        $student->save();

        $student_id = $student->id;
        $prolfile_completed = $this->getProfileCompletedState($student_id);
        // Students::create($request->all());
        return response()->json([
            'success' => '<i class="fa fa-check-circle text-success"></i>&nbsp;Student personal details updated successfully.',
            'student_id' => $student_id,
            'prolfile_completed' => $prolfile_completed
        ]);
    }

    public function saveEdcationInfo(Request $request)
    {
        // if ($request->input('degree') === null || $request->input('other_degree_name') === null) {
        //     $degree_required = 'required|string|max:255';
        // } else {
        //     $degree_required = 'nullable|string|max:255';
        // }
        // if ($request->input('university') === null || $request->input('other_institute_name') === null) {
        //     $institute_required = 'required|string|max:255';
        // } else {
        //     $institute_required = 'nullable|string|max:255';
        // }
        $rules = [
            // 'degree' => $degree_required,
            // 'university' => $institute_required,
            // 'other_institute_name' => 'required|string|max:255',
            'other_degree_name' => 'required|string|max:255',
            'passout_year' => 'required|string|max:255',
            'student_id' => 'required|string|max:255',
            // 'course_name' => 'nullable|string|max:255', //field_of_study
            'other_institute_name' => 'required|string|max:255', //field_of_study
            'gpa' => 'nullable|string|max:255', //field_of_study
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->input('education_id')) {
            $education = EducationalQualifications::find($request->input('education_id'));
        } else if ($request->input('student_id')) {
            $education = EducationalQualifications::where('student_id', $request->input('student_id'))
                ->first();
            if ($education === null) {
                $education = new EducationalQualifications();
            }
        } else {
            $education = new EducationalQualifications();
        }

        // return $request->input('degree');;
        $education->student_id = $request->input('student_id');
        // $education->field_of_study = $request->input('course_name');
        $education->graduation_year = $request->input('passout_year');
        $education->gpa = $request->input('gpa');
        $education->other_degree_name = $request->input('other_degree_name');
        $education->other_college_name = $request->input('other_institute_name');
        $education->degree_id = $request->input('degree');
        $education->institution_id = $request->input('university');
        $education->save();
        $this->update_profile_completion($request->input('student_id'), 1, 2);

        $prolfile_completed = $this->getProfileCompletedState($request->input('student_id'));
        // Students::create($request->all());
        return response()->json([
            'success' => '<i class="fa fa-check-circle text-success"></i>&nbsp;Student qualification updated successfully.',
            'education_id' => $education->id,
            'prolfile_completed' => $prolfile_completed
        ]);
    }



    public function view_students(Request $request, $search = null)
    {
        
        $query = Students::with('city:id,name', 'state:id,name', 'identity_card:id,name');
        $data = [];
        if ($request->isMethod('post')) {
            // Initialize the query for the Students model
            $data = request()->all();

            unset($data['_token']);
            if (!is_null($data) || !empty(array_filter($data))) {
                $data_posted = false;
            } else {
                $data_posted = true;
            }
            // Apply filters to the Students query
            if ($request->input('name') !== null) {

                $query->where('first_name', $request->input('name'));
            }
            if ($request->filled('gender')) {
                $query->where('gender', $request->input('gender'));
            }
            if ($request->filled('phone_number')) {
                $query->where('phone_number', $request->input('phone_number'));
            }

            if ($request->filled('marital_status')) {
                $query->whereIn('marital_status_id', $request->filled('marital_status'));
            }

            if ($request->filled('email')) {
                $query->where('email', $request->input('email'));
            }

            if ($request->filled('employment_status')) {
                $query->whereIn('employment_status_id', $request->filled('employment_status'));
            }

            if ($request->filled('pending_profile_completion')) {
                $query->where('profile_completion', $request->input('pending_profile_completion'));
            }

            if ($request->filled('city')) {
                $cityInput = $request->input('city');
                if (is_string($cityInput)) {
                    $cityInput = explode(',', $cityInput);
                }
                $query->whereIn('city_id', $cityInput);
            } else if ($request->filled('state')) {
                $query->where('state_id', $request->input('state'));
            } else if ($request->filled('country')) {
                $query->where('country_id', $request->input('country'));
            }


            $studentsIds3 = collect();
            // Prepare the subquery for pending payments

            if ($request->filled('pending_payments') || $request->filled('course')) {
                $query_payment = CoursePayments::query();
                if ($request->filled('pending_payments')) {
                    $query_payment = $query_payment->where('payment_status', $request->input('pending_payments'));

                    if ($request->input('pending_payments') == 'pending') {
                        $studentsIds3 = Students::where('profile_completion', '<', 3)->pluck('id');
                    }
                }
                if ($request->filled('course')) {
                    $courseInput = $request->input('course');
                    if (is_string($courseInput)) {
                        $courseInput = explode(',', $courseInput);
                    }
                    $query_payment = $query_payment->whereIn('course_id', $courseInput);
                }

                $studentsIds1 = $query_payment->where('status', 'active')
                    ->pluck('student_id');
            } else {
                $studentsIds1 = collect(); // empty collection if no pending payments
            }


            // Prepare the subquery for course and university
            if ($request->filled('degree') || $request->filled('university')) {

                $query2 = EducationalQualifications::query();

                if ($request->filled('degree')) {
                    $query2->where('other_degree_name', $request->input('degree'));
                }
                if ($request->filled('university')) {
                    $query2->where('other_college_name', $request->input('university'));
                }

                $studentsIds2 = $query2->pluck('student_id');
            } else {
                $studentsIds2 = collect(); // empty collection if no filters
            }
            // return $studentsIds3;
            // Get the intersected IDs
            $studentsIds = $studentsIds1->merge($studentsIds2)->merge($studentsIds3)->unique();

            // Apply the IDs to the Students query if any IDs are found
            if ($studentsIds->isNotEmpty()) {
                $query->whereIn('id', $studentsIds);
            }
        } else if ($search !== null) {
            $data_posted = true;
            if ($search == 'new') {
                $created_from_date = Carbon::now()->subMonth()->startOfMonth()->toDateString();
                $created_to_date = Carbon::now()->endOfMonth()->toDateString();
                $query = $query->whereBetween('created_at', [$created_from_date, $created_to_date]);
            } else if ($search == 'unpaid') {
                $studentsIds1 = CoursePayments::query()
                    ->where('payment_status', 'pending')
                    ->where('status', 'active')
                    ->pluck('student_id');

                $studentsIds2 = Students::where('profile_completion', '<', 3)->pluck('id');
                $studentsIds = $studentsIds1->merge($studentsIds2)->merge($studentsIds2)->unique();

                if ($studentsIds->isNotEmpty()) {
                    $query->whereIn('id', $studentsIds);
                }
            }
        } else {
            $data_posted = false;
            $data = [];
        }

        $bindings = $query->getBindings();
        if ($data_posted == true && count($bindings) == 0) {
            $students_data = [];
        } else {
            if ($request->input('excel') !== null && $request->input('excel') == "true") {
                $fileName = 'students_' . Auth::id() . '.xlsx';

                $filePath = 'public/exports/' . $fileName;
                $directory = dirname($filePath);
                if (!Storage::exists($directory)) {
                    Storage::makeDirectory($directory);
                }
                Excel::store(new StudentsExport($query->get()), $filePath);
                $storage_path = Storage::url($filePath);
                return response()->json(["status" => "success", 'filePath' => "public/" . $storage_path]);
            } else {
                $students_data = $query->paginate(1000);
            }
        }


        // dd($data);
        return view(
            'students.view_students',
            [
                'students_data' => $students_data,
                'dataAr' => $data
            ]
        );
    }

    public function loadViewStudentFilter(Request $request)
    {

        $profile_completionAr = [
            1 => 'Personal Qualifications',
            2 => 'Pending Payments',
            3 => 'Pending Document Upload',
            4 => 'Completed',
        ];
        $genderAr = [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
        ];
        $paymentStatusAr = [
            'completed' => 'Completed',
            'pending' => 'Pending',
        ];
        $marital_statusAr = MaritalStatus::select('id', 'marital_status AS name')
            ->where('status', 'active')
            ->get();
        $employment_statusesAr = EmploymentStatuses::select('id', 'status_name AS name')->get();
        $countriesAr = Countries::select('id', 'name')->get();
        $country = $request->input('country');
        $state = $request->input('state');
        if ($country !== null) {
            $states = States::where('country_id', $country)->get();
        } else {
            $states = [];
        }
        if ($state !== null) {
            $cities = Cities::where('state_id', $state)->get();
        } else {
            $cities = [];
        }
        $city = explode(",", $request->input('city'));
        $coursesAr = DB::table('courses')
            ->join('universities', 'courses.university_id', '=', 'universities.id')
            ->join('streams', 'courses.stream_id', '=', 'streams.id')
            ->select(
                'courses.id',
                DB::raw("CONCAT(streams.code, ' ', courses.specialization, ', ', universities.name) AS name")
            )
            ->get();
        return view('students.view_students.view_student_filter', [
            'marital_statusAr' => $marital_statusAr,
            'employment_statusesAr' => $employment_statusesAr,
            'countriesAr' => $countriesAr,
            'coursesAr' => $coursesAr,
            'name' => $request->input('name'),
            'gender' => $request->input('gender'),
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'country' => $country,
            'state' => $state,
            'city' => $city,
            'university' => $request->input('university'),
            'degree' => $request->input('degree'),
            'course' => explode(",", $request->input('course')),
            'profile_completionAr' => $profile_completionAr,
            'genderAr' => $genderAr,
            'paymentStatusAr' => $paymentStatusAr,
            'pending_payments' =>  $request->input('pending_payments'),
            'pending_profile_completion' =>  $request->input('pending_profile_completion'),
            'states' => $states,
            'cities' => $cities
        ]);
    }

    public function view_profile($step, $student_id)
    {
        $studentData = Students::with(
            'city:id,name',
            'state:id,name',
            'country:id,name',
            'religion:id,religion_name',
            'religion_category:id,religion_category',
            'identity_card:id,name',
            'employment_status:id,status_name',
            'marital_status:id,marital_status',
            'nationality:id,name'
        )
            ->where('id', $student_id)
            ->first();
        if (!$studentData) {
            abort(404);
        } else {
            $educationData = EducationalQualifications::where('student_id', $student_id)
                ->get();

            $coursePaymentsAr = CoursePayments::with(
                'courses:id,university_id',
            )
                ->where('student_id', $student_id)
                ->where('status', 'active')
                ->get();


            $documentsData = Documents::with(
                'doc_category:id,category_name',
            )
                ->where('student_id', $student_id)
                ->get();

            return view(
                'students.view_student_profile',
                [
                    'studentData' => $studentData,
                    'educationDataAr' => $educationData,
                    'coursePaymentsAr' => $coursePaymentsAr,
                    'documentsDataAr' => $documentsData,
                    'student_id' => $student_id,
                    'step' => $step,
                    'profile_completed' => $studentData->profile_completion,
                    'max_profile_completion_steps' => $this->max_profile_completion_steps,
                    'completed_levels' => $this->completed_levels
                ]
            );
        }
    }

    public function searchStudents(Request $request)
    {
        $term = $request->get('term');


        $term = $request->get('term'); // The search term

        $studentsQuery = Students::query()
            ->where('student_status', 'active') // Always apply status filter first
            ->where(function ($query) use ($term) {
                // This is the OR condition group:
                $query->where('first_name', 'like', '%' . $term . '%')
                    ->orWhere('last_name', 'like', '%' . $term . '%')
                    ->orWhere('email', 'like', '%' . $term . '%')
                    // OR EXISTS condition
                    ->orWhereExists(function ($subQuery) use ($term) {
                        $subQuery->selectRaw(1) // Check if any row exists
                            ->from('course_payments')
                            ->whereRaw('course_payments.student_id = students.id')
                            ->where('course_payments.student_track_id', 'like', '%' . $term . '%');
                    });
            })
            ->select('students.id', 'students.first_name', 'students.last_name', 'students.email');


        $students = $studentsQuery->get();


        // Format the response to match the expected structure for autocomplete
        $results = $students->map(function ($student) {
            $name = $student->first_name;
            if ($student->last_name != '') {
                $name .= ' ' . $student->last_name;
            }
            return [
                'id' => $student->id,
                'label' => $name . ', ' . $student->email,
            ];
        });
        return response()->json($results);
    }
}
