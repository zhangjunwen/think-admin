<?php
/**
 *
 * User: 大丶象
 * Date: 2017/12/11/011
 * Time: 7:01
 */

namespace app\admin\controller;

class Auth extends Base
{
    protected $rule  =   [
        'title.require|length:1,20'     => '用户组名称不能为空',
        'title.length'                  => '用户组名称1-20位',
    ];
    public function group_list(){
        $group_list = db("auth_group")
            ->where(array('status'=>1))
            ->field('id as group_id,title,rules')
            ->select();
        foreach($group_list as &$v){
            $v['rules_list'] = db("auth_rule")
                ->where(array('id'=>array('in',$v['rules']),'status'=>1))
                ->field('title')
                ->select();
        }
        $this->assign("group_list",$group_list);
        return view();
    }

    public function add_group(){
        if(request()->isAjax()) {
            $this->auth_validate($this->input,$this->rule);
            db('auth_group')->insert([
                'title'     =>  $this->input['title'],
                'status'    =>  1,
                'rules'     =>  ''
            ]);
            callBack(0,'操作成功');
        }
        return view();
    }

    /**
     * 删除权限角色
     * @param $params
     */
    public function del_group(){
        db('auth_group')->where(array('id'=>input("post.group_id")))->delete();
        db('auth_group_access')->where(array('group_id'=>input("post.group_id")))->delete();
        callBack(0,'操作成功');
    }

    public function save_rule(){
        if(request()->isAjax()) {
            $post = input('post.');
            db('auth_group')->where(array('id'=>input('group_id')))->setField('rules','');
            $rules = "";
            foreach($post['rule'] as $v){
                $rules .= $v.',';
                db('auth_group')->where(array('id'=>input("group_id")))->setField('rules',rtrim($rules,','));
            }
            callBack(0,'操作成功');
        }else{
            $rule_list = db('auth_rule')->where(array('status'=>1))->field('id as rule_id,title')->select();
            $group_rules = db("auth_group")->where(array('id'=>input('group_id')))->value('rules');
            foreach($rule_list as &$v){
                $v['exist'] = 0;
                if($group_rules){
                    if(in_array($v['rule_id'],explode(',',$group_rules))){$v['exist'] = 1;}
                }
            }
            $this->assign('input',input());
            $this->assign("rule_list",$rule_list);
            return view();
        }
    }

    public function rule_list(){
        $rule_list = db("auth_rule")->where(array('status'=>1))->field('id as rule_id,name,title')->order('id asc')->select();
        $this->assign("rule_list",$rule_list);
        return view();
    }

    /**
     * 权限配置
     */
    public function set_group(){
        if(request()->isAjax()){
            db('auth_group_access')->where(array('uid'=>$this->input['admin_id']))->delete();
            foreach ($this->input['group_id'] as $v){
                $data[] = [
                    'uid'       =>  $this->input['admin_id'],
                    'group_id'  =>  $v
                ];
            }
            db('auth_group_access')->insertAll($data);
            callBack(0,'操作成功');
        }else{
            $group_access = db('auth_group_access') ->where(array('uid'=>$this->input['admin_id']))->column('group_id');
            $group_list = db("auth_group") ->where(array('status'=>1)) ->field('id as group_id,title,rules') ->select();
            foreach ($group_list as &$v){
                $v['exist'] = 0;
                if(in_array($v['group_id'],$group_access)){$v['exist']=1;}
            }
            $this->assign('group_list',$group_list);
            return view();
        }
    }
}