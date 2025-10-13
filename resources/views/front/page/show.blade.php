@extends('front.master.master')

@section('title', $title)

@section('body')
<main class="spark_container">
    <section class="section">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="card-title mb-0">{{ $title }}</h1>
                        </div>
                        <div class="card-body">
                            {{-- The {!! !!} syntax is used to render HTML content from the database --}}
                            {!! $content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection