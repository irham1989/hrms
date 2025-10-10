let pModal = new Modals({selector: '#p-modal'});

$("#p-start").flatpickr({
    dateFormat: "d-m-Y",
});

$("#p-end").flatpickr({
    dateFormat: "d-m-Y",
});

function resetHistoryForm(){
    common.resetForm([
        ['#p-start', 'string'],
        ['#p-end', 'string'],
    ])

    common.setFormValue('#p-id', '', 'string');
}
