<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Support\Niche;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Niche $niche): View
    {
        $services = Service::query()
            ->where('niche', $niche->key())
            ->orderBy('name')
            ->get();

        return view('services.index', compact('services'));
    }

    public function create(): View
    {
        $service = new Service([
            'default_duration_minutes' => 60,
            'default_price' => 0,
            'active' => true,
        ]);

        return view('services.create', compact('service'));
    }

    public function store(ServiceRequest $request, Niche $niche): RedirectResponse
    {
        Service::create($request->validated() + ['niche' => $niche->key()]);

        return redirect()->route('services.index')->with('status', 'Service created.');
    }

    public function edit(Service $service): View
    {
        $this->authorizeNiche($service);

        return view('services.edit', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $this->authorizeNiche($service);

        $service->update($request->validated());

        return redirect()->route('services.index')->with('status', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->authorizeNiche($service);

        $service->delete();

        return redirect()->route('services.index')->with('status', 'Service deleted.');
    }

    protected function authorizeNiche(Service $service): void
    {
        abort_unless($service->niche === app(Niche::class)->key(), 404);
    }
}
