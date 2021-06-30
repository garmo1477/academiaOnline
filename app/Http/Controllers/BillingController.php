<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class BillingController extends Controller
{
    public function creditCardForm()
    {
        return view('student.credit_card_form');
    }

    public function processCreditCardForm()
    {
        //reglas de validación
        $this->validate(request(),[
            'card_number' => 'required',
            'card_exp_year' => 'required',
            'card_exp_month' => 'required',
            'cvc' => 'required',
        ]);
        try {
            DB::beginTransaction();
            //se require esta linea para hacer lo q viene después, esto coge la clave secreta de stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));
            if (! auth()->user()->hasPaymentMethod()) {
                //si el cliente no tiene método de pago, creamos un cliente de stripe con ese usuario
                auth()->user()->createAsStripeCustomer();
            }

            //creamos un método de pago para ese cliente creado
            $paymentMethod = PaymentMethod::create([
                'type' => 'card',
                'card' => [
                    'number' => request('card_number'),
                    'exp_month' => request('card_exp_month'),
                    'exp_year' => request('card_exp_year'),
                    'cvc' => request('cvc')
                ]
            ]);

            //actualizamos el método de pago por defecto del usuario en nuestra app
            auth()->user()->updateDefaultPaymentMethod($paymentMethod->id);
            //guardamos el usuario
            auth()->user()->save();

            DB::commit();

            return back()->with(
                'message',
                ['success', __('Tarjeta actualizada correctamente')]
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return back()->with('message', ['danger', $exception->getMessage()]);
        }
    }
}
