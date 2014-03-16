/**
 * Set the currently visible tab in the new datasource wizard dialog
 * 
 * @param {Number} pEnabledTab A number between 0 (step 1) and 2 (step 3)
 */
jSrb.Utilities.SetNewDataSourceTab = function(pEnabledTab) {
    var mDisabledTabs = [0,1,2];
    var mTabToRemove = (mDisabledTabs.indexOf(pEnabledTab));
    mDisabledTabs.splice(mTabToRemove, 1);
    
    jSrb.ctlNewDataSourceWizard.tabs("option", {
        disabled: mDisabledTabs,
        active: pEnabledTab,
        heightStyle: "fill"
    });
};