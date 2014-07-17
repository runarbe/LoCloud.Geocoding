jQuery("document").ready(function() {

    jQuery("#btnUsrRegister").click(function(pEvent) {
        pEvent.preventDefault();
        handlerBtnUsrRegister(pEvent);
        return false;
    });

});