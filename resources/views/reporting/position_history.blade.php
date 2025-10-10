@extends('layouts.backend.master')

@section('title')
    Laporan Sejarah Jawatan
@endsection

@section('content')
    <div class="card mb-5 mb-xl-10">
        <div class="card-header">
            <h3 class="card-title">Carian Sejarah Jawatan</h3>
        </div>
        <form action="{{ route('admin.reporting.index') }}" method="POST">
            @csrf
            <div class="card-body pt-9 pb-0">
                <div class="row">
                     {{-- No. IC --}}
                    <div class="col-md-4 vals-row mb-4">
                        <label for="ic-no" class="form-label">No. Kad Pengenalan</label>
                        <input type="text"
                         id="ic-no"
                        name="ic_no"
                        class="form-control"
                         value="{{ $ic_no }}"
                        placeholder="Contoh: 111111223333 atau 111111-22-3333"
                         maxlength="14">
                    <div class="invalid-feedback"></div>
                </div>
                    <div class="col-md-4 vals-row mb-4">
                        <label for="staff-name" class="form-label">Nama Pegawai</label>
                        <input type="text" name="staff_name" class="form-control" value="{{ $staff_name }}">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-4 vals-row mb-4">
                        <label for="branch" class="form-label">Cawangan</label>
                        <select name="branch" class="form-select" data-control="select2" id="branch">
                            <option value="">Semua Cawangan</option>
                            @foreach($branchList as $bl)
                                <option value="{{ $bl->id }}" {{ $branch == $bl->id ? 'selected' : '' }}>{{ ucwords($bl->name) }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-4 vals-row mb-4">
                        <label for="grade" class="form-label">Gred</label>
                        <select name="grade" class="form-select" data-control="select2" id="grade">
                            <option value="">Semua Gred</option>
                            @foreach($gradeList as $gl)
                                <option value="{{ $gl->id }}" {{ $grade == $gl->id ? 'selected' : '' }}>{{ $gl->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-4 vals-row mb-4">
                        <label for="year-start" class="form-label">Tahun Mula</label>
                        <select name="year_start" class="form-select" data-control="select2" id="year-start">
                            <option value="">Semua Tahun</option>
                            @foreach($yearList as $yl)
                                <option value="{{ $yl }}" {{ $year_start == $yl ? 'selected' : '' }}>{{ $yl }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-4 vals-row mb-4">
                        <label for="year-end" class="form-label">Tahun Tamat</label>
                        <select name="year_end" class="form-select" data-control="select2" id="year-end">
                            <option value="">Semua Tahun</option>
                            @foreach($yearList as $yl)
                                <option value="{{ $yl }}" {{ $year_end == $yl ? 'selected' : '' }}>{{ $yl }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <button name="find_normal_generate" type="submit" class="btn btn-success float-end ms-5" value="genNormal">Cari</button>
                        <button name="find_pdf_generate" formtarget="_blank" type="submit" class="btn btn-danger float-end" value="genPdf">Jana PDF</button>
                         {{-- BUTANG BARU: EXPORT EXCEL --}}
                        <button type="submit" class="btn btn-primary float-end me-3"
                                formmethod="POST" formtarget="_blank"
                                formaction="{{ route('admin.reporting.excel') }}">
                            Jana Excel
                        </button>
                        {{-- TAMAT BUTANG BARU --}}
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card mb-5 mb-xl-10">
        <div class="card-header">
            <h3 class="card-title">Senarai Sejarah Jawatan</h3>
        </div>
        <div class="card-body pt-9 pb-0">
            <div class="row">
                <table class="table table-bordered text-center align-middle">
                    <thead>
                        <tr>
                            <td style="width:25%">Nama</td>
                            <td style="width:15%">No. IC</td>   {{-- <-- TAMBAH --}}
                            <td style="width:25%">Jawatan</td>
                            <td style="width:5%">Gred</td>
                            <td style="width:25%">Cawangan</td>
                            <td style="width:10%">Tarikh Lantik</td>
                            <td style="width:10%">Tarikh Tamat</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($staffList) > 0)
                            @foreach($staffList as $sl)
                                <tr>
                                    <td>{{ ucwords($sl->name)  }}</td>
                                    <td>{{ $sl->ic_no }}</td>   {{-- <-- TAMBAH --}}
                                    <td>{{ $sl->position }}</td>
                                    <td>{{ $sl->grade }}</td>
                                    <td>{{ $sl->branch_name }}</td>
                                    <td>
                                        {{ $sl->start_date ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $sl->end_date ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">Tiada Rekod Ditemui</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('jsCustom')
    <script src="{{ asset('js/modules/reporting/position_history/init.js') }}?v=1"></script>
    <script src="{{ asset('js/modules/reporting/position_history/index.js') }}?v=1"></script>
@endsection
