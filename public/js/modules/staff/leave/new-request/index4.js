$('#store-update-leave-new-request').on('click', function(){
    common.buttonLoadOnPress('#store-update-leave-new-request')

    let is_mc = $('#leave-category').find(':selected').attr('data-mc')
    let is_full = $('#leave-category').find(':selected').attr('data-full')
    let is_half = $('#leave-category').find(':selected').attr('data-half')

    let v = new Validscript('ms')
    v.validMix('#leave-date-range', 'Julat Cuti')
    v.validInt('#leave-category', 'Kategori Cuti', true)
    v.validInt('#leave-approver', 'Pelulus', true)

    if(is_half == 1){
        v.validMix('#leave-start-time', $('#leave-start-time').val())
        v.validMix('#leave-end-time', $('#leave-end-time').val())
    }else{
        common.resetForm([
            ['#leave-start-time', 'string'],
            ['#leave-end-time', 'string'],
        ])
    }

    if(is_mc == 1 || is_half == 1){
        v.validUpload('#leave-mc', 'Lampiran', ['png', 'jpg', 'jpeg', 'pdf'], 'leave_mc', true)
    }else{
        common.resetForm([
            ['#leave-mc', 'string']
        ])
    }

    if (v.checkFail()) {
        alerting.formRequired();
        return false;
    }

    v.setNewEntry('staff_id', staff_id)
    v.setNewEntry('leave_reason', $('#leave-reason').val())

    http.fetch({
        url: `${common.getUrl()}${moduleUrl}store-update-new-request`,
        data: v.data,
        method: 'POST',
        callback: function(r){
            common.buttonLoadOff('#store-update-leave-new-request');
            if(r.status){
                alerting.fireSwal({
                    text: r.data.message,
                    icon: 'success',
                    buttonColor: 'btn btn-success',
                    confirmButton: 'Close',
                    callback: function(){
                        window.location.reload();
                    }
                })
            }else{
                alerting.error(r.data);
            }
        }
    })
})
