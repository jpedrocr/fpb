<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GameRequest extends \Backpack\CRUD\app\Http\Requests\CrudRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'round_id' => 'required|number',
            'category_id' => 'required|number',
            'fpb_id' => 'required|number',
            'hometeam_id' => 'required|number',
            'outteam_id' => 'required|number',
            'number' => 'number'
            'schedule' => 'datetime'
            'home_result' => 'number'
            'out_result' => 'number'
            'status' => 'min:5|max:255'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
