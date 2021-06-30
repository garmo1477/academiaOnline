@extends('layouts.learning')

@section('hero')
    @include('partials.learning.hero_topics')
@endsection

@section('content')
    <topics
        :course="{{ json_encode(
            Hashids::connection(\App\Models\Course::class)
            ->encode(request()->route('course')->id)
            )
        }}"
    ></topics>
@endsection

@push('js')
<script src="{{ asset('js/app.js') }}"></script>
@endpush

