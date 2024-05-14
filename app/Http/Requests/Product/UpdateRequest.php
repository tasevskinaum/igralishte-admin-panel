<?php

namespace App\Http\Requests\Product;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Discount;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantityXS' => 'required|integer|min:0',
            'quantityS' => 'required|integer|min:0',
            'quantityM' => 'required|integer|min:0',
            'quantityL' => 'required|integer|min:0',
            'quantityXL' => 'required|integer|min:0',
            'size_advice' => 'required|string',
            'colors' => 'required|array',
            'colors.*' => [function ($attribute, $value, $fail) {
                $colorExists = Color::find($value);
                if (!$colorExists) {
                    $fail("Избравте непостоечка боја.");
                }
            }],
            'maintenance_guidelines' => 'required|string',
            'tags' => 'nullable|string|regex:/^(#\p{L}+(,\s*#\p{L}+\s*)*)?$/u',
            'old-images.*' => [
                function ($attribute, $value, $fail) {
                    $exists = $this->product->images()->where('id', $value)->exists();
                    if (!$exists) {
                        $fail('Не можете да избришете слика која не припаѓа на овој продукт.');
                    }
                },
            ],
            'images' => 'array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
            'category' => 'required|exists:categories,id',
            'brand' => ['required', function ($attribute, $value, $fail) {
                $category = $this->input('category');
                $brand = Brand::where('id', $value)
                    ->whereHas('categories', function ($query) use ($category) {
                        $query->where('categories.id', $category);
                    })
                    ->exists();

                if (!$brand) {
                    $fail('Избраниот бренд не припаѓа на избраната категорија.');
                }
            }],
            'discount' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value)) {
                    $discount = Discount::find($value);

                    if (!$discount || $discount->status->name !== 'Активен') {
                        $fail('Попустот е неактивен или повеќе не постои.');
                    }
                }
            }],
            'status' => 'required|exists:statuses,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Внесете име.',
            'description.required' => 'Внесете опис.',
            'price.required' => 'Внеси цена.',
            'price.numeric' => 'Внеси цена во денари.',
            'price.min' => 'Не можете да внесете сума пониска од 0 денари.',
            'quantityXS.required' => 'Внесете количина за XS',
            'quantityXS.integer' => 'Количината мора да биде број',
            'quantityXS.min' => 'Не можете да внесте количина помала од :min.',
            'quantityS.required' => 'Внесете количина за S',
            'quantityS.integer' => 'Количината мора да биде број',
            'quantityS.min' => 'Не можете да внесте количина помала од :min.',
            'quantityM.required' => 'Внесете количина за M',
            'quantityM.integer' => 'Количината мора да биде број',
            'quantityM.min' => 'Не можете да внесте количина помала од :min.',
            'quantityL.required' => 'Внесете количина за L',
            'quantityL.integer' => 'Количината мора да биде број',
            'quantityL.min' => 'Не можете да внесте количина помала од :min.',
            'quantityXL.required' => 'Внесете количина за XL',
            'quantityXL.integer' => 'Количината мора да биде број',
            'quantityXL.min' => 'Не можете да внесте количина помала од :min.',
            'size_advice.required' => 'Внеси совет за величина.',
            'colors.required' => 'Избери една или повеќе бои.',
            'maintenance_guidelines.required' => 'Внеси насоки за одржување на производот.',
            'tags.regex' => 'Валиден формат: #ново, #vintage, #палта ..',
            'images.max' => 'Максималниот број на прикачени слики е 10.',
            'images.*.image' => 'Прикачете слика/и.',
            'images.*.mimes' => 'Сликата мора да биде од тип: jpeg, png, jpg, gif.',
            'images.*.max' => 'Максимална големина на слика: 4MB',
            'category.required' => 'Одбери категорија.',
            'category.exists' => 'Невалидна категорија.',
            'brand.required' => 'Избери бренд.',
            'status.required' => 'Одбери статус.',
            'status.exists' => 'Невалиден статус.'
        ];
    }
}
