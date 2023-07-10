<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class klogger {

    //put your code here
    var $location = '/tmp/';
    var $trackId = "";

    public function __construct() {
        $this->location .= "whatsapp-" . Date('Y-m-d-H') . ".log";
        $this->trackId = $this->getTrackId();
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this, $f = '__construct' . $i)) {
            call_user_func_array(array($this, $f), $a);
        }
        $this->setErrorFile();
    }

    private function getTrackId() {
        if (!isset($_SESSION["trackId"])) {
            $_SESSION["trackId"] = time();
        }
        $trackId = $_SESSION["trackId"];
        return Date('Y-m-d H:i:s') . "-" . $trackId;
    }

    public function setTrackId($trackId = "1") {
        $_SESSION["trackId"] = $trackId;
        $this->trackId = $this->getTrackId();
    }

    public function __construct1($file) {
        $this->location .="$file";
    }

    public function debug($msg = null) {
        error_log("DEBUG:  " . $this->trackId . " " . $msg . "\n", 3, $this->location);
    }

    public function error($msg = null) {
        error_log("ERROR:  " . $this->trackId . " " . $msg . "\n", 3, $this->location);
    }

    public function info($msg = null) {
        error_log("INFO:  " . $this->trackId . " " . $msg . "\n", 3, $this->location);
    }

    private function setErrorFile() {
        //   if(!isset($_SESSION['__is_log_error_set'])){
        ini_set('log_errors', TRUE);
        ini_set('error_log', $this->location);
        $_SESSION['__is_log_error_set'] = 1;
        //     }
    }

    public function flow_debug($msg = null) {
        error_log("DEBUG:FlOW  " . $this->trackId . " " . $msg . "\n", 3, $this->location);
    }

}