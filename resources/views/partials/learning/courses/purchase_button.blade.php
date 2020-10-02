@guest
    {{-- si es un invitado --}}
    <a href="{{ route('welcome') }}" class="btn btn-info btn-lg btn-block">
        {{ __('Crear una cuenta') }}
    </a>
@else
    {{-- aquí utilizamos el méotod de la politica de acceso CoursePolicy
    --}}
    @can('purchaseCourse', $course)
        {{-- si puede comprar --}}
        <a href="{{ route("add_course_to_cart", ["course" => $course]) }}"      class="site-btn btn-block"
        >
            {{ __('Tomar el curso por :price', ['price' => $course->formatted_price]) }}
        </a>
    @else
        <a href="" class="site-btn btn-block">
            {{ __('Ir al curso') }}
        </a>
    @endcan
@endguest
