<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class NovelUpdateRequest extends Request
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
        return [
            'name' => 'required|max:255',
            'author_id' => 'required|int',
            'type' => 'required',
            'biquge_url' => 'required|unique:novel,biquge_url,'.$this->get('id').'|max:255',
            'chapter_num' => 'int'
        ];
    }
}
