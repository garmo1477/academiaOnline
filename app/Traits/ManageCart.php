<?php
namespace App\Traits;

use App\Models\Coupon;
use App\Models\Course;
use App\Services\Cart;

trait ManageCart{
    public function showCart()
    {
        return view('learning.cart');
    }

    public function addCourseToCart(Course $course)
    {
        //creamos una estancia de cart
        $cart = new Cart;
        //llamamos al método addCourse donde le pasamos el curso
        $cart->addCourse($course);
        session()->flash('message', ['success', __('Curso añadido al carrito correctamente')]);
        //y al final redireccionamos al carrito ya con el producto añadido
        return redirect(route('cart'));
    }

    public function removeCourseFromCart(Course $course)
    {
        //obtenemos el carrito de compras
        $cart = new Cart;
        //llamamos el método removeCourse
        $cart->removeCourse($course->id);
        session()->flash('message', ['success', __('Curso eliminado del carrito correctamente')]);
        return back();
    }

    public function applyCoupon()
    {
        session()->remove('coupon');
        session()->save();
        
        $code = request('coupon');
        //llamamos el scope Available para saber si el cupón existe
        $coupon = Coupon::available($code)->first();
        if (!$coupon) {
            session()->flash('message', ['danger', __('El cupón que has introducido no existe')]);
            return back();
        }
        $cart = new Cart;
        //obtener los cursos del carrito
        $cousesInCart = $cart->getContent()->pluck('id');
        //hacemos esto para saber si los cursos q están en el carrito pueden aplicar el cupón q se está utilizando
        $totalCourses = $coupon->courses()->whereIn('id', $cousesInCart)->count();
        
        if ($totalCourses) {
            session()->put('coupon', $code);
            session()->save();
            session()->flash('message', ['success', __('El cupón se ha aplicado correctamente')]);
            return back();
        }
        session()->flash('message', ['danger', __('El cupón no se puede aplicar')]);
        return back();

    }
}