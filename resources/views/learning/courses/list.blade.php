<section class="course-section spad">
    <div class="container">
        <div class="section-title mb-3">
            <h2>{{ __('Listado de cursos') }}</h2>
            <p>{{ __(('Aquí tienes todos los cursos de la plataforma')) }}</p>
        </div>
    </div>
    <div class="course-wrap">
        <div class="row course-items-area">
            @forelse ($courses as $course)
                @include('partials.learning.courses.single')
            @empty
                <p>No hay ningún curso de lo que has buscado</p>
            @endforelse
        </div>
    </div>
</section>