jQuery("document").ready(function() {

    jQuery("#btnDatasourcesAclDelete").click(function() {
        handlerBtnDatasourcesAclDelete();
        return false;
    });

    jQuery("#btnDatasourceUpdate").click(function(evt) {
        evt.preventDefault();
        handlerBtnDatasourceUpdate();
        return false;
    });

    jQuery('#grdDatasources').w2grid({
        name: 'grdDatasources',
        offset: 0,
        limit: 10,
        multiSelect: false,
        show: {
            header: false,
            footer: false,
            toolbar: false
        },
        onSelect: function(pEvent) {
            handlerGrdDatasourcesSelect(pEvent, this);
        },
        onUnselect: function(pEvent) {
            handlerGrdDatasourcesUnselect(pEvent, this);
        },
        url: WsUrl.manageDatasources,
        columns: [
            {field: 'ds_title', caption: 'Data source title', size: '100%'}
        ],
        searches: [
            {field: 'ds_title', caption: 'Search by data source title', type: 'text'}
        ],
        sortData: [{field: 'recid', direction: 'ASC'}]
    });

    jQuery('#grdDatasourcesAcl').w2grid({
        name: 'grdDatasourcesAcl',
        offset: 0,
        limit: 10,
        multiSelect: false,
        show: {
            header: false,
            toolbar: false,
            footer: false
        },
        onSelect: function(pEvent) {
            handlerGrdDatasourcesAclSelect(pEvent, this);
        },
        url: WsUrl.manageDatasourcesACL,
        postData: {
            datasourceid: -1
        },
        columns: [
            {field: 'usr', caption: 'User name', size: '70%'},
            {field: 'label', caption: 'Access', size: '30%'}
        ],
        sortData: [{field: 'recid', direction: 'ASC'}]
    });


// Prevent submit and add autocompelte
    jQuery("#tbAddUsers").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code === 13) { //Enter keycode
            return false;
        }
    }).autocomplete({
        source: WsUrl.manageUsr + "?cmd=auto-complete",
        appendTo: "#gc-mod",
        closeOnEscape: false,
        minLength: 2,
        select: function(pEvt, pUsr) {
            jQuery("#hdSelectedUsrID").val(pUsr.item.id);
            pollActivateBtnAddDatasourcesAclEntry();
        }
    });

    jQuery("#btnDatasourcesAclAdd").click(function(pEvent) {
        handlerBtnDatasourcesAclAdd("meta_usr",
                jQuery("#hdSelectedUsrID").val(),
                "meta_datasources",
                jQuery("#hdSelectedDatasourceID").val(),
                jQuery("#selAccess").val()
                );
        return false;
    });

    jQuery("#btnDatasourceDelete").click(function(pEvent) {
        pEvent.preventDefault();
        handlerBtnDatasourceDelete();
        return false;
    });
});
