<?php

namespace App\Http\Requests\Stack;

use App\Models\User;
use Closure;
use DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class AttachUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('stack'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                'exists:users,email',
                function (string $attribute, mixed $value, Closure $fail) {
                    $user = User::query()
                        ->where('email', $value)
                        ->first();

                    if (! $user) {
                        $fail('We couldn\'t find anybody with that email.');
                    }

                    if ($this->route('stack')->created_by_user_id == $user->getKey()) {
                        $fail('You can\'t invite the owner of the stack.');
                    }

                    $userInStack = DB::table('pivot_stacks_users')
                        ->where('user_id', $user->getKey())
                        ->where('stack_id', $this->route('stack')->getKey())
                        ->exists();

                    logger()->info('$userInStack', [
                        'user_id' => $user->getKey(),
                        'stack_id' => $this->route('stack')->getKey(),
                        'raw' => $userInStack,
                    ]);

                    if ($userInStack) {
                        $fail('That user is already within the stack.');
                    }
                },
            ],
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
            'email.exists' => 'We couldn\'t find anybody with that email.',
        ];
    }
}
