@extends('layouts.backend.master')
@section('title','Maklumat Kakitangan')

@section('content')
<div class="row g-5">
  <div class="col-12">
    <div class="card border">
      <div class="card-header">
        <h3 class="card-title mb-0">Jumlah Kakitangan Ikut Penempatan</h3>
      </div>
      <div class="card-body">
        <div class="chart-wrap mx-auto" style="height:360px; max-width:900px">
          <canvas id="chartBranches"></canvas>
        </div>

        {{-- Jadual ringkas --}}
        <div class="table-responsive mt-5">
          <table class="table table-row-dashed align-middle">
            <thead><tr><th>Penempatan</th><th class="text-end">Jumlah</th></tr></thead>
            <tbody>
              @foreach($byBranches as $row)
                <tr>
                  <td>{{ $row->label }}</td>
                  <td class="text-end">{{ $row->total }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card border">
      <div class="card-header">
        <h3 class="card-title mb-0">Jumlah Kakitangan Ikut Jawatan</h3>
      </div>
      <div class="card-body">
        <div class="chart-wrap mx-auto" style="height:360px; max-width:900px">
          <canvas id="chartPositions"></canvas>
        </div>

        {{-- Jadual ringkas --}}
        <div class="table-responsive mt-5">
          <table class="table table-row-dashed align-middle">
            <thead><tr><th>Jawatan</th><th class="text-end">Jumlah</th></tr></thead>
            <tbody>
              @foreach($byPositions as $row)
                <tr>
                  <td>{{ $row->label }}</td>
                  <td class="text-end">{{ $row->total }}</td>
                </tr>
              @endforeach
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
  const branchLabels = @json($branchLabels);
  const branchData   = @json($branchData);

  const positionLabels = @json($positionLabels);
  const positionData   = @json($positionData);

  // warna lembut automatik
  function colors(n){
    const a=[]; for(let i=0;i<n;i++){ a.push(`hsl(${Math.floor(360/n*i)} 70% 60%)`); } return a;
  }

  // Bar chart – Penempatan
  new Chart(document.getElementById('chartBranches').getContext('2d'), {
    type: 'bar',
    data: {
      labels: branchLabels,
      datasets: [{ data: branchData, backgroundColor: colors(branchLabels.length), borderWidth: 0 }]
    },
    options: {
      responsive: true,
      plugins:{ legend:{ display:false }, tooltip:{ enabled:true } },
      scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } }
    }
  });

  // Horizontal bar – Jawatan
  new Chart(document.getElementById('chartPositions').getContext('2d'), {
    type: 'bar',
    data: {
      labels: positionLabels,
      datasets: [{ data: positionData, backgroundColor: colors(positionLabels.length), borderWidth: 0 }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      plugins:{ legend:{ display:false }, tooltip:{ enabled:true } },
      scales:{ x:{ beginAtZero:true, ticks:{ precision:0 } } }
    }
  });

});
</script>
@endpush
