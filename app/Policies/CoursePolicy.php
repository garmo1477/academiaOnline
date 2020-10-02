<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     * Los métodos que definamos aquí se pondrán utilizar con la directiva can, para saber si un estudiante puede o no hacer algo. Debemos añadir este archivo a AuthServiceProvider en Providers.
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function purchaseCourse(User $user, Course $course)
    {
        //comparamos el id de usuario logeado con el user_id de los cursos, si se encuentra es profesor
        $isTeacher = $user->id === $course->user_id;
        //si ha comprado ese curso en concreto tiene que estar en la tabla course_student, si no devolverá false
        $coursePurchased = $course->students->contains($user->id);
        return !$isTeacher && !$coursePurchased;
        //si no ha comprado ya ese curso y no es profesor, entonces podrá comprar el curso, eso lo definimos llamando este méotodo en purchase_button.blade
    }
}
