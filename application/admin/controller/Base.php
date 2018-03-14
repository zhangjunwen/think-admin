<?php
namespace app\admin\controller;

use think\Controller;
use think\auth\Auth;
use think\Validate;
class Base extends Controller
{
    protected $check_login = ['login'];

    protected $beforeActionList = [
        'check_login',
        'menu_auth',
        'input_data',
    ];

    public function check_login(){
        if(!in_array(strtolower(request()->controller()),$this->check_login) && !session('user')){
            $this->redirect('/alogin');
        }else{
            $this->user = session('user');
            $this->conf = config();
            $this->assign([
                'user'      =>  $this->user,
                'config'    =>  config(),
                'input'     =>  input(),
            ]);
        }
    }

    /**
     * 获取input数据
     */
    public function input_data(){
        $this->input = input();
        if(request()->isPost()){$this->input = input('post.');}
        elseif(request()->isGet()){$this->input = input('get.');}
    }

    //菜单Auth认证
    public function menu_auth(){
        $auth = new Auth();
        if($this->user){
            foreach($this->conf['nav_menu'] as &$v){
                if(!$auth->check($v['auth'], $this->user['admin_id'])){$v['auth_status'] = 0;}
                else{$v['auth_status'] = 1;}
                foreach($v['child'] as &$cv){
                    if(!$auth->check($cv['url'], $this->user['admin_id'])){$cv['auth_status'] = 0;}
                    else{$cv['auth_status'] = 1;}
                }
            }
            $group = $auth->getGroups($this->user['admin_id']);
            $this->assign([
                'nav_menu'      =>  $this->conf['nav_menu'],
                'group'         =>  $group[0]
            ]);
        }
    }

    /**
     * 自动验证
     * @param $data
     * @param $rule
     */
    public function auth_validate($data,$rule){
        $validate = new Validate($rule);
        if (!$validate->check($data)) {callBack(1,$validate->getError());}
    }

    /**
     * 文件上传
     * @param $filename
     * @param $path
     * @param array $ext
     * @return array
     */
    public function upload_file($filename,$path,$ext=['size'=>10485760,'ext'=>'jpg,png,gif']){
        $files = request()->file($filename);
        if(!is_array($files)) callBack(1,"请上传文件");
        $path = 'u'.DS.$path;
        foreach($files as $file){
            $info = $file->validate($ext)->move(ROOT_PATH.'public'.DS.$path);
            $file_list[] = request()->domain().DS.$path.DS.$info->getSaveName();
        }
        return $file_list;
    }
}