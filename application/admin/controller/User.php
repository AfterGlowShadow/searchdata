<?php
namespace app\admin\controller;
use app\Controllers\BaseController;
use app\Models\User as UserModel;
class User extends BaseController{
    public $Model;
    public function initialize(){
        parent::initialize();
        $this->Model=new UserModel();
    }

}