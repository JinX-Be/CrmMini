<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Phase 1+ will populate these with real counts.
        $stats = [
            'customers' => 0,
            'appointments_upcoming' => 0,
            'invoices_unpaid' => 0,
            'due_to_reschedule' => 0,
        ];

        return view('dashboard', compact('stats'));
    }
}
