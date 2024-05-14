<?php

namespace App\Http\Requests\Brand;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'describe' => 'required|string',
            'tags' => 'nullable|string|regex:/^(#\p{L}+(,\s*#\p{L}+\s*)*)?$/u',
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
            'category' => 'required',
            'category.*' =>
            function ($attribute, $value, $fail) {
                if (!Category::where('id', $value)->exists()) {
                    $fail('Внесовте невалидна категорија.');
                }
            },
            'status' => 'required|exists:statuses,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Внесете име.',
            'describe.required' => 'Внесете опис.',
            'tags.regex' => 'Валиден формат: #ново, #vintage, #палта ..',
            'images.required' => 'Прикачете слика/и.',
            'images.max' => 'Максималниот број на прикачени слики е 10.',
            'images.*.image' => 'Прикачете слика/и.',
            'images.*.mimes' => 'Сликата мора да биде од тип: jpeg, png, jpg, gif.',
            'images.*.max' => 'Максимална големина на слика: 4MB',
            'category.required' => 'Одбери категорија.',
            'status.required' => 'Одбери статус',
            'status.exists' => 'Невалиден статус'
        ];
    }
}
