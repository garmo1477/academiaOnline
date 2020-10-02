<?php

namespace App\Http\Controllers;

use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {      
       $courses = Course::filtered();
       return view('learning.courses.index', compact('courses'));
    }

    public function search()
    {
        //eliminamos la session
        session()->remove('search[courses]');
        //comprobamos si el input del form de busqueda tiene valor
        if(request('search')){
            //establecemos la session con el valor del input que estamos enviando por el formulario
            session()->put('search[courses]', request('search'));
            //guardamos sesion
            session()->save();
        }
        return redirect(route('courses.index'));
    }

    public function show(Course $course)
    {
        //cargamos lo que queremos en load
        $course->load('units', 'students', 'reviews');        
        return view('learning.courses.show', compact('course'));
    }
}
