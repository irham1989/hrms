$(document).on('click','.position-edit', function(){
    resetHistoryForm();
    let id = common.getRowId(this, 'data-id');
    let data = common.getForm();
    data.append('id', id);

    pModal.show({
        title: 'Kemaskini Tarikh Lantikan',
        buttons: [
            {
                selector: '#p-store-add',
                show: false
            },
            {
                selector: '#p-store-update',
                show: true
            }
        ],
        callback: function(){
            http.fetch({
                url: `${common.getUrl()}${moduleUrl}get-info-position-history`,
                data: data,
                method: 'POST',
                callback: function(r){
                    if(r.status){
                        common.setFormValue('#p-start', r.data.start_date, 'string');
                        common.setFormValue('#p-end', r.data.end_date, 'string');
                        common.setFormValue('#p-id', r.data.id, 'string');
                    }else{
                        alerting.error(r.data);
                    }
                }
            });
        }
    });
})

$('#p-store-update').on('click', () => pStoreUpdate('#p-store-update'));

function pStoreUpdate(selector){
    common.buttonLoadOnPress(selector);
    let v = new Validscript();

    v.validMix('#p-start', 'Nama')

    if(v.checkFail()){
        alerting.formRequired();
        common.buttonLoadOff(selector);
        return false;
    }
    v.setNewEntry('p_end', $('#p-end').val());
    v.setNewEntry('id', $('#p-id').val());

    http.fetch({
        url: `${common.getUrl()}${moduleUrl}store-update-position-history-date`,
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
                        window.location.reload()
                    }
                })
            }else{
                alerting.error(r.data);
            }

            common.buttonLoadOff(selector);
        }
    })
}

$(document).on('click','.position-active', function(){
    resetHistoryForm();
    let id = common.getRowId(this, 'data-id');
    let data = common.getForm();
    data.append('id', id);

    http.fetch({
        url: `${common.getUrl()}${moduleUrl}set-position-as-active`,
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
                        window.location.reload()
                    }
                })
            }else{
                alerting.error(r.data);
            }
        }
    });
})
