<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function viewEmployees()
    {
        $user_categories = Roles::all();

        $emp_dataAr = Employees::with('users:id,is_active,profile_picture,role_id')->paginate(100);
        return view('employees.view_employees', ['emp_dataAr' => $emp_dataAr, 'user_categories' => $user_categories]);
    }

    public function updateEmpStatus(Request $request)
    {
        $rules = [
            'emp_id' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => '<i class="fa fa-times text-danger"></i>&nbsp;An error occurred. Please try refreshing the page.'
            ], 404);
        }

        $userId = $request->input('emp_id');
        if ($userId == Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => '<i class="fa fa-times text-danger"></i>&nbsp;You are not permitted to update the status of your own account.'
            ], 404);
        } else if (Auth::user()->role_id != 1) {
            return response()->json([
                'status' => 'error',
                'message' => '<i class="fa fa-times text-danger"></i>&nbsp;Not allowed'
            ], 404);
        }
        $user = User::find($userId);
        if ($user) {
            $newStatus = $user->is_active ? 0 : 1;

            $user->is_active = $newStatus;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => '<i class="fa fa-check-circle text-success"></i>&nbsp;User status updated successfully.',
                'new_status' => $newStatus,
                'emp_id' => $userId
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => '<i class="fa fa-times text-danger"></i>&nbsp;User not found.'
            ], 404);
        }
    }

    public function updateEmpRole(Request $request)
    {
        $rules = [
            'emp_id' => 'required|string|max:255',
            'user_category' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => '<i class="fa fa-times text-danger"></i>&nbsp; An error occurred. Please try refreshing the page.'
            ], 404);
        }

        $userId = $request->input('emp_id');
        if ($userId == Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => '<i class="fa fa-times text-danger"></i>&nbsp;You are not permitted to update the status of your own account.'
            ], 404);
        } else if (Auth::user()->role_id != 1) {
            return response()->json([
                'status' => 'error',
                'message' => '<i class="fa fa-times text-danger"></i>&nbsp;Not allowed'
            ], 404);
        }
        $user = User::find($userId);
        if ($user) {


            $user->role_id = $request->input('user_category');
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => '<i class="fa fa-check-circle text-success"></i>&nbsp;User Role updated successfully.',
                'emp_id' => $userId
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => '<i class="fa fa-times text-danger"></i>&nbsp;User not found.'
            ], 404);
        }
    }

    public function UsernameAutocomplete(Request $request)
    {
        $term = $request->get('term');  // Search term

        $employees = Employees::where('first_name', 'LIKE', '%' . $term . '%')
            ->orWhere('last_name', 'LIKE', '%' . $term . '%')
            ->orWhere('email', 'LIKE', '%' . $term . '%')  // Add email search
            ->select('id', 'first_name', 'last_name', 'email')  // Select relevant fields
            ->limit(50)  // Limit results to 50
            ->get()
            ->map(function ($employee) {
                $employeeLabel = $employee->first_name . ' ' . $employee->last_name . ' ' .  $employee->email;
                return [
                    'label' => $employeeLabel,
                    'value' => $employee->id,
                ];
            });

        return response()->json($employees);
    }
}
