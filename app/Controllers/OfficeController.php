<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class OfficeController extends BaseController
{
    public function index()
    {
        return view('Office/Dashboard');
    }
}
