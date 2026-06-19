<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use App\Support\Niche;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Niche $niche): View
    {
        $stats = [
            'customers' => Customer::where('niche', $niche->key())->count(),
            'appointments_upcoming' => Appointment::where('niche', $niche->key())
                ->where('status', 'scheduled')
                ->where('starts_at', '>=', now())
                ->count(),
            // Populated in later phases.
            'invoices_unpaid' => 0,
            'due_to_reschedule' => 0,
        ];

        return view('dashboard', compact('stats'));
    }
}
