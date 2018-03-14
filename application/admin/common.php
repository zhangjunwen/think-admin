<?php
//密码生成
function build_pwd($pwd){
    $key = config('pwd_prefix');
    return md5($key.$pwd);
}