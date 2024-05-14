<?php

namespace App\Http\Requests\Discount;

use App\Models\Product;
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
            'discount' => 'required|integer|min:0|max:100',
            'category' => 'required|exists:discount_categories,id',
            'set_discount_on' => [
                'nullable',
                'string',
                'regex:/^#\d+(?:,\s*#\d+)*$/',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $ids = explode(',', str_replace('#', '', $value));

                        $existingIds = Product::whereIn('id', $ids)->pluck('id')->toArray();

                        $nonExistingIds = array_diff($ids, $existingIds);

                        if (!empty($nonExistingIds)) {
                            $fail('Производите ' . implode(', ', $nonExistingIds) . ' не постојат!');
                        }
                    }
                }
            ],
            'status' => 'required|exists:statuses,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Внесете име / промо код.',
            'discount.required' => 'Внесете попуст.',
            'discount.integer' => 'Внесете вредност во проценти.',
            'discount.min' => 'Процентот на попуст неможе да биде помал од 0%.',
            'discount.max' => 'Процентот на попуст неможе да биде поголем од 100%.',
            'category.required' => 'Одбери категорија.',
            'category.exists' => 'Невалидна категорија',
            'set_discount_on.regex' => 'Валиден формат: #0, #44, #88..',
            'status.required' => 'Одбери статус',
            'status.exists' => 'Невалиден статус'
        ];
    }
}
