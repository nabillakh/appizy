<?php

Class log {
  //
  const USER_ERROR_DIR = '/home/site/error_log/Site_User_errors.log';
  const GENERAL_ERROR_DIR = '/home/site/error_log/Site_General_errors.log';

  private $path;

  public function log($log_dir) {
    $this->path = $log_dir;
  }

  public function user($msg,$username) {
    $date = date('Y.m.d h:i:s');
    // $log = $msg."   |  Date:  ".$date."  |  User:  ".$username."\n";
    $types = ['notice','tips','warning','error'];
    $log = $date;
    error_log($log, 3, $this->path);
  }

  public function general($msg) {
    $date = date('d.m.Y h:i:s');
    $log = $msg."   |  Date:  ".$date."\n";
    error_log($msg."   |  Tarih:  ".$date, 3, self::GENERAL_ERROR_DIR);
  }

}
