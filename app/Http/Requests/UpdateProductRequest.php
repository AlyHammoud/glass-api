<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // $product = $this->route('products');
        // if ($this->user()->id !== $product->user_id) {
        //     return false;
        // }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|max:1000',
            'titleArabic' => 'max:1000',
            'briefDetails' => 'required|max:2000',
            'briefDetailsArabic' => 'max:2000',
            'service_id' => 'required|exists:services,id',
            'imagesArabic' => '',
            'imagesEnglish' =>  '',
            'cover' => 'required',
            'slug' => '',
            'fullDetails' => 'required',
            'fullDetailsArabic' => ''
        ];
    }
}
