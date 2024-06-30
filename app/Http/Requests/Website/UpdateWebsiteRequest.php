<?php

namespace App\Http\Requests\Website;

use App\Models\WebsiteStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateWebsiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('website'));
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $website = $this->route('website');
        $websiteStatusId = ($this->is_monitor_active ? $website->website_status_id : WebsiteStatus::PAUSED);

        if ($website->website_status_id == WebsiteStatus::PAUSED && $this->is_monitor_active) {
            $websiteStatusId = WebsiteStatus::DEFAULT;
        }

        $this->merge([
            'website_status_id' => $websiteStatusId,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $website = $this->route('website');

        return [
            'name' => 'required|string|min:1|max:255',
            'address' => 'required|url:http,https|min:1|max:255',
            'website_status_id' => 'required|integer|exists:website_statuses,website_status_id',
            'monitor_location_ids' => 'required|array',
            'monitor_location_ids.*' => 'required|integer|exists:monitor_locations,monitor_location_id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'monitor_location_ids.required' => 'Please select at least one monitor location.',
        ];
    }
}
