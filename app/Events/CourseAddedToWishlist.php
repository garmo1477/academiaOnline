<?php

namespace App\Events;

use App\Models\Wishlist;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourseAddedToWishlist
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Wishlist
     */
    public $wishlist;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct(Wishlist $wishlist)
    {
        $this->wishlist = $wishlist;
        //creamos un mailable con php artisan make:mail StudentWishCourse --markdown=emails.teachers.student_wish_course que también crea ya el archivo del email
    }

    
}
