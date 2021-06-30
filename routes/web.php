<?php

use App\Helpers\RouteResource;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@index')->name('welcome');

Auth::routes();

Route::post(
    //la misma ruta que creamos en el webhook en la cuenta de Stripe
    'stripe/webhook',
    'StripeWebHookController@handleWebhook'
);

Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {
    Route::get('/', 'CourseController@index')->name('index');
    Route::post('/search', 'CourseController@search')->name('search');
    Route::get('/{course}', 'CourseController@show')->name('show');
    Route::get('/{course}/learn', 'CourseController@learn')
        ->name('learn')->middleware('can_access_to_course');
    Route::get('/{course}/review', 'CourseController@createReview')
        ->name('reviews.create');
    Route::post('/{course}/review', 'CourseController@storeReview')
        ->name('reviews.store');  
        
    Route::get('/category/{category}', 'CourseController@byCategory')->name('category');
    
    Route::group(['prefix' => '{course}/topics', 'as' => 'topics.', 'middleware' => ['can_access_to_course']], function (){
        Route::get('/', 'TopicController@index')->name('index');
        Route::get('/json', 'TopicController@json')->name('json');
        Route::post('/', 'TopicController@store')->name('store');
    });
   
});

//añadimos el middleware teacher, hay que poner el mismo nombre que llamamos en el Kernel.php
Route::group(['prefix' => 'teacher', 'as' => 'teacher.', 'middleware' => ['teacher']], function () {
    /**
     * Rutas para los cursos del profesor
     */
    Route::get('/', 'TeacherController@index')
        ->name('index');
        
    
    /**
     * COURSE ROUTES
     */
    (new RouteResource([
        "controller" => "TeacherController",
        "path" => "courses",
        "routes" => ["index", "create", "store", "edit", "update"]
    ]))->generator();

    /**
     * UNIT ROUTES
     */
    (new RouteResource([
        "controller" => "TeacherController",
        "path" => "units",
        "routes" => ["index", "create", "store", "edit", "update", "destroy"]
    ]))->generator();

    /**
     * COUPONS
     */
    (new RouteResource([
        "controller" => "TeacherController",
        "path" => "coupons",
        "routes" => ["index", "create", "store", "edit", "update", "destroy"]
    ]))->generator();

    (new RouteResource([
        "controller" => "TeacherController",
        "path" => "profits",
        "routes" => ["index"]
    ]))->generator();
    

    /**
     * Rutas para las unidades del profesor
     */
    Route::get('/units', 'TeacherController@units')
        ->name('units');
    Route::get('/units/create', 'TeacherController@createUnit')
        ->name('units.create');
    Route::post('/units/store', 'TeacherController@storeUnit')
        ->name('units.store');
    Route::get('/units/{unit}', 'TeacherController@editUnit')
        ->name('units.edit');
    Route::put('/units/{unit}', 'TeacherController@updateUnit')
        ->name('units.update');
    Route::delete('/units/{unit}', 'TeacherController@destroyUnit')
        ->name('units.destroy');

    /**
     * Cupones
     */
    Route::get('/coupons', 'TeacherController@coupons')
        ->name('coupons');
    Route::get('/coupons/create', 'TeacherController@createCoupon')
        ->name('coupons.create');
    Route::post('/coupons/store', 'TeacherController@storeCoupon')
        ->name('coupons.store');
    Route::get('/coupons/{coupon}', 'TeacherController@editCoupon')
        ->name('coupons.edit');
    Route::put('/coupons/{coupon}', 'TeacherController@updateCoupon')
        ->name('coupons.update');
    Route::delete('/coupons/{coupon}', 'TeacherController@destroyCoupon')
        ->name('coupons.destroy');

});
/**
 * Rutas para los estudiantes
 */

Route::group(['prefix' => 'student', 'as' => 'student.', 'middleware' => ['auth']], function () {
    Route::get('/', 'StudentController@index')
        ->name('index');
    
    Route::get('credit-card', 'BillingController@creditCardForm')
        ->name('billing.credit_card_form');
    Route::post('credit-card', 'BillingController@processCreditCardForm')
        ->name('billing.process_credit_card');

    Route::get('/courses', 'StudentController@courses')->name('courses');
    //rutas pedidos del estudiante
    Route::get('/orders', 'StudentController@orders')->name('orders');
    Route::get('/orders/{order}', 'StudentController@showOrder')
        ->name('orders.show');
    Route::get('/orders/{order}/download_invoice', 'StudentController@downloadInvoice')
        ->name('orders.download_invoice');

    Route::put('/wishlist/{course}/toggle', 'StudentController@toggleItemOnWishlist')
        ->name('wishlist.toggle');

    Route::get('/wishlists', 'StudentController@meWishlist')
        ->name('wishlist.me');
    Route::get('/wishlists/{id}/destroy', 'StudentController@destroyWishlistItem')
        ->name('wishlist.destroy');
});

/**
 * Rutas del carrito de compra
 */
Route::get('/add-course-to-cart/{course}','StudentController@addCourseToCart')
    ->name('add_course_to_cart');
Route::get('/cart', 'StudentController@showCart')
    ->name('cart');
Route::get('/remove-course-from-cart/{course}', 'StudentController@removeCourseFromCart')
    ->name('remove_course_from_cart');

Route::post('/apply-coupon', 'StudentController@applyCoupon')->name('apply_coupon');

/**
 * Rutas de finalización de pago en el carrito
 */
Route::group(["middleware" => ["auth"]], function () {
    Route::get('/checkout', 'CheckoutController@index')
        ->name('checkout_form');
    Route::post('/checkout', 'CheckoutController@processOrder')
        ->name('process_checkout');
});

