<?php

namespace App\Listeners;

use App\Events\CourseAddedToWishlist;
use App\Mail\StudentWishCourse;
use Mail;

class SendCourseWishedEmailToTeacher
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CourseAddedToWishlist  $event
     * @return void
     */
    public function handle(CourseAddedToWishlist $event)
    {
        Mail::to($event->wishlist->course->teacher->email)
            ->send(new StudentWishCourse($event->wishlist));
    }
}
