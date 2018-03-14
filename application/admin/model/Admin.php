<?php
/**
 *
 * User: 大丶象
 * Date: 2018/2/2/002
 * Time: 17:13
 */

namespace app\admin\model;


class Admin extends Base
{
    public function find_data($where,$field="*"){
        $data = db('admin')->where($where)->field($field)->find();
        return $data;
    }

    public function list_data(){
        $data = $this->page('admin');
        return $data;
    }

    public function add_data($data){
        $user = $this->find_data(array('username'=>$data['username']));
        if($user) callBack (1, "账号已存在");
        db('admin')->insert($data);
        $id = db('admin')->getLastInsID();
        if(!$id) callBack(1, "操作失败，请稍后再试");
        return $id;
    }
    public function save_data($where,$data){
        $res = db('admin')->where($where)->update($data);
        return $res;
    }

    public function del_data($where){
        $res = db('admin')->where($where)->delete();
        if(!$res) callBack(1, "操作失败，请稍后再试");
        return $res;
    }
}