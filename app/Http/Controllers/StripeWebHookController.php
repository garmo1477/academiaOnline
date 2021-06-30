<?php

namespace App\Http\Controllers;

use App\Jobs\SendTeacherSalesEmail;
use App\Mail\StudentNewOrder;
use App\Models\Order;
use Illuminate\Http\Response;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Log;
use Mail;

class StripeWebHookController extends WebhookController
{
    /**
     * WEBHOOK que se encarga de obtener un evento al hacer un pago correctamente
     * charge.succeeded
     * @param $payload
     * @return Response
     */
    public function handleChargeSucceeded($payload)
    {
        //webhook sirve para que una vez el cliente haya llevado a cabo el pago en la plataforma, hacemos una petición a stripe y stripe nos va a notificar. Hace la conexión de la plataforma con stripe
        try {
            //guardamos el id de stripe del usuario
            $invoice_id = $payload['data']['object']['invoice'];
            //el usuario que ha realizado el pedido
            $user = $this->getUserByStripeId($payload['data']['object']['customer']);
            if ($user) {
                //buscamos los pedidos de un usuarios, que tengan estado pendiente, por orden descendente y cogemos el primero (cogeremos entonces el último pedido que ha realizado el usu)
                $order = $user->orders()
                    ->where('status', Order::PENDING)
                    ->latest()
                    ->first();
                if ($order) {
                    //cargamos la linea de pedidos y el curso
                    $order->load('orderLines.course.teacher');
                    $order->update([
                        'invoice_id' => $invoice_id,
                        'status' => Order::SUCCESS
                    ]);

                    //cogemos los cursos que ha comprado el usuario, por id de curso
                    $coursesId = $order->orderLines()->pluck('course_id');
                    //Los logs en Laravel almacenan la información correspondiente a todos los errores (Excepciones) y/o eventos inesperados dentro de una aplicación para tener un registro de estas incidencias y así poder depurar mucho más fácil nuestro código.
                    Log::info(json_encode($coursesId));
                    //utilizamos el método courses_learning para vincular todos los cursos al usuario. attach = vincular
                    $user->courses_learning()->attach($coursesId);

                    //se generará un archivo de log en storage/logs con estos logs
                    Log::info(json_encode($user));
                    Log::info(json_encode($order));
                    Log::info('Pedido actualizado correctamente');
                    Mail::to($user->email)->send(new StudentNewOrder($user, $order));
                    
                    foreach ($order->orderLines as $order_line) {
                        //creamos un nuevo job, nueva tarea para cada email que tengamos que enviar
                        SendTeacherSalesEmail::dispatch(
                            $order_line->course->teacher,
                            $user,
                            $order_line->course
                        )->onQueue('emails');
                    }

                    return new Response('Webhook Handled: {handleChargeSucceeded}', 200);
                }
            }
        }catch (\Exception $exception) {
            Log::debug("Excepción Webhook {handleChargeSucceeded}: " . $exception->getMessage() . ", Line: " . $exception->getLine() . ', File: ' . $exception->getFile());
            //a este evento charge.succeeded, le llamamos en este método con handle antes y lo que viene después en camel case, en este caso ChargeSucceeded, se hace para todos los eventos del webhook
            return new Response('Webhook Handled with error: {hanbleChargeSucceeded}', 400);
        }
    }
}