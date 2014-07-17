jQuery(document).ready(function() {

    jQuery("#btnUsrAdd").click(function(pEvent) {
        pEvent.preventDefault();
        handlerBtnUsrAdd(pEvent);
        return false;
    });
    
    jQuery("#btnUsrInsertUpdate").click(function(pEvent) {
        pEvent.preventDefault();
        handlerBtnUsrInsertUpdate(pEvent);
        return false;
    });
    
    jQuery("#btnUsrDelete").click(function(pEvent) {
        pEvent.preventDefault();
        handlerBtnUsrDelete(pEvent);
        return false;
    });

    jQuery('#grdUsr').w2grid({
        name: 'grdUsr',
        offset: 0,
        limit: 10,
        show: {
            header: false,
            toolbar: true,
            footer: true
        },
        onSelect: function(pEvent) {
            handlerGrdUsrSelect(pEvent, this);
        },
        url: WsUrl.manageUsr,
        columns: [
            {field: 'recid', caption: 'ID#', size: '10%'},
            {field: 'usr', caption: 'User name', size: '50%'},
            {field: 'label', caption: 'User level', size: '40%'}
        ],
        searches: [
            {field: 'user', caption: 'Search by username', type: 'text'}
        ],
        sortData: [{field: 'recid', direction: 'ASC'}]
    });

    newUsr();
});