<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use App\Support\Niche;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $niche = app(Niche::class)->key();

        return [
            'customer_id' => [
                'required',
                Rule::exists('customers', 'id')->where('niche', $niche),
            ],
            'service_id' => [
                'nullable',
                Rule::exists('services', 'id')->where('niche', $niche),
            ],
            'starts_at' => ['required', 'date'],
            'duration_minutes' => ['required', 'integer', 'min:5'],
            'status' => ['required', Rule::in(Appointment::STATUSES)],
            'price' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
