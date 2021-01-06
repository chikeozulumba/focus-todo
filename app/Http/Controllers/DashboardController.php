<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    /**
     * Implements showDashboard().
     *
     * This method returns a view for the user to use the dashboard
     *
     * @return view
     */
    public function showDashboard(): View
    {
        return view('pages.dashboard');
    }
}
