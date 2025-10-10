@extends('layouts.backend.master')
@section('title','Laman Utama Staf')

@section('content')
<div class="row gx-5 gx-xl-10 mb-xl-10">
  <div class="col-12">
    <div class="row g-4">
      @foreach($tiles as $t)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <a href="{{ $t['href'] }}" class="text-decoration-none">
            <div class="card shadow-sm h-100 hover-elevate-up">
              <div class="card-body">
                <div class="fs-1 mb-3">{{ $t['icon'] }}</div>
                <div class="fw-bold text-dark">{{ strtoupper($t['label']) }}</div>
                <div class="text-muted small">{{ $t['desc'] }}</div>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
