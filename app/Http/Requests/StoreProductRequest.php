<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // $this->merge([
        //     'user_id' => $this->user()->id,
        // ]);
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
