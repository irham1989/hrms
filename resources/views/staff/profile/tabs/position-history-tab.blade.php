@php use Illuminate\Support\Facades\Auth; @endphp
<div class="row gx-5 gx-xl-10 mb-xl-10">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Sejarah Perkhidmatan</h3>
                @if(Auth::user()->hasRole('super-admin|admin'))
                    <div class="card-toolbar">
                        <a href="{{ route('admin.user.list') }}" class="btn btn-sm btn-danger">
                            Kembali Ke Senarai Pengguna
                        </a>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle" id="academic-list">
                                    <thead>
                                    <tr class="fw-bold fs-6 text-gray-800" style="text-align:center">
                                        <th style="width: 30%">Penempatan</th>
                                        <th style="width: 20%">Jawatan</th>
                                        <th style="width: 10%">Terkini?</th>
                                        @if(Auth::user()->hasRole('super-admin|admin'))
                                            <th style="width: 10%">Tindakan</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($staff->getStaffPositionHistory) > 0)
                                        @php
                                            $x = 0;
                                        @endphp
                                        @foreach($staff->getStaffPositionHistory as $gsp)
                                            <tr data-id="{{ $gsp->id }}">
                                                <td>
                                                    {{ $gsp->getBranch->name }}<br>
                                                    {{ $gsp->start_date ? date('d-m-Y', strtotime($gsp->start_date)) : '-' }}<br>Hingga<br> {{ $gsp->end_date ? date('d-m-Y', strtotime($gsp->end_date)) : '-' }}
                                                </td>
                                                <td>
                                                    {{ $gsp->getBranchPosition->getPosition->name }}<br>
                                                    {{ $gsp->getBranchPosition->getGrade->name }}
                                                </td>
                                                <td>
                                                    @if($gsp->active)
                                                        <span class="text-success">Aktif</span>
                                                    @else
                                                        @if($gsp->end_date)
                                                            <span class="text-danger">Tamat</span>
                                                        @else
                                                            <span class="text-warning">-</span>
                                                        @endif

                                                    @endif
                                                </td>
                                                @if(Auth::user()->hasRole('super-admin|admin'))
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-icon btn-warning" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas fa-pencil fs-4"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><button class="dropdown-item text-warning position-edit">Tarikh Lantikan Dan Tamat</button></li>
                                                                <li><button class="dropdown-item text-success position-active">Tetap Sebagai Jawatan Aktif</button></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                            @php
                                                $x++;
                                            @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                Tiada Sejarah Perkhidmatan
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
