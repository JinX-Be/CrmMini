<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Support\Niche;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request, Niche $niche): View
    {
        $q = trim((string) $request->query('q', ''));

        $customers = Customer::query()
            ->where('niche', $niche->key())
            ->when($q !== '', function ($query) use ($q) {
                $term = '%'.strtolower($q).'%';
                $query->where(function ($w) use ($term) {
                    $w->whereRaw('LOWER(name) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(phone) LIKE ?', [$term]);
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('customers.index', compact('customers', 'q'));
    }

    public function create(): View
    {
        $customer = new Customer();

        return view('customers.create', compact('customer'));
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $customer = Customer::create($request->customerData());

        return redirect()
            ->route('customers.show', $customer)
            ->with('status', 'Customer created.');
    }

    public function show(Customer $customer): View
    {
        $this->authorizeNiche($customer);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        $this->authorizeNiche($customer);

        return view('customers.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $this->authorizeNiche($customer);

        $customer->update($request->customerData());

        return redirect()
            ->route('customers.show', $customer)
            ->with('status', 'Customer updated.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $this->authorizeNiche($customer);

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('status', 'Customer deleted.');
    }

    /**
     * Ensure a customer belongs to the active niche (prevents cross-niche access
     * once the DB is shared across niches in Phase 5).
     */
    protected function authorizeNiche(Customer $customer): void
    {
        abort_unless($customer->niche === app(Niche::class)->key(), 404);
    }
}
