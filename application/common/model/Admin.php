<?php
namespace app\common\model;

use think\Loader;
use think\Model;

class Admin extends Model {
    protected $pk="admin_id";
    protected $table ="blog_admin";

    public function login($data){
        $validate = Loader::validate('Admin');
        if(!$validate->check($data)){
            return ['valid'=>0,'msg'=>$validate->getError()];
        }
        $userInfo=$this->where('admin_username',$data['admin_username'])->where('admin_password',md5($data['admin_password']))->find();
        if(!$userInfo){
            return ['valid'=>0,'msg'=>'用户名或密码不正确'];
        }
        session('admin.admin_id',$userInfo['admin_id']);
        session('admin.admin_username',$userInfo['admin_username']);
        return ['valid'=>1,'msg'=>'登陆成功'];
    }
}