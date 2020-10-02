<?php

namespace App\Http\Requests;

use App\Models\Unit;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;


class UnitRequest extends FormRequest
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
        //comprobamos el método  si post 
        switch ($this->method()){
            case 'POST':{
                return[
                    //normas de validación para el formulario de creación de unidades
                    'title' => 'required|min:6|max:200',
                    //el contenido será requerido solo si es de tipo vídeo
                    'content' => 'required_if:unit_type,'.Unit::VIDEO,
                    'course_id' => [
                        'required',
                        //el id de curso es requerido y además tiene que existir en la tabla de cursos
                        Rule::exists('courses', 'id')
                    ],
                    'unit_type' => [
                        //requerido y tiene q estar en uno de los casos de unitTypes, o video o zip o section
                        'required',
                        Rule::in(Unit::unitTypes())
                    ],
                    'file' => 'required_if:unit_type,'.Unit::ZIP.'|file',
                    'unit_time' => 'required_if:unit_type,'.Unit::VIDEO,
                ];
            }
            case 'PUT':{
                return[
                    //normas de validación para el formulario de creación de unidades
                    'title' => 'required|min:6|max:200',
                    //el contenido será requerido solo si es de tipo vídeo
                    'content' => 'required_if:unit_type,'.Unit::VIDEO,
                    'course_id' => [
                        'required',
                        //el id de curso es requerido y además tiene que existir en la tabla de cursos
                        Rule::exists('courses', 'id')
                    ],
                    'unit_type' => [
                        //requerido y tiene q estar en uno de los casos de unitTypes, o video o zip o section
                        'required',
                        Rule::in(Unit::unitTypes())
                    ],
                    'file' => 'required_if:unit_type,'.Unit::ZIP.'|sometimes|file',
                    'unit_time' => 'required_if:unit_type,'.Unit::VIDEO,
                ];
            }
            default: {
                return [];
            }
        }
    }
}
