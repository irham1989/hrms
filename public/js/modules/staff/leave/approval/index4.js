$(document).on('click', '.approval-change', function(){
    let id = common.getRowId(this, 'data-id');
    let requester = common.getRowId(this, 'data-requester');
    let requester_branch = common.getRowId(this, 'data-branch');

    $('#requester-id').val(requester)
    $('#requester-branch-id').val(requester_branch)
    $('#leave-id').val(id)

    approverModal.show({
        title: 'Ubah Pelulus',
        buttons: [
            {
                selector: '#approver-store-update',
                show: true
            }
        ],
    });
})

$('#approver-store-update').on('click', function(){
    common.buttonLoadOnPress('#approver-store-update');
    let v = new Validscript('ms');

    v.validInt('#approver-pick', '', true)

    if(v.checkFail()){
        alerting.formRequired();
        common.buttonLoadOff('#approver-store-update');
        return false;
    }

    v.setNewEntry('id', $('#leave-id').val());

    http.fetch({
        url: `${common.getUrl()}${moduleUrl}update-approver`,
        data: v.data,
        method: 'POST',
        callback: function(r){
            if(r.status){
                alerting.fireSwal({
                    text: r.data.message,
                    icon: 'success',
                    buttonColor: 'btn btn-success',
                    confirmButton: 'Close',
                    callback: function(){
                        approverModal.hide();
                        table.reload();
                    }
                })
            }else{
                alerting.error(r.data);
            }

            common.buttonLoadOff('#approver-store-update');
        }
    })
})

$(document).on('click', '.approval-delete', function(){
    let data = common.getForm();
    data.append('id', common.getRowId(this, 'data-id'));

    alerting.fireSwal({
        text: 'Padam Permohonan Cuti?',
        icon: 'error',
        confirmButton: 'Ya',
        buttonColor: 'btn btn-warning',
        showCancelButton: true,
        callback: function(){
            http.fetch({
                url: `${common.getUrl()}${moduleUrl}request-delete`,
                data: data,
                method: 'POST',
                callback: function(r){
                    if(r.status){
                        alerting.fireSwal({
                            text: r.data.message,
                            icon: 'success',
                            buttonColor: 'btn btn-success',
                            confirmButton: 'Close',
                            callback: function(){
                                table.reload();
                            }
                        })
                    }else{
                        alerting.error(r.data);
                    }
                }
            });
        }
    })
})

$(document).on('click', '.approval-approve', function(){
    let approveStat = $(this).attr('data-approve')

    let data = common.getForm(false)
    data.append('approve_stat', approveStat)
    data.append('id', common.getRowId(this, 'data-id'))

    alerting.fireSwal({
        text: approveStat == 1 ? 'Sahkan Permohonan Cuti Ini' : 'Tidak Sahkan Permohonan Cuti Ini',
        icon: approveStat == 1 ? 'warning' : 'error',
        confirmButton: 'Ya',
        buttonColor: 'btn btn-warning',
        showCancelButton: true,
        callback: function(){
            http.fetch({
                url: `${common.getUrl()}${moduleUrl}request-approval`,
                data: data,
                method: 'POST',
                callback: function(r){
                    if(r.status){
                        alerting.fireSwal({
                            text: r.data.message,
                            icon: 'success',
                            buttonColor: 'btn btn-success',
                            confirmButton: 'Close',
                            callback: function(){
                                table.reload();
                            }
                        })
                    }else{
                        alerting.error(r.data);
                    }
                }
            });
        }
    })
})

$(document).on('click', '.leave-change-category', function(){
    let changeTo = $(this).attr('data-change')

    let data = common.getForm(false)
    data.append('change_to', changeTo)
    data.append('id', common.getRowId(this, 'data-id'))

    alerting.fireSwal({
        text: changeTo == 'mc' ? 'Ubah Kepada Cuti Sakit?' : 'Ubah Kepada Cuti Rehat?',
        icon: changeTo == 'mc' ? 'warning' : 'warning',
        confirmButton: 'Ya',
        buttonColor: 'btn btn-warning',
        showCancelButton: true,
        callback: function(){
            http.fetch({
                url: `${common.getUrl()}${moduleUrl}leave-request-change-category`,
                data: data,
                method: 'POST',
                callback: function(r){
                    if(r.status){
                        alerting.fireSwal({
                            text: r.data.message,
                            icon: 'success',
                            buttonColor: 'btn btn-success',
                            confirmButton: 'Close',
                            callback: function(){
                                table.reload();
                            }
                        })
                    }else{
                        alerting.error(r.data);
                    }
                }
            });
        }
    })
})
