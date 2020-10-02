<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //cambiamos esto a true, para que autorice la validación del formulario de editar curso
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method()){
            case 'POST':{
                return [
                    'title' => 'required|min:5|unique:courses',
                    'categories' => 'required|array',
                    'description' => 'required|min:50',
                    'price' => 'required',
                    'picture' => 'required|image|mimes:jpg,jpeg,png'
                ];
                
            }
            case 'PUT':{
                return [
                    //para editar un campo unicos/unique tenemos que añadir el $this y lo q viene después, si no dirá que ya existe
                    'title' => 'required|min:5|unique:courses,title,' . $this->route('course')->id,
                    'categories' => 'required|array',
                    'description' => 'required|min:50',
                    'price' => 'required',
                    'picture' => 'required|sometimes|image|mimes:jpg,jpeg,png'
                ];
            }
            default:{
                return [];
            }
        }

        
    }
}
