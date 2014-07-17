<?php
require_once("functions.php");
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>LoCloud Geocoding Micro Service</title>
        <link rel="stylesheet" href="css/style.css" type="text/css" title="main"/>
        <link rel="stylesheet" href="css/cupertino/jquery-ui.css"/>
        <link rel="stylesheet" href="css/pure/pure.css"/>
        <link rel="stylesheet" href="js/w2ui-1.3/w2ui-1.3.css"/>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.10.2.custom.min.js" type="text/javascript"></script>
        <script src="js/w2ui-1.3/w2ui-1.3.min.js" type="text/javascript"></script>
        <script src="js/jSrb/jSrb.js" type="text/javascript"></script>
    </head>
    <body>
        <div id="dlgManager" class="pure-g" title="Manage users and groups">
            <img src="images/locloud-logo-50px.png"/>
            <div class="pure-u-1">
                <button id="btnNewUsr" class="pure-button">Create new user</button>
                <button id="btnNewGroup" class="pure-button">Create new group</button>
                <p>Use tools</p>
            </div>
            <div class="pure-u-1-3">
                <div id="usrGrid" style="height:500px;"></div>
            </div>
            <div class="pure-u-1-3">
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Link</a></li>
                        <li><a href="#tabs-2">User</a></li>
                        <li><a href="#tabs-3">Group</a></li>
                    </ul>
                    <div id="tabs-1">
                        Info goes here
                    </div>
                    <div id="tabs-2">
                        <form id="usrForm" class="pure-form pure-form-aligned">
                            <fieldset>
                                <div class="pure-control-group">
                                    <label>User name</label>
                                    <input type="text" id="tbUsrUsr" value="" placeholder="Username" required/>
                                </div>
                                <div class="pure-control-group">
                                    <label>Password</label>
                                    <input type="password" id="tbUsrPwd" placeholder="Password" required/>
                                </div>
                                <div class="pure-control-group">
                                    <label>Level</label>
                                    <select id="tbUsrLevel" value="2" placeholder="User level" required>
                                        <?php echo getOptions("WsUserLevel"); ?>                        
                                    </select>
                                </div>
                                <div class="pure-controls">
                                    <button id="btnDeleteUsr" class="pure-button">Delete</button>
                                    <button id="btnInsertUpdateUsr" class="pure-button pure-button-primary">Create/Modify</button>
                                </div>
                            </fieldset>
                            <input type="hidden" id="hdUsrID"/>
                        </form>
                    </div>
                    <div id="tabs-3">
                        <form id="groupForm" class="pure-form pure-form-aligned">
                            <fieldset>
                                <div class="pure-control-group">
                                    <label>Title</label>
                                    <input type="text" id="tbGroupTitle" value="" placeholder="Title/name of group" required/>
                                </div>
                                <div class="pure-control-group">
                                    <label>Description</label>
                                    <input type="text" id="tbGroupDescription" value="" placeholder="Short description of group" required/>
                                </div>

                                <div class="pure-controls">
                                    <button id="btnDeleteGroup" class="pure-button">Delete</button>
                                    <button id="btnInsertUpdateGroup" class="pure-button pure-button-primary">Create/Modify</button>
                                </div>
                            </fieldset>
                            <input type="hidden" id="hdGroupID"/>
                        </form>
                    </div>
                </div>
            </div>
            <div class="pure-u-1-3">
                <div id="groupGrid" style="height:500px;"></div>                    
            </div>
        </div>
        <div id="popup"></div>
    </body>
</html>