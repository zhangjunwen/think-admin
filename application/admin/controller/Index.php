<?php
namespace app\admin\controller;

class Index extends Base
{
    public function index()
    {
        return view();
    }

    public function welcome(){
        echo ('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <p>欢迎登录 <b>“'.$this->conf["web_title"].'”</b> 系统管理平台！</p>');
    }
}
