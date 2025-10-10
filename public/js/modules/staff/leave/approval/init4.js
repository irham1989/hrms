let approverModal = new Modals({selector: '#approver-modal'});

let tableData = common.getForm(false)
tableData.append('user_id', user_id)
tableData.append('is_super', is_super)
tableData.append('is_admin', is_admin)
tableData.append('is_approval', is_approval)
tableData.append('is_staff', is_staff)

let table = new DatatableInit({
    url: `${common.getUrl()}${moduleUrl}approval-list`,
    method: 'POST',
    selector: '#approval-list',
    data: tableData,
    prev: '#approval-prev',
    next: '#approval-next',
    columns: [
        {
            data: 'name'
        },
        {
            data: 'h_date'
        },
        {
            data: 'days'
        },
        {
            data: 'reason'
        },
        {
            data: 'status'
        },
        {
            data: 'action',
            raw: function (full) {
                let full_day = full.is_full_day
                let mc = full.is_mc;

                let status_id = full.status_id;
                let btn = '-';

                let changeBtn = '';

                if(full_day == 1){
                    changeBtn = `<li><button class="dropdown-item text-info leave-change-category" data-change="mc">Ubah Ke Cuti Sakit</button></li>`;
                }else if(mc == 1){
                    changeBtn = `<li><button class="dropdown-item text-info leave-change-category" data-change="annual">Ubah Ke Cuti Rehat</button></li>`;
                }

                if(status_id == 1){
                    let adminChange = '';
                    if(is_admin){
                        adminChange += `<li><button class="dropdown-item text-primary approval-change">Tukar Pelulus</button></li>`
                    }
                    btn = `<div class="dropdown">
                      <button class="btn btn-icon btn-warning" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                           <i class="fas fa-pencil fs-4"></i>
                      </button>
                      <ul class="dropdown-menu">

                        ${adminChange}
                        ${changeBtn}
                        <li><button class="dropdown-item text-success approval-approve" data-approve="1">Sahkan Cuti</button></li>
                        <li><button class="dropdown-item text-danger approval-approve" data-approve="2">Tidak Sahkan Cuti</button></li>
                      </ul>
                    </div>`;
                }else{
                    btn = `<div class="dropdown">
                      <button class="btn btn-icon btn-warning" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                           <i class="fas fa-pencil fs-4"></i>
                      </button>
                      <ul class="dropdown-menu">
                        ${changeBtn}
                      </ul>
                    </div>`;
                }
                return btn;
            }
        }
    ]
})
table.setSearchButton('#approval-list-search').setupChangePage('#approval-prev', '#approval-next');
table.run();

$('#approver-pick').select2({
    ajax: {
        url: `${common.getUrl()}${moduleUrl}get-approver`,
        dataType: 'json',
        data: function (params) {
            let query = {
                search: params.term,
                staff_id: $('#requester-id').val(),
                branch_id: $('#requester-branch-id').val()
            }
            return query;
        },
        processResults: function (data) {
            return {
                results: data.items
            };
        }
    },
});
