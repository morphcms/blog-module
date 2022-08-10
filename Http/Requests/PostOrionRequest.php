<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Blog\Utils\Table;
use Orion\Http\Requests\Request;

class PostOrionRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:180',
            ],
            'slug' => [
                'required',
                'max:180',
                Rule::unique(Table::posts(), 'slug')->ignoreModel($this->model()),
            ],
            'summary' => [
                'nullable',
                'max:250',
            ],
        ];
    }
}
