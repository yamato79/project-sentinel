<?php

namespace App\Http\Requests\Stack;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateStackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $stack = $this->route('stack');

        if ($stack->name != $this->name) {
            $this->merge([
                'slug' => Str::slug($this->name) . "-" . Str::random(6),
            ]);

            return;
        }

        $this->merge([
            'slug' => $stack->slug,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $stack = $this->route('stack');

        return [
            'name' => 'required|string|min:1|max:255',
            'slug' => "required|string|min:1|max:255|unique:stacks,slug,{$stack->getKey()},stack_id",
            'description' => 'required|string|min:1|max:255',
        ];
    }
}
