@extends('layouts.backend.master')
@section('title','Dashboard Staf')

@section('content')
<div class="row g-5">
  <div class="col-12 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header"><h3 class="card-title mb-0">Ringkasan Cuti Tahunan</h3></div>
      <div class="card-body">
        <div class="chart-wrap mx-auto" style="height:300px; max-width:520px;">
          <canvas id="leaveDonut"></canvas>
        </div>
        <div class="d-flex gap-2 justify-content-center mt-4">
          <a href="{{ route('staff.leave.new-request', ['user_id' => auth()->id()]) }}" class="btn btn-primary">
            Mohon Cuti
          </a>
          <a href="{{ route('staff.leave.request', ['user_id' => auth()->id()]) }}" class="btn btn-light">
            Senarai Permohonan
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Opsyenal: Donut MC --}}
  <div class="col-12 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header"><h3 class="card-title mb-0">Ringkasan Cuti Sakit (MC)</h3></div>
      <div class="card-body">
        <div class="chart-wrap mx-auto" style="height:300px; max-width:520px;">
          <canvas id="mcDonut"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const leaveLabels = {!! json_encode($labels ?? []) !!};
  const leaveData   = {!! json_encode($data ?? []) !!};

  const mcLabels = {!! json_encode($mcLabels ?? []) !!};
  const mcData   = {!! json_encode($mcData ?? []) !!};

  const makeDonut = (canvasId, labels, data) => {
    const el = document.getElementById(canvasId);
    if(!el) return;
    const ctx = el.getContext('2d');
    const colors = labels.map((_, i) => `hsl(${Math.floor(360/labels.length*i)} 70% 60%)`);
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{
          data,
          backgroundColor: colors,
          borderColor: 'rgba(255,255,255,0.9)',
          borderWidth: 2,
          cutout: '55%'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom' },
          tooltip: {
            callbacks: {
              label: (c) => {
                const total = c.dataset.data.reduce((a,b)=>a+b,0) || 1;
                const val   = c.parsed || 0;
                const pct   = ((val/total)*100).toFixed(1);
                return ` ${c.label}: ${val} (${pct}%)`;
              }
            }
          }
        },
        layout: { padding: 8 }
      }
    });
  };

  makeDonut('leaveDonut', leaveLabels, leaveData);
  makeDonut('mcDonut',    mcLabels,    mcData);
});
</script>
@endpush
