$("#fam-dob").flatpickr({
    dateFormat: "d-m-Y",
});

$("#fam-death").flatpickr({
    dateFormat: "d-m-Y",
});

let famModal = new Modals({selector: '#fam-modal'});

let famData = common.getForm(false)
famData.append('staff_id', staff_id)

let table = new DatatableInit({
    url: `${common.getUrl()}${moduleUrl}family-list`,
    method: 'POST',
    data: famData,
    selector: '#fam-list',
    prev: '#fam-prev',
    next: '#fam-next',
    columns: [
        {
            data: 'name'
        },
        {
            data: 'email'
        },
        {
            data: 'relation'
        },
        {
            data: 'death'
        },
        {
            data: 'action',
            raw: function (full) {
                return `<div class="dropdown">
                      <button class="btn btn-icon btn-warning" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                           <i class="fas fa-pencil fs-4"></i>
                      </button>
                      <ul class="dropdown-menu">
                        <li><button class="dropdown-item text-warning fam-edit">Kemaskini</button></li>
                        <li><button class="dropdown-item text-danger fam-delete">Padam</button></li>
                      </ul>
                    </div>`;

            }
        }
    ]
})
table.setupChangePage('#fam-prev', '#fam-next');
table.run();

function resetFamForm(){
    common.resetForm([
        ['#fam-relation', 'dropdown'],
        ['#fam-name', 'string'],
        ['#fam-email', 'string'],
        ['#fam-phone', 'string'],
        ['#fam-count', 'dropdown'],
        ['#fam-dob', 'string'],
        ['#fam-death', 'string'],
    ])

    $('#fam-count').attr('disabled', false)

    common.setFormValue('#fam-id', '', 'string');
}

$('#fam-relation').on('change', function() {
    if ($(this).val() === 'Anak') {
        $('#fam-count').prop('disabled', false);
    } else {
        $('#fam-count').prop('disabled', true);
    }
});
