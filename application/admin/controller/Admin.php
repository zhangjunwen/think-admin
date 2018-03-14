<?php
/**
 *
 * User: 大丶象
 * Date: 2018/2/2/002
 * Time: 17:38
 */

namespace app\admin\controller;
use lib\verify;
use think\auth\Auth;
use think\Validate;
class Admin extends Base
{
    protected $rule  =   [
        'username.require|length:6,20'          =>  '请输入用户名',
        'password.require|length:6,20'          =>  '密码不能为空',
        'repassword.require|confirm:password'   =>  '确认密码错误',
    ];

    public function index(){
        $data = model('admin')->list_data();
        $auth = new Auth();
        foreach($data['list'] as &$v){
            $v['group_title'] = '';
            foreach ($auth->getGroups($v['admin_id']) as $av){
                $v['group_title'] .= $av['title']."、";
            }
            $v['group_title'] = rtrim($v['group_title'],'、');
        }
        $this->assign("data",$data);
        return view();
    }

    public function build_admin(){
        if(request()->isAjax()){
            $this->auth_validate($this->input,$this->rule);
            model('admin')->add_data([
                'username'  =>  $this->input['username'],
                'password'  =>  build_pwd($this->input['password']),
                'branch'    =>  $this->input['branch'],
                'name'      =>  $this->input['name'],
                'phone'     =>  verify::isMobile($this->input['phone']) ? $this->input['phone'] : callBack(1,"手机号格式错误")
            ]);
            callBack(0, "创建成功");
        }else{
            return view();
        }
    }
    public function update_msg(){
        if(request()->isAjax()){
            $post = input('post.');
            $data = [
                'branch'    =>  $post['branch'],
                'name'      =>  $post['name'],
                'phone'     =>  verify::isMobile($post['phone']) ? $post['phone'] : callBack(1,"手机号格式错误")
            ];
            model('admin')->save_data(['admin_id'=>$post['admin_id']],$data);
            callBack(0, "修改成功");
        }else{
            $user = model('admin')->find_data(['admin_id'=>$this->input['admin_id']]);
            $this->assign([
                'user'          =>  $user,
                'admin_id'      =>  $this->input['admin_id']
            ]);
            return view();
        }
    }

    public function del_admin(){
        if(request()->isAjax()){
            model('admin')->del_data(['admin_id'=>$this->input['admin_id']]);
            callBack(0, "删除成功");
        }
    }

    public function update_pwd(){
        if (request()->isAjax()) {
            $post = input("post.");
            $validate = new Validate($this->rule);
            $validate->scene('update_pwd',['password','repassword']);
            if (!$validate->scene('update_pwd')->check($post)) {
                callBack(1, $validate->getError());
            }
            model('admin')->save_data(['admin_id'=>$this->input['admin_id']], ['password' => build_pwd($post['password'])]);
            callBack(0, "修改成功");
        } else {
            $this->assign([
                'admin_id'      =>  $this->input['admin_id']
            ]);
            return view();
        }
    }
}