<?php

namespace App\Http\Requests;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(Admin::class)->ignore($this->user()->id)],
            'phone' => ['required', 'regex:/^07[0-24678]\d{6}$/']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Полето е задолжително',
            'name.string' => 'Внеси име!',
            'name.max' => 'Името неможе да содржи повеќе од :max карактери.',
            'email.required' => 'Полето е задолжително.',
            'email.string' => 'Внеси е-маил!',
            'email.lowercase' => 'Внеси валидна е-маил адреса.',
            'email.email' => 'Внеси валидна е-маил адреса.',
            'email.max' => 'Е-маилот неможе да содржи повеќе од :max карактери.',
            'email.unique' => 'Адресата веќе постои.',
            'phone.required' => 'Полето е задолжително.',
            'phone.regex' => 'Валиден формат: 075500300.',
        ];
    }
}
