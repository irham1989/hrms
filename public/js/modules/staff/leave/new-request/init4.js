$("#leave-date-range").flatpickr({
    altInput: true,
    altFormat: "d-m-Y",
    dateFormat: "Y-m-d",
    mode: "range",
    // minDate: "today"
});

$("#leave-start-time").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
});

$("#leave-end-time").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
});

$('#leave-approver').select2({
    ajax: {
        url: `${common.getUrl()}${moduleUrl}get-approver`,
        dataType: 'json',
        data: function (params) {
            let query = {
                search: params.term,
                branch_id: $('#position-branch-id').val(),
                staff_id: $('#staff-id').val(),
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

$('#leave-category').on('change', function() {
    let selected = $(this).find(':selected');
    let is_mc = selected.attr('data-mc');
    let is_full = selected.attr('data-full');
    let is_half = selected.attr('data-half');

    if (is_mc == '1') {
        // MC selected
        $('#leave-start-time, #leave-end-time').prop('disabled', true);
        $('#leave-mc').prop('disabled', false);
    } else if (is_half == '1') {
        // Half-day selected
        $('#leave-start-time, #leave-end-time').prop('disabled', false);
        $('#leave-mc').prop('disabled', false);
    } else {
        // All others
        $('#leave-start-time, #leave-end-time, #leave-mc').prop('disabled', true);
    }
});
