<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function feature_not_avail()
    {
        return view('permissions.feature_not_avail');
    }
}
