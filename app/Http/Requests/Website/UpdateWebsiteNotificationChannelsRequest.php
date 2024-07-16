<?php

namespace App\Http\Requests\Website;

use App\Models\NotificationChannelDriver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateWebsiteNotificationChannelsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('website'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [];

        foreach($this->notification_channels as $notificationChannelDriverId => $data) {
            $notificationChannelDriver = NotificationChannelDriver::findOrFail($notificationChannelDriverId);

            foreach($notificationChannelDriver->validator_rules as $field => $fieldRules) {
                $rules["notification_channels.{$notificationChannelDriverId}.field_values.{$field}"] = $fieldRules;
                $rules["notification_channels.{$notificationChannelDriverId}.is_active"] = 'required|boolean';
            }
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $messages = [];

        foreach($this->notification_channels as $notificationChannelDriverId => $data) {
            $notificationChannelDriver = NotificationChannelDriver::findOrFail($notificationChannelDriverId);

            foreach($notificationChannelDriver->validator_messages as $field => $fieldMessage) {
                $messages["notification_channels.{$notificationChannelDriverId}.field_values.{$field}"] = $fieldMessage;
            }
        }

        return $messages;
    }
}
