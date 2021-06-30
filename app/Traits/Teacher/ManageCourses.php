<?php

namespace App\Traits\Teacher;

use App\Helpers\Uploader;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Unit;
use DB;

//los traits son una forma de manejar herencias multiples en php, se añade traits a una clase, y esa clase va a convertir todos sus métodos y propiedades en parte de esa misma clase
trait ManageCourses{
    public function courses()
    {
        //usamos el scopeForTeacher del modelo Course
        $courses = Course::forTeacher();
        return view('teacher.courses.index', compact('courses'));
    }

    public function createCourse()
    {
        $course = new Course;
        $title = __('Crear un nuevo curso');
        $textButton = __('Dar de alta el curso');
        $options = ['route' => ['teacher.courses.store'], 'files' => true];
        return view('teacher.courses.create', compact('title', 'course', 'options', 'textButton'));
    }

    public function storeCourse(CourseRequest $request)
    {
        try {           
            DB::beginTransaction();
            $file = null;            
            if ($request->hasfile('picture')) {               
                $file = Uploader::uploadFile('picture', 'courses');
            }            
            //datos del curso que se está creando recogidos por el método courseInput de más abajo
            $course = Course::create($this->courseInput($file));     
            $course->categories()->sync(request('categories'));           
                 
            DB::commit();
            session()->flash('message', ['success', __('Curso creado satisfactoriamente')]);
            return redirect(route('teacher.courses.edit', ['course' => $course]));

        } catch (\Throwable $exception) {
            
            session()->flash('message', ['danger', $exception->getMessage()]);
            return back();
        }
    }

    public function editCourse(Course $course)
    {
        $course->load('units');
        $title = __('Editar el curso :course', ['course' => $course->title]);
        $textButton = __('Actualizar curso');
        $options = ['route' => ['teacher.courses.update', ['course' => $course]], 'files' => true];
        $update = true;
        return view('teacher.courses.edit', compact('title', 'course', 'options', 'textButton', 'update'));
    }

    public function updateCourse(CourseRequest $request, Course $course)
    {
        try {
            //si hacemos consulta entre una transación y un commit, las operaciones se guardan de forma persistente en base de datos, si algo falla no se ejecturá el commit y se irá al roolback, se deshará toda la transación.
            DB::beginTransaction();
            $file = $course->picture;
            //si se está enviando una nueva foto por el formulario
            if ($request->hasfile('picture')) {
                if ($course->picture) {
                    // si hay ya una foto, eliminamos la imagen del directorio cursos ('courses') utilizando el nombre que tenemos en base de datos $course->picture
                    Uploader::removeFile('courses', $course->picture);
                }
                $file = Uploader::uploadFile('picture', 'courses');
            }
            //rellenamos los datos del curso y lo guardamos en base de datos, los datos que han sido actualizados por el usuario, recogidos por el método courseInput de más abajo. La foto si está siendo actualizada se reemplazará por la nueva, pasando por el if de arriba y si no se cambia la foto, se quedará como estaba o vacía o con la foto antigua
            $course->fill($this->courseInput($file, $course->featured))->save();
            //sincronizamos las categorías con el array enviado
            $course->categories()->sync(request('categories'));
            //actualizamos el orden de las unidades si éste ha sido cambiado. O sea, este método solo funcionará aquí, porque lo estamos llamando.
            $this->updateOrderedUnits();
            //hamoces commit
            DB::commit();
            session()->flash('message', ['success', __('Curso actuazalido satisfactoriamente')]);
            return back();


        } catch (\Throwable $exception) {
            //si algo de arriba falla
            DB::rollBack();
            session()->flash('message', ['danger', $exception->getMessage()]);
            return back();
        }
    }

    protected function courseInput(string $file = null, bool $featured = false): array {
        //retornamos un array para reutilizar el input que estamos pasando desde el formulario, campos del modelo de Curso
        return [
            'title' => request('title'),
            'description' => request('description'),
            'price' => request('price'),
            'picture' => $file,
            'featured' => $featured
        ];
    }

    protected function updateOrderedUnits()
    {
        //actualizar el orden de las unidades que estamos enviando, cuando hayan sido editadas por el usuario si se ha actualizado el orden de la unidad
        //orderedUnits es el input oculto del formulario, que recoge el orden del input si ha sido cambiado.
        if (request('orderedUnits')) {
            $data = json_decode(request('orderedUnits'));
            foreach ($data as $unit) {
                Unit::whereId($unit->id)->update(['order' => $unit->order]);
            }
        }
    }
}