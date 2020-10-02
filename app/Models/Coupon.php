<?php

namespace App\Models;

use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Coupon
 *
 * @property int $id
 * @property int $user_id
 * @property string $code
 * @property string $description
 * @property string $discount_type
 * @property int $discount
 * @property int $enabled
 * @property string|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereUserId($value)
 * @mixin \Eloquent
 */
class Coupon extends Model
{
    //para utilizar borrados lógicos, debemos añadir Softdeletes
    //utilizamos hashidale para que por que el modelo sepa que tiene que usar hash en vez de id
    use SoftDeletes, Hashidable;
    const PERCENT = 'PERCENT';
    const PRICE = 'PRICE';

    protected $fillable = [
        'user_id', 'code', 'discount_type', 'discount',
        'description', 'enabled', 'expires_at'
    ];

    //aquí diremos a eloquent que lo q pongamos dentro será de tipo fecha. lo convertirá en un campo de tipo carbon
    protected $dates = [
        'expires_at'
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            //cuando se esté guardando se ugarde el id de usuario autenticado en el user_id
            self::saving(function ($table){
                $table->user_id = auth()->id();
            });
        }
       
    } 
    public function courses()
    {   //un cupón puede estar vinculado a muchos cursos y un curso a muchos cupones
        return $this->belongsToMany(Course::class);
    }

    public function scopeForTeacher(Builder $builder)
    {
        //para poder acceder a todos los cupones de un profesor, listados
        return $builder
            ->where('user_id', auth()->id())
            ->paginate();
    }

    public function scopeAvailable(Builder $builder, string $code)
    {
        //condiciones para saber si un cupón es funcional o no
        return $builder
            ->where('enabled', true)
            ->where('code', $code)
            //now() es la fecha actual
            ->where('expires_at', '>=', now())
            //o donde la columna expires_at es null
            ->orWhereNull('expires_at');
    }

    public static function discountTypes()
    {
        return [
            self::PERCENT => __('Porcentaje'),
            self::PRICE => __('Fijo'),
        ];
    }
}
