<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Log;

class BoggleFormRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        for($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 4; $j++) {
                $rules["c".$i.$j] = 'required|alpha|size:1';
            }
        }
        return $rules;
    }
}
