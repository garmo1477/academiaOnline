<?php

namespace App\Mail;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeacherNewSale extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    public $teacher;

    /**
     * @var User
     */
    public $student;

     /**
     * @var Course
     */
    public $course;

    /**
     * Create a new message instance.
     *
     * @param User $teacher
     * @param User $student
     * @param Course $course
     */
    public function __construct(User $teacher, User $student, Course $course)
    {
        $this->teacher = $teacher;
        $this->student = $student;
        $this->course = $course;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('¡Curso Vendido! - ' . config('app.name'))
            ->markdown('emails.teachers.new_sale');
    }
}
