@extends('layouts.app')
@section('content')
<form method="POST" action="{{ route('performance.evaluation.store') }}">
  @csrf
  <div class="card">
    <div class="card-header">Penilaian Baharu</div>
    <div class="card-body">
      <div class="mb-3">
        <label class="form-label">Period</label>
        <select name="evaluation_period_id" class="form-control">
          @foreach($periods as $p)
            <option value="{{ $p->id }}">{{ $p->name ?? $p->year }}</option>
          @endforeach
        </select>
        @error('evaluation_period_id') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Kegiatan & Sumbangan</label>
        <textarea name="kegiatan_sumbangan" class="form-control" rows="5">{{ old('kegiatan_sumbangan') }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Latihan Dihadiri</label>
        <textarea name="latihan_dihadiri" class="form-control" rows="3">{{ old('latihan_dihadiri') }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Latihan Diperlukan</label>
        <textarea name="latihan_diperlukan" class="form-control" rows="3">{{ old('latihan_diperlukan') }}</textarea>
      </div>
    </div>
    <div class="card-footer">
      <button class="btn btn-primary">Simpan Draf</button>
    </div>
  </div>
</form>
@endsection
