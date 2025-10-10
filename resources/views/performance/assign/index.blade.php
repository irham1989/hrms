@extends('layouts.app')

@section('content')
<div class="card">
  <div class="card-header">Tetapan Assign Penilai</div>
  <div class="card-body">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('performance.assign.store') }}">
      @csrf
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Period</label>
          <select name="evaluation_period_id" class="form-control">
            @foreach($periods as $p)
              <option value="{{ $p->id }}">{{ $p->name ?? ('Tahun '.$p->year) }}</option>
            @endforeach
          </select>
          @error('evaluation_period_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">PYD (Staf)</label>
          <select name="staff_id" class="form-control">
            @foreach($staff as $s)
              <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
          </select>
          @error('staff_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">PPP (Penilai Pertama)</label>
          <select name="ppp_id" class="form-control">
            <option value="">— Tiada —</option>
            @foreach($staff as $s)
              <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
          </select>
          @error('ppp_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">PPK (Penilai Kedua)</label>
          <select name="ppk_id" class="form-control">
            <option value="">— Tiada —</option>
            @foreach($staff as $s)
              <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
          </select>
          @error('ppk_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">Effective From</label>
          <input type="date" name="effective_from" class="form-control" value="{{ old('effective_from') }}">
          @error('effective_from') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">Effective To (opsyenal)</label>
          <input type="date" name="effective_to" class="form-control" value="{{ old('effective_to') }}">
          @error('effective_to') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="mt-3">
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
