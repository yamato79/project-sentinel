<?php

namespace App\Http\Requests\Stack;

use Illuminate\Foundation\Http\FormRequest;

class LeaveStackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->route('stack')
            ->users()
            ->where('pivot_stacks_users.user_id', auth()->user()->getKey())
            ->whereNotNull('pivot_stacks_users.joined_at')
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
