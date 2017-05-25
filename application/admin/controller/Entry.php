<?php
namespace app\admin\controller;

class Entry extends Common {
    public function index(){
        return $this->fetch();
    }
}