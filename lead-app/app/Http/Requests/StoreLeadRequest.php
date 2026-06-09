<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorisation is handled by the EnsureApiToken middleware.
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:50'],
            'company'           => ['nullable', 'string', 'max:255'],
            'message'           => ['nullable', 'string'],
            'company_domain'    => ['nullable', 'string', 'max:255'],
            'company_size'      => ['nullable', 'integer'],
            'company_industry'  => ['nullable', 'string', 'max:255'],
            'enrichment_status' => ['nullable', 'in:pending,enriched,failed'],
            'source'            => ['nullable', 'string', 'max:100'],
            'raw_payload'       => ['nullable', 'array'],
        ];
    }
}
