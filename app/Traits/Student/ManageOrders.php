<?php
namespace App\Traits\Student;

use App\Models\Order;

trait ManageOrders{
    public function orders()
    {
        //usamos el scope de User.php
        $orders = auth()->user()->processedOrders();
        return view('student.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order
            ->load('orderLines.course', 'coupon')
            ->loadCount('orderLines');
        return view('student.orders.show', compact('order'));
    }

    public function downloadInvoice(Order $order)
    {
        //siempre es recomendable poner estas operaciones en un try catch
        try {            
            if ($order->user_id != auth()->id()) {
                session()->flash('message', ['danger', __('No tienes acceso a este recurso')]);
                return back();
            }
            //para modificar el diseño de la factura hay que ejecutar: php artisan vendor:publish, elegir la opción 15 y en la carpeta vendor/cashier elegir receipt
            return auth()->user()->downloadInvoice($order->invoice_id,[
                'vendor' => env('APP_NAME'),
                'product' => __('Compra de cursos'),
            ]);

        } catch (\Exception $exception) {
            session()->flash('message', ['danger', __('Ha ocurrido un error al descargar la factura')]);
            return back();
        }
    }
}