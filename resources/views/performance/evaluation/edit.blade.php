@extends('layouts.app')
@section('content')
<form method="POST" action="{{ route('performance.evaluation.update', $evaluation) }}">
  @csrf
  @method('PUT')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>Edit Penilaian</div>
      <div>
        <a href="{{ route('performance.evaluation.submit', $evaluation) }}"
           class="btn btn-success btn-sm"
           onclick="return confirm('Hantar kepada PPP?');">Hantar (PYD)</a>
      </div>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <label class="form-label">Period</label>
        <select name="evaluation_period_id" class="form-control" disabled>
          <option>{{ $evaluation->period->name ?? $evaluation->period->year }}</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Kegiatan & Sumbangan</label>
        <textarea name="kegiatan_sumbangan" class="form-control" rows="5">{{ old('kegiatan_sumbangan', $evaluation->kegiatan_sumbangan) }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Latihan Dihadiri</label>
        <textarea name="latihan_dihadiri" class="form-control" rows="3">{{ old('latihan_dihadiri', $evaluation->latihan_dihadiri) }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Latihan Diperlukan</label>
        <textarea name="latihan_diperlukan" class="form-control" rows="3">{{ old('latihan_diperlukan', $evaluation->latihan_diperlukan) }}</textarea>
      </div>
    </div>
    <div class="card-footer d-flex gap-2">
      <button class="btn btn-primary">Kemaskini Draf</button>
    </div>
  </div>
</form>
@endsection
