<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class OfficeController extends BaseController
{
    public function index()
    {
        return view('Office/Index');
    }

    public function pending()
    {
        return view('Office/Pending');
    }

    public function ongoing()
    {
        return view('Office/OnGoing');
    }

    public function completed()
    {
        return view('Office/Completed');
    }

    public function history()
    {
        return view('Office/History');
    }

    public function manageprofile()
    {
        return view('Office/ManageProfile');
    }

    public function trash()
    {
        return view('Office/Trash');
    }
}
