<div class="card" id="course-curriculum">
    @forelse ($course->units as $unit)
    {{-- aquí se añadirá el nombre de los archivos del tipo de curso, si es video, seccion o zip llamando a unit_type se coge el nombre de los tipos y junto con la ruta pues es como logramos ese efecto --}}    
        @include('partials.learning.courses.curriculum.'.strtolower($unit->unit_type))
    @empty
        <div class="empty-results">
            {{ __('El contenido de este curso todavía no está definido') }}
        </div>
    @endforelse
</div>