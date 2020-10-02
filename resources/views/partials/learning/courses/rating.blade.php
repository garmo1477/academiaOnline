{{-- para mostrar las estrellas de las valoraciones --}}
 
<div>
    <ul class="list-inline">
        <li class="list-inline-item">
            <i class="fa-2x fa fa-star{{ $rating >= 1 ? ' yelow' : '' }}"></i>
        </li>
        <li class="list-inline-item">
            <i class="fa-2x fa fa-star{{ $rating >= 2 ? ' yelow' : '' }}"></i>
        </li>
        <li class="list-inline-item">
            <i class="fa-2x fa fa-star{{ $rating >= 3 ? ' yelow' : '' }}"></i>
        </li>
        <li class="list-inline-item">
            <i class="fa-2x fa fa-star{{ $rating >= 4 ? ' yelow' : '' }}"></i>
        </li>
        <li class="list-inline-item">
            <i class="fa-2x fa fa-star{{ $rating = 5 ? ' yelow' : ''  }}"></i>
        </li>
        @if (!isset($hideCounter))
        
        {{-- enseñaremos el numero de valoraciones que tiene un curso, si no existe la variable llamada hideCounter, si no tiene valor --}}
        
            <li class="list-inline-item">
                <h3>{{ $course->reviews->count() }}</h3>
            </li>
        @endif
    </ul>
</div>