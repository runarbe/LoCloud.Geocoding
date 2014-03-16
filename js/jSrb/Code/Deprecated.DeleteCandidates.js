function loadSourceItemsOld (pOffset, pLimit) {
    jQuery.getJSON('ws-load.php',
    {
        offset:pOffset,
        limit:pLimit
    },
    function(data) {
        if (data.s == 1) {
            clearList();
            jQuery.each(data.d,function(key, val) {
                var listElement = jQuery('<li></li>');
                listElement.html(val.poi_name);
                if (val.gc_probability != null) {
                    listElement.addClass("prob"+val.gc_probability);
                    listElement.addClass("ui-state-default");
                }
                listElement.data("attributes", val)
                jQuery('#selectable').append(listElement);
            });            
        }
        else {
            console.log(data.m);
        }
    });    
}