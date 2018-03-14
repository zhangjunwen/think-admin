<?php
namespace app\admin\model;

use think\Model;

class Base extends Model
{
    /**
     * 数据分页
     * @param type $table
     * @param type $where
     * @param type $order
     * @param type $field
     * @param type $limit
     * @return type
     */
    public function page($table,$where=[],$order='',$field="*",$limit=20,$join = []){
        $model = db($table);
        $list = $model->where($where)->order($order)->field($field)->join($join)->paginate($limit);
        foreach($list as $v){$data['list'][] = $v;}
        $data['page'] = $list->render();
        return $data;
    }

    /**
     * 表关联查询
     * @param $array
     * @param int $limit
     * @return mixed
     */
    public function tpage($table,$limit=20){
        $list = Db::table($table['table'])
            ->alias($table['alias'])
            ->where($table['where'])
            ->join($table['join'])
            ->order($table['order'])
            ->field($table['field'])
            ->paginate($limit);
        foreach($list as $v){$data['list'][] = $v;}
        $data['page'] = $list->render();
        return $data;
    }
}