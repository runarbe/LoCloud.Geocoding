<?php

class MsgElevationRequired {

    public $m = array();
    public $s = -1;

    function __construct() {
        $this->m[] = "Error: your user level is not sufficient to execute this operation";
        $this->s = -1;
    }

    public function getJson() {
        return json_encode($this);
    }

}