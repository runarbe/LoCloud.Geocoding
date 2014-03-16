<?php
/**
 * Message to return if the session has expired
 */
class MsgSessionExpired {

    public $m = array();
    public $s = -1;

    function __construct() {
        $this->m[] = "Error: you are not logged in or your user session has expired due to inactivity. Please log in again to continue using the application.";
        $this->s = -1;
    }

    public function getJson() {
        return json_encode($this);
    }

}
?>
