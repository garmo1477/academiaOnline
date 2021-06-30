<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Review;

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
        $course->load('units', 'students', 'reviews.author');        
        return view('learning.courses.show', compact('course'));
    }

    public function learn(Course $course)
    {
        $course->load('units');
        return view('learning.courses.learn', compact('course'));
    }

    public function createReview(Course $course)
    {
        return view('learning.courses.reviews.form', compact('course'));
    }

    public function storeReview(Course $course)
    {
        $reviewed = $course->reviews->contains('user_id', auth()->id());
        if($reviewed){
            return redirect(route('courses.learn', ['course' => $course]))->with('message', ['danger', __('No puedes valorar este curso porque ya lo has hecho')]);
        }
        
        //$coursePurchased = $course->students->contains('user_id', auth()->id());
         $this->validate(request(),[
                'review' => 'required|string|min:10',
                'stars' => 'required'
            ]);

            $review = Review::create([
                'user_id' => auth()->id(),
                'course_id' => $course->id,
                'stars' => (int) request('stars'),
                'review' => request('review'),
                'created_at' => now()
            ]);
        
        /*if($coursePurchased){
           
        }*/
        return redirect(route('courses.learn', ['course' => $course]))->with('message', ['success', __('Muchas gracias por valorar el curso')]);        
    }

    public function byCategory(Category $category)
    {
        $courses = Course::filtered($category);
        return view('learning.courses.by_category', compact('courses', 'category'));
    }
}
