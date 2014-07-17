jQuery(document).ready(function() {

    jQuery("#btnNewGroup").click(function(evt) {
        handlerBtnNewGroup(evt);
    });

    jQuery("#btnInsertUpdateGroup").click(function(evt) {
        handlerBtnInsertUpdateGroup(evt);
    });

    jQuery("#btnDeleteGroup").click(function(evt) {
        handlerBtnDeleteGroup(evt);
    });

    loadGroupGrid();

});