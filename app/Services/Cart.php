<?php
namespace App\Services;
use App\Helpers\Currency;
use App\Models\Coupon;
use App\Models\Course;
use Illuminate\Support\Collection;

/**
 * Class Cart
 * @package App\Classes
 */
class Cart{
    /**
     * Class Cart
     * @var Collection
     */
    protected Collection $cart;

    /**
     * Cart constructor
     */
    public function __construct()
    {
        if (session()->has('cart')) {
            $this->cart = session('cart');
        }else{
            $this->cart = new Collection;
        }
    }

    /**
     * Get cart contents
     * 
     */
    public function getContent(): Collection
    {
        //retornamos el contenido de ese carrito,para poder ver los articulos que el cliente ha seleccionado
        return $this->cart;
    }
    /**
     * Save the cart on session 
     */
    protected function save(): void{
        session()->put('cart', $this->cart);
        session()->save();
    }

    /**
     * Add course on cart
     * 
     * @param Course $course
     */
    public function addCourse(Course $course): void
    {
        //void quiere decir que no retornará nada pero hacemos un push para empujar un nuevo curso al carrito y luego actualizamos con save
        $this->cart->push($course);
        $this->save();
    }

    public function removeCourse(int $id): void
    {
        $this->cart = $this->cart->reject(function(Course $course) use ($id){
            return $course->id == $id;
        });
        $this->save();
    }

    /**
     *
     * calculates the total cost in the cart
     *
     * @param bool $formatted
     * @return mixed
     */
    public function totalAmount($formatted = true)
    {
        //Se devuelve la suma total del carrito de compras
        $amount = $this->cart->sum(function(Course $course){
            return $course->price;
        });
        // y aquí formatamos ese total
        if ($formatted) {
            return Currency::formatCurrency($amount);
        }
        return $amount;
    }

    public function taxes($formatted = true)
    {
        //cogemos el total del método anterior y ponemos a false, para que lo devuelva como float
        $total = $this->totalAmount(false);
        //si hay un valor aplicamos las tasas
        if ($total) {
            $total = ($total * env('TAXES')) / 100;
            //si se formatea
            if ($formatted) {
                return Currency::formatCurrency($total);
            }
            return $total;
        }
        //si no hay ninguna tasa devuelve 0
        return 0;
    }
    /**
     * Total products in cart
     * 
     * @return int
     */
    public function hasProducts(): int
    {
        //el total de productos en el carrito
        return $this->cart->count();
    }

    /**
     * Clear cart
     */

    public function clear():void
    {
        //creamos una nueva coleccion de carrito y la guardamos en la sesion
        $this->cart = new Collection;
        $this->save();
    }

    public function totalAmountWithDiscount($formatted = true)
    {
        $amount = $this->totalAmount(false);
        $withDiscount = $amount;
        if (session()->has('coupon')) {
            $coupon = Coupon::available(session('coupon'))->first();
            if (!$coupon) {
                return $amount;
            }
            //p0luck devuelve el array de id's del contenido del carrito
            $coursesInCart = $this->getContent()->pluck('id');
            if ($coursesInCart) {
                //cursos que están vinculados al cupón en la base de datos
                //con esto revisamos la columnas couse_id de la tabla coupon_course
                $coursesForApply = $coupon->courses()->whereIn('id', $coursesInCart);
                //id de los cursos vinculados en base de datos para aplicar el cupón
                $idCourses = $coursesForApply->pluck('id')->toArray();

                //si no hay cursos compatible con el descuento
                if (!count($idCourses)) {
                    $this->removeCoupon();
                    session()->flash('message', ['danger' => __('El cupón no se puede aplicar')]);
                    return $amount;
                }
                //hará la suma de los cursos sin descuento
                $priceCourses = $coursesForApply->sum('price');

                //comprobar el tipo de descuento y aplicarlo
                
                if ($coupon->discount_type === Coupon::PERCENT) {
                    //el 2 es para que devuelva con coma flotante
                    $discount = round($priceCourses -($priceCourses *((100 - $coupon->discount) / 100)), 2);
                    //discount es el valor del descuento que el profesor ha añadido al cupón en el formulario de creación de cupón
                    $withDiscount = $amount - $discount;
                }

                if ($coupon->discount_type === Coupon::PRICE) {
                    $withDiscount = $amount - $coupon->discount;
                }
            }else{
                $this->removeCoupon();
                return $amount;
            }
        }
        if ($formatted) {
            return Currency::formatCurrency($withDiscount);
        }
        return $withDiscount;
    }
     
    protected function removeCoupon(): void
    {
        session()->remove('coupon')    ;
        session()->save();
    }
}