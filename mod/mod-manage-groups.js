function newGroup() {
    resetGroup();
}

function unselectGroups() {
    w2ui["mGroupGrid"].selectNone();
}

function refreshGroupGrid() {
    w2ui["mGroupGrid"].reload();
}

function loadGroupGrid() {
    jQuery('#groupGrid').w2grid({
        header: 'Groups',
        show: {
            header: false,
            toolbar: true
        },
        name: 'mGroupGrid',
        url: 'ws/ws-group.php?action=GetAll',
        onSelect: function(evt) {
            handlerSelectGroup(evt);
        },
        columns: [
            {field: 'title', caption: 'Group name', size: '100%'}
        ],
        searches: [
            {field: 'group', caption: 'Search for group', type: 'text'}
        ],
        sortData: [{field: 'recid', direction: 'ASC'}]
    });
}

function loadGroup(pGroupID) {
    activateGroupForm();
    var mGroup = getW2UIGridRecByID("mGroupGrid", pGroupID);
    console.log(mGroup);
    jQuery("#tbGroupTitle").val(mGroup.title);
    jQuery("#tbGroupDescription").val(mGroup.description);
    jQuery("#hdGroupID").val(mGroup.id);
    jQuery("#hdGroupAction").val("Update");
}

function resetGroup() {
    jQuery("#tbGroupDescription").val("");
    jQuery("#tbGroupTitle").val("");
    jQuery("#hdGroupID").val("");
    jQuery("#hdGroupAction").val("Insert");
}


function handlerSelectGroup(evt) {
    console.log(evt);
    loadGroup(evt.recid);
}

function handlerBtnNewGroup(evt) {
    evt.preventDefault();
    newGroup();
    unselectGroups();
}

function handlerBtnInsertUpdateGroup(evt) {
    evt.preventDefault();
    jQuery.getJSON(WsUrl.manageGroup, {
        action: jQuery("#hdGroupAction").val(),
        title: jQuery("#tbGroupTitle").val(),
        description: jQuery("#tbGroupDescription").val()
    }, function(data) {
        refreshGroupGrid();
    }).fail(function(error) {
        console.log(error);
    });
}

function handlerBtnDeleteGroup(evt) {
    evt.preventDefault();
    jQuery.getJSON(WsUrl.manageGroup, {
        action: "Delete",
        id: jQuery("#hdGroupID").val()
    }, function(data) {
        if (data.status === "success") {
            refreshGroupGrid();
            resetGroup();
        } else {
            console.log(data);
        }
    }).fail(function(error) {
        console.log(error);
    });
}