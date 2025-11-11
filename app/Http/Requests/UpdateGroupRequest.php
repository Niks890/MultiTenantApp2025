<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGroupRequest extends FormRequest
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
        $groupId = $this->route('group') ? $this->route('group')->id : null;
        return [
            'group_name' => [
                'required',
                'min:6',
                'max:100',
                Rule::unique('m_groups', 'name')
                    ->where(fn ($query) => $query->where('delete_flg', 0))
                    ->ignore($groupId),
            ],
            'group_description' => 'nullable|max:100'
        ];
    }


    public function messages(): array
    {
        return [
            'group_name.required' => 'Vui lòng nhập tên nhóm cửa hiệu',
            'group_name.unique' => 'Tên nhóm cửa hiệu đã tồn tại',
            'group_name.min' => 'Tên nhóm cửa hiệu phải có ít nhất 6 ký tự',
            'group_name.max' => 'Tên nhóm cửa hiệu không được vượt quá 100 ký tự',
            'group_description.max' => 'Mô tả nhóm cửa hiệu không được vượt quá 100 ký tự',
        ];
    }
}
