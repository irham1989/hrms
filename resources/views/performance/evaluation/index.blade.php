@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Senarai Penilaian Saya</h3>
  <a href="{{ route('performance.evaluation.create') }}" class="btn btn-primary">+ Penilaian Baharu</a>
</div>

@if($period)
  <div class="alert alert-info">Period aktif: {{ $period->name ?? $period->year }}</div>
@endif

<div class="card">
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Period</th>
          <th>Status</th>
          <th>Kemaskini</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($list as $ev)
          <tr>
            <td>{{ $ev->period->name ?? $ev->period->year }}</td>
            <td>{{ strtoupper($ev->status) }}</td>
            <td>{{ $ev->updated_at->format('d/m/Y') }}</td>
            <td>
              <a href="{{ route('performance.evaluation.edit', $ev) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-center text-muted">Tiada rekod.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
