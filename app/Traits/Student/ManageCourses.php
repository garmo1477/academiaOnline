<?php
namespace App\Traits\Student;

trait ManageCourses{
    public function courses()
    {
        //usamos el scope de User.php
        $courses = auth()->user()->purchasedCourses();
        return view('student.courses.index', compact('courses'));
    }
}