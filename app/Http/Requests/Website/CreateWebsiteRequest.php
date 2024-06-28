<?php

namespace App\Http\Requests\Website;

use App\Models\Website;
use App\Models\WebsiteStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateWebsiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', new Website());
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'website_status_id' => WebsiteStatus::DEFAULT,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:1|max:255',
            'address' => 'required|url:http,https|min:1|max:255',
            'website_status_id' => 'required|integer|exists:website_statuses,website_status_id',
        ];
    }
}
