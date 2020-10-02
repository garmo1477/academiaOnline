<section class="hero-section-single-course set-single-bg">
    <div class="container">
        <div class="hero-text-single-course text-white">
            <img src="{{ $course->imagePath() }}" class="img-fluid" alt="{{ $course->title }}">
            <h2>{{ $course->title }}</h2>
            @include('partials.learning.courses.rating', ['rating' => $course->rating])
        </div>       
    </div>
</section>

