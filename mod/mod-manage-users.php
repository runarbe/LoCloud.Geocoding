<div id="gc-mod" title="Manage users">
    <div id="dlgManager" class="pure-g" title="Manage users">
        <div class="pure-u-1-2">
            <p>1a) Create a new user</p>
            <button id="btnUsrAdd" class="pure-button pure-button-primary">Add new</button>
            <p>...or...</p>
            <p>1b) Select an existing user to edit</p>
            <div id="grdUsr" style="height:400px;"></div>
        </div>
        <div class="pure-u-1-2">
            <form id="meta_usr" class="pure-form pure-form-aligned">
                <p>2. Add or modify user data</p>
                <fieldset>
                    <div class="pure-control-group">
                        <label>User name</label>
                        <input type="text" id="usr" class="pure-input-1-2" value="" placeholder="Username" required  />
                    </div>
                    <div class="pure-control-group">
                        <label>Password</label>
                        <input type="password" id="pwd" class="pure-input-1-2" placeholder="Password" required/>
                    </div>
                    <div class="pure-control-group">
                        <label>Level</label>
                        <select id="level" value="2" class="pure-input-1-2" placeholder="User level" required>
                            <?php echo getOptions("WsUserLevel", array(UserLevels::SuperAdmin)); ?>                        
                        </select>
                    </div>
                    <div class="pure-controls">
                        <button id="btnUsrDelete" class="pure-button">Delete</button>
                        <button id="btnUsrInsertUpdate" class="pure-button pure-button-primary">Create</button>
                    </div>
                </fieldset>
                <input type="hidden" id="hdSelectedUsrID"/>
                <input type="hidden" id="hdCmd"/>
            </form>                
        </div>
        <div class="pure-u-1">
            <div id="lblLog"></div>
        </div>
    </div>
</div>