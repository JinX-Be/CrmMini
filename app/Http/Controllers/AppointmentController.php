<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Support\Niche;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /** The calendar page. */
    public function index(): View
    {
        return view('appointments.index');
    }

    /** JSON feed consumed by FullCalendar. */
    public function events(Request $request, Niche $niche): JsonResponse
    {
        $appointments = Appointment::query()
            ->with(['customer', 'service'])
            ->where('niche', $niche->key())
            ->when($request->filled('start'), fn ($q) => $q->where('starts_at', '>=', $request->date('start')))
            ->when($request->filled('end'), fn ($q) => $q->where('starts_at', '<=', $request->date('end')))
            ->get();

        $events = $appointments->map(fn (Appointment $a) => [
            'id' => $a->id,
            'title' => $a->customer?->name.($a->service ? ' · '.$a->service->name : ''),
            'start' => $a->starts_at->toIso8601String(),
            'end' => $a->endsAt()->toIso8601String(),
            'url' => route('appointments.show', $a),
            'color' => $a->statusColor(),
        ]);

        return response()->json($events);
    }

    public function create(Request $request, Niche $niche): View
    {
        $appointment = new Appointment([
            'status' => 'scheduled',
            'duration_minutes' => 60,
            'customer_id' => $request->integer('customer') ?: null,
            'starts_at' => $request->filled('date')
                ? $request->date('date')->setTime(9, 0)
                : now()->addDay()->setTime(9, 0),
        ]);

        return view('appointments.create', $this->formData($niche, $appointment));
    }

    public function store(AppointmentRequest $request, Niche $niche): RedirectResponse
    {
        $appointment = Appointment::create(
            $this->payload($request) + ['niche' => $niche->key()]
        );

        return redirect()
            ->route('appointments.show', $appointment)
            ->with('status', 'Appointment created.');
    }

    public function show(Appointment $appointment): View
    {
        $this->authorizeNiche($appointment);
        $appointment->load(['customer', 'service']);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment, Niche $niche): View
    {
        $this->authorizeNiche($appointment);

        return view('appointments.edit', $this->formData($niche, $appointment));
    }

    public function update(AppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->authorizeNiche($appointment);

        $appointment->update($this->payload($request));

        return redirect()
            ->route('appointments.show', $appointment)
            ->with('status', 'Appointment updated.');
    }

    public function complete(Appointment $appointment): RedirectResponse
    {
        $this->authorizeNiche($appointment);

        $appointment->update(['status' => 'completed']);

        return back()->with('status', 'Appointment marked completed.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $this->authorizeNiche($appointment);

        $appointment->delete();

        return redirect()->route('appointments.index')->with('status', 'Appointment deleted.');
    }

    /**
     * Shared data for the create/edit forms.
     *
     * @return array<string, mixed>
     */
    protected function formData(Niche $niche, Appointment $appointment): array
    {
        return [
            'appointment' => $appointment,
            'customers' => Customer::where('niche', $niche->key())->orderBy('name')->get(),
            'services' => Service::where('niche', $niche->key())->where('active', true)->orderBy('name')->get(),
            'statuses' => Appointment::STATUSES,
        ];
    }

    /**
     * Build the persisted attributes, defaulting price from the service.
     *
     * @return array<string, mixed>
     */
    protected function payload(AppointmentRequest $request): array
    {
        $data = $request->validated();

        if (($data['price'] ?? null) === null && ! empty($data['service_id'])) {
            $data['price'] = optional(Service::find($data['service_id']))->default_price;
        }

        return $data;
    }

    protected function authorizeNiche(Appointment $appointment): void
    {
        abort_unless($appointment->niche === app(Niche::class)->key(), 404);
    }
}
