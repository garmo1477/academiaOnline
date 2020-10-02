<?php

namespace App\Models;

use App\Helpers\Currency;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Course
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $picture
 * @property string $description
 * @property float $price
 * @property int $featured
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Course whereUserId($value)
 * @mixin \Eloquent
 */
class Course extends Model
{
    use Hashidable;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'picture',
        'price',
        'featured',
        'status'
    ];
    const PUBLISHED = 1;
    const PENDING = 2;
    const REJECTED = 3;

    const prices = [
        '9.99' => '9.99€',
        '12.99' => '12.99€',
        '19.99' => '19.99€',
        '29.99' => '29.99€',
        '49.99' => '49.99€'
    ];

    protected $appends = [
        //cuando accedamos a los datos de un curso vamos a obtener toda la información. Añadimos aqui el método getFormattedPriceAttribute, poniendo en minuscula y solo las palabras del medio y con un guión separandolas
        "rating", 'formatted_price'
    ];

    protected static function boot()
    {
        parent::boot();
        // si la app no se está ejecutando desde la consola (por si estamos ejecutando seeds), salvamos el id de usuario identificado
        if (!app()->runningInConsole()) {
            self::saving(function($table){
                $table->user_id = auth()->id();
            });
        }
    }

    public function imagePath(){
        return sprintf('%s/%s', '/storage/courses', $this->picture);
    }

    public function categories()
    {
        //relación de muchos a muchos
        return $this->belongsToMany(Category::class);
    }

    public function students()
    {
        //definimos la relación y indicamos la tabla pivot entre estudiantes y curso
        //podremos buscar a los estudiantes que pertenecem a un curso
        return $this->belongsToMany(User::class, 'course_student');
    }

    public function teacher()
    {
        //relación pertenece a, accedemos al profesor de este curso y a todos los cursos de este profesor
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviews()
    {
        //un curso puede tener muchas valoraciones
        return $this->hasMany(Review::class);
    }

    public function units(){
        return $this->hasMany(Unit::class)->orderBy('order', 'asc');
    }

    public function getRatingAttribute()
    {
        //avg = average, saca una media de todas las valoraciones de un curso. Coge los datos de la columna stars de la tabla reviews
        return $this->reviews->avg('stars');
    }

    public function getFormattedPriceAttribute()
    {
        //método para formatear total precio
        return Currency::formatCurrency($this->price);
    }


    public function totalVideoUnits()
    {
        //llamamos al método units de arriba y así sacamos el numero total de unidades de tipo video, o zip
        return $this->units->where('unit_type', Unit::VIDEO)->count();
    }
    public function totalFileUnits()
    {
        return $this->units->where('unit_type', Unit::ZIP)->count();
    }
    public function totalTime()
    {
        $minutes = $this->units->where('unit_type', Unit::VIDEO)->sum('unit_time');
        return gmdate('H:i', $minutes * 60);
    }

    public function scopeFiltered(Builder $builder)
    {
        //cargamos los datos del profesor de un curso en concreto
        $builder->with('teacher');
        $builder->withCount('students'); //contador de estudiantes
        //donde el estado del curso es aprobado
        $builder->where('status', Course::PUBLISHED);
        //y si tenemos esta sesion
        if (session()->has('search[courses]')){
            $builder->where('title', 'LIKE', '%' . session('search[courses]') . '%');
        }
        return $builder->paginate();

    }

    public function scopeForTeacher(Builder $builder)
    {
        return $builder
            ->with('students')
            ->where('user_id', auth()->id())
            ->paginate();
    }

}