@extends('layouts.backend.master')
@section('title','Maklumat Kakitangan')

@section('content')
<div class="row g-5">
  {{-- Carta Statistik --}}
  <div class="col-12">
    <div class="card border">
      <div class="card-header">
        <h3 class="card-title mb-0">Statistik Kakitangan</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <canvas id="chartBranches" height="250"></canvas>
          </div>
          <div class="col-md-6">
            <canvas id="chartPositions" height="250"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Penapis dan Senarai Staf --}}
  <div class="col-12">
    <div class="card border">
      <div class="card-header">
        <h3 class="card-title mb-0">Senarai Staf Mengikut Tapisan</h3>
      </div>
      <div class="card-body">
        <div class="row mb-4">
          <div class="col-md-5">
            <label class="form-label">Penempatan</label>
            <select id="branchSelect" class="form-select">
              <option value="">-- Semua Penempatan --</option>
              @foreach($branches as $b)
                <option value="{{ $b->id }}">{{ $b->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-5">
            <label class="form-label">Jawatan</label>
            <select id="positionSelect" class="form-select">
              <option value="">-- Semua Jawatan --</option>
              @foreach($positions as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button id="btnFilter" class="btn btn-primary w-100">Tapis</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Nama Staf</th>
                <th>Jawatan</th>
                <th>Penempatan</th>
              </tr>
            </thead>
            <tbody id="staffTableBody">
              <tr><td colspan="3" class="text-center text-muted">Sila pilih tapisan di atas.</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Data carta
  const branchLabels = @json($branchLabels);
  const branchData   = @json($branchData);
  const positionLabels = @json($positionLabels);
  const positionData   = @json($positionData);

  function colors(n){ const a=[]; for(let i=0;i<n;i++){ a.push(`hsl(${Math.floor(360/n*i)} 70% 60%)`); } return a; }

  new Chart(document.getElementById('chartBranches').getContext('2d'), {
    type: 'bar',
    data: { labels: branchLabels, datasets: [{ data: branchData, backgroundColor: colors(branchLabels.length) }] },
    options: { plugins:{ legend:{display:false} }, scales:{ y:{beginAtZero:true} } }
  });

  new Chart(document.getElementById('chartPositions').getContext('2d'), {
    type: 'bar',
    data: { labels: positionLabels, datasets: [{ data: positionData, backgroundColor: colors(positionLabels.length) }] },
    options: { plugins:{ legend:{display:false} }, scales:{ y:{beginAtZero:true} } }
  });

  // Butang tapis (AJAX)
  document.getElementById('btnFilter').addEventListener('click', function() {
    const branch_id = document.getElementById('branchSelect').value;
    const position_id = document.getElementById('positionSelect').value;

    fetch(`{{ route('admin.staff.stats.filter') }}?branch_id=${branch_id}&position_id=${position_id}`)
      .then(res => res.json())
      .then(data => {
        const tbody = document.getElementById('staffTableBody');
        tbody.innerHTML = '';
        if(data.length === 0){
          tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tiada data ditemui.</td></tr>';
        } else {
          data.forEach(s => {
  tbody.innerHTML += `
    <tr>
      <td>${s.staff_name}</td>
      <td>${s.position_name ?? '-'}</td>
      <td>${s.branch_name ?? '-'}</td>
    </tr>`;
});

        }
      });
  });
});
</script>
@endpush
