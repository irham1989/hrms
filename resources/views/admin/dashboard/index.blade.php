@extends('layouts.backend.master')
@section('title','Dashboard Pentadbir')

@section('content')
<div class="row gx-5 gx-xl-10 mb-xl-10">
  <div class="col-12">

    {{-- Ringkasan angka (optional) --}}
    <div class="d-flex gap-3 mb-5 flex-wrap">
      <div class="card border flex-fill" style="min-width:220px">
        <div class="card-body">
          <div class="text-muted">Total Staf (bukan admin)</div>
          <div class="fs-1 fw-bold">{{ number_format($totalStaffNonAdmin ?? 0) }}</div>
        </div>
      </div>
      <div class="card border flex-fill" style="min-width:220px">
        <div class="card-body">
          <div class="text-muted">Total Admin</div>
          <div class="fs-1 fw-bold">{{ number_format($totalAdmin ?? 0) }}</div>
        </div>
      </div>
      <div class="card border flex-fill" style="min-width:220px">
        <div class="card-body">
          <div class="text-muted">Total Super Admin</div>
          <div class="fs-1 fw-bold">{{ number_format($totalSuperAdmin ?? 0) }}</div>
        </div>
      </div>
    </div>

    {{-- Carta Pie: Agihan Pengguna Mengikut Peranan --}}
    <div class="card shadow-sm">
      <div class="card-header">
        <h3 class="card-title mb-0">Agihan Pengguna Mengikut Peranan</h3>
      </div>
      <div class="card-body">
  <div class="chart-wrap mx-auto" style="height:360px; max-width:760px;">
    <canvas id="rolePie"></canvas>
  </div>
</div>


  </div>
</div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>
    (function () {
      const canvas = document.getElementById('rolePie');
      if (!canvas) return; // elak error jika elemen tiada

      const labels = @json($labels ?? []);
      const data   = @json($data ?? []);

      // fallback jika tiada data
      const safeLabels = labels.length ? labels : ['Tiada Data'];
      const safeData   = data.length   ? data   : [1];

      const ctx = canvas.getContext('2d');

      // Jana warna lembut automatik
      const colors = safeLabels.map((_, i) => {
        const hue = Math.floor((360 / safeLabels.length) * i);
        return `hsl(${hue} 70% 60%)`;
      });

      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: safeLabels,
          datasets: [{
            data: safeData,
            backgroundColor: colors,
            borderColor: 'rgba(255,255,255,0.9)',
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { position: 'bottom' },
            tooltip: {
              callbacks: {
                label: (ctx) => {
                  const total = ctx.dataset.data.reduce((a, b) => a + b, 0) || 1;
                  const val   = ctx.parsed || 0;
                  const pct   = ((val / total) * 100).toFixed(1);
                  return ` ${ctx.label}: ${val} (${pct}%)`;
                }
              }
            }
          }
        }
      });
    })(); // IIFE: tutup sendiri, jadi tiada kurungan lebihan
  </script>
@endpush
