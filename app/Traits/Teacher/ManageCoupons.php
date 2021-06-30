<?php
namespace App\Traits\Teacher;

use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use DB;

trait ManageCoupons
{
    public function coupons()
    {
        $coupons = Coupon::forTeacher();
        return view('teacher.coupons.index', compact('coupons'));
    }

    public function createCoupon()
    {
        $coupon = new Coupon;
        $title = __('Crear un nuevo cupón');
        $textButton = __('Dar de alta el cupón');
        $options = ['route' => ['teacher.coupons.store']];
        return view('teacher.coupons.create', compact('title', 'coupon', 'options', 'textButton'));
    }

    public function storeCoupon(CouponRequest $request)
    {
        //para guardar lo que se ha creado se hace un try catch
        try {
            DB::beginTransaction();
            //recibimos los valores introducidos en los inputs del form de crear cupón y guardamos en una variable
            $input = $this->couponInput();
            //creamos el cupón con los datos introducidos en el form de crear cupones
            $coupon = Coupon::create($input);
            //utilizamos el cupón con el método courses() que es la relación que creamos belongsToMany. usamos false para vincular la información
            $coupon->courses()->sync(request('courses'), false);         
            DB::commit();

            session()->flash('message', ['success', __('Cupón creado correctgamente')]);

            return redirect(route('teacher.coupons.edit', ['coupon' => $coupon]));
            
        } catch (\Throwable $exception) {
            DB::rollBack();
            session()->flash('message', ['danger', $exception->getMessage()]);
            return back();
        }
    }

    public function editCoupon(Coupon $coupon)
    {
        $coupon->load('courses');
        $title = __('Editar el cupón :coupon', ['coupon' => $coupon->code]);
        $textButton = __('Actualizar cupón');
        $options = ['route' => ['teacher.coupons.update', ['coupon' => $coupon]]];
        //decimos que es true porque queremos actualizar
        $update = true;
        return view('teacher.coupons.edit', compact(
            'title',
            'coupon',
            'textButton',
            'options',
            'update'
        ));
    }
    public function updateCoupon(CouponRequest $request, Coupon $coupon)
    {
        //para guardar lo que se ha editado se hace un try catch
        try {
            DB::beginTransaction();
            //recibimos el input
            $input = $this->couponInput();
            //rellenamos el cupón con los nuevos datos
            $coupon->fill($input)->save();     
            //y sincronizamos los cursos seleccionados para ese cupón      
            $coupon->courses()->sync(request('courses'));         
            DB::commit();

            session()->flash('message', ['success', __('Cupón actualizado correctamente')]);

            return redirect(route('teacher.coupons.edit', ['coupon' => $coupon]));
            
        } catch (\Throwable $exception) {
            DB::rollBack();
            session()->flash('message', ['danger', $exception->getMessage()]);
            return back();
        }
    }

    public function destroyCoupon(Coupon $coupon)
    {
        if (request()->ajax()) {
            $coupon->delete();
            session()->flash('message', ['success', __('El cupón :code ha sido eliminado con éxito', ['code' => $coupon->code])]);
        }
    }

    protected function couponInput(): array
    {
        return request()->only(
            'code',
            'description',
            'discount_type',
            'discount',
            'enabled',
            'expires_at');
    }
}
