<?php

namespace App\Http\Requests;

use ErrorHandler;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class RestaurantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->tokenCan('role:admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:60',
            'schedules' => 'required|array',
            'schedules.*.dayname' => 'required',
            'schedules.*.open' => 'required',
            'schedules.*.closed' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama belum diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'name.max' => 'Nama maksimal 60 karakter',
            'schedules.required' => 'Jadwal belum diisi',
            'schedules.array' => 'Jadwal harus berupa array'
        ];
    }

    protected function failedValidation(ValidationValidator $validator)
    {
        throw new HttpResponseException(response(ErrorHandler::errorResource($validator->errors()->all(), 400)));

    }
}
