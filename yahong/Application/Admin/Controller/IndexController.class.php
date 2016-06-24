<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class IndexController extends Controller {
    public function index(){
     // var_dump(M()->table('Product')->select());
        $this->display();
    }
}