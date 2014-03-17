/**
 * Set the currently visible tab in the new datasource wizard dialogs
 * 
 * @param {Number} pEnabledTab A number between 1 and 3
 * @returns {void}
 */
function SetNewDataSourceTab(pEnabledTab) {
    var mDisabledTabs = [0,1,2];
    var mTabToRemove = (mDisabledTabs.indexOf(pEnabledTab));
    mDisabledTabs.splice(mTabToRemove, 1);
    
    ctlNewDataSourceWizard.tabs("option", {
        disabled: mDisabledTabs,
        active: pEnabledTab,
        heightStyle: "fill"
    });
}