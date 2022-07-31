<?php
namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;


/**
 * @OA\Schema(
 *      title="User request",
 *      description="User request body data",
 *      type="object",
 *      required={"username", "email","first_name","last_name"}
 * )
 */
class UserRequest extends FormRequest
{
    

    public function authorize()
    {
        abort_if(Gate::denies('project_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }

    public function rules()
    {
        return [
            'username' => ['required'],
            'first_name' =>['required'],
            'last_name'=> ['required'],
            'email'=> ['required'],
        ];
    }
}