<?php
namespace app\admin\controller;
use think\session;
class Login extends Base
{
    protected $rule  =   [
        'username.require|length:6,20'  => '用户名不能为空',
        'password.require|length:6,20'  => '密码不能为空',
    ];

    public function index(){
        if(request()->isAjax()){
            $this->auth_validate($this->input,$this->rule);
            $this->post['password'] = build_pwd($this->input['password']);
            $user = model('admin')->find_data(["username"=>$this->input['username']],"admin_id,username,password");
            if(!$user) callBack(1,"账户不存在");
            if($user['password'] != $this->post['password']) callBack(1,"密码错误");
            session('user',$user);
            callBack(0,"登录成功");
        }else{ return view();}
    }

    public function login_out(){
        Session::clear();
        $this->redirect('/alogin');
    }
}