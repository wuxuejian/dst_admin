<?php
class Gpsmsg {
    public $code;
    protected $cmd = 'gpsmsg';
    protected $mode = 2;
    public $deviceno;
    public $time;
    public $lng;
    public $lat;
    public $speed;
    public $course;
    public $status;
    protected $res = true;
}