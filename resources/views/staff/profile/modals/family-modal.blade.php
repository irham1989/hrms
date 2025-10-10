<input type="hidden" id="fam-id" value="">
<div class="modal fade" tabindex="-1" id="fam-modal" data-bs-focus="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-4 vals-row">
                        <label for="fam-relation" class="required form-label">Hubungan</label>
                        <select class="form-control" id="fam-relation" data-control="select2">
                            <option value="Anak">Anak</option>
                            <option value="Suami">Suami</option>
                            <option value="Isteri">Isteri</option>
                            <option value="Ibu">Ibu</option>
                            <option value="Bapa">Bapa</option>
                            <option value="Adik-beradik">Adik-beradik</option>
                            <option value="Datuk">Datuk</option>
                            <option value="Nenek">Nenek</option>
                            <option value="Ibu Mertua">Ibu Mertua</option>
                            <option value="Bapa Mertua">Bapa Mertua</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-12 vals-row mb-4">
                        <label for="fam-count" class="form-label">Anak Yang Ke? (Jika Hubungan Yang Dipilih Adalah Anak)</label>
                        <select class="form-select" data-control="select2" id="fam-count">
                            <option value="0">Sila Pilih</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-12 vals-row mb-4">
                        <label for="fam-name" class="required form-label">Nama</label>
                        <input type="text" class="form-control text-uppercase" id="fam-name" value="">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-12 vals-row mb-4">
                        <label for="fam-email" class="form-label">E-mail</label>
                        <input type="text" class="form-control" id="fam-email" value="">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-12 vals-row mb-4">
                        <label for="fam-phone" class="required form-label">No. Telefon</label>
                        <input type="text" class="form-control text-uppercase" id="fam-phone" value="">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-12 vals-row mb-4">
                        <label for="fam-dob" class="required form-label">Tarikh Lahir</label>
                        <input type="text" class="form-control text-uppercase" id="fam-dob" value="">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-12 vals-row mb-4">
                        <label for="fam-death" class="form-label">Tarikh Kematian</label>
                        <input type="text" class="form-control text-uppercase" id="fam-death" value="">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>

                <button type="button" class="btn btn-success" id="fam-store-add">
                    <span class="indicator-label">
                        Simpan
                    </span>
                    <span class="indicator-progress">
                        Sedang Diproses... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
                <button type="button" class="btn btn-warning" id="fam-store-update">
                    <span class="indicator-label">
                        Kemaskini
                    </span>
                    <span class="indicator-progress">
                        Sedang Dikemaskini... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
