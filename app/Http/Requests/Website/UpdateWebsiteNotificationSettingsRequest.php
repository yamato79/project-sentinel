<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateWebsiteNotificationSettingsRequest extends FormRequest
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
        $notificationTypeIds = collect($this->notification_type_ids)
            ->reduce(function ($carry, $include, $notificationTypeId) {
                if ($include) $carry[] = $notificationTypeId;
                return $carry;
            }, []);

        $this->merge([
            'notification_type_ids' => $notificationTypeIds,
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
            'notification_type_ids' => 'array',
            'notification_type_ids.*' => 'required|integer|exists:notification_types,notification_type_id',
        ];
    }
}
