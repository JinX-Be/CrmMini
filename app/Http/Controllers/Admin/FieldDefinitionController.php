<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FieldDefinitionRequest;
use App\Models\CustomFieldDefinition;
use App\Support\Niche;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FieldDefinitionController extends Controller
{
    public function index(Niche $niche): View
    {
        $fields = CustomFieldDefinition::query()
            ->where('niche', $niche->key())
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('settings.fields.index', compact('fields'));
    }

    public function create(): View
    {
        $field = new CustomFieldDefinition(['type' => 'text', 'sort_order' => 0]);

        return view('settings.fields.create', compact('field'));
    }

    public function store(FieldDefinitionRequest $request, Niche $niche): RedirectResponse
    {
        CustomFieldDefinition::create($request->validated() + ['niche' => $niche->key()]);

        return redirect()
            ->route('fields.index')
            ->with('status', 'Field added.');
    }

    public function edit(CustomFieldDefinition $field): View
    {
        $this->authorizeNiche($field);

        return view('settings.fields.edit', compact('field'));
    }

    public function update(FieldDefinitionRequest $request, CustomFieldDefinition $field): RedirectResponse
    {
        $this->authorizeNiche($field);

        // Key is immutable; never update it.
        $data = collect($request->validated())->except('key')->all();
        $field->update($data);

        return redirect()
            ->route('fields.index')
            ->with('status', 'Field updated.');
    }

    public function destroy(CustomFieldDefinition $field): RedirectResponse
    {
        $this->authorizeNiche($field);

        $field->delete();

        return redirect()
            ->route('fields.index')
            ->with('status', 'Field removed. Existing customer data for this field is kept.');
    }

    protected function authorizeNiche(CustomFieldDefinition $field): void
    {
        abort_unless($field->niche === app(Niche::class)->key(), 404);
    }
}
