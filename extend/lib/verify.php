<?php
    /**********************************************
    *   整理类型:  常用验证表单提交 类                  *
    *    整理时间:  2012-11-26                         *
    *    整理人:    icyzhl,320108686@qq.com          *
    *********************************************/
namespace lib;
class verify{
    #验证用户名,$value传递值;$minLen最小长度;$maxLen最长长度;只允许下划线+汉字+英文+数字（不支持其它特殊字符）
    #@param string $value
    #@param int $length
    #@return boolean
    static function isUsername($value,$minLen=2,$maxLen=30){
        if(!$value) return false;
        return preg_match('/^[_\w\d\x{4e00}-\x{9fa5}]{'.$minLen.','.$maxLen.'}$/iu',$value);
    }

   /**
    * 是否为空
    * @param type $value
    * @return boolean
    */
    static function isEmpty($value){
        $value = trim($value);
        if(empty($value)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 判断是否大于0
     * @param unknown $value
     * @return boolean
     */
    static function isGtZero($value){
        $value = (double)$value;
        if($value>0){
            return true;
        }else{
            return false;
        }
    }

    #验证是否为指定语言,$value传递值;$minLen最小长度;$maxLen最长长度;$charset默认字符类别（en只能英文;cn只能汉字;alb数字;ALL不限制）
    #@param string $value
    #@param int $length
    #@return boolean
    static function islanguage($value,$charset='all',$minLen=1,$maxLen=50){
        if(!$value) return false;
        switch($charset){
            case 'en':$match = '/^[a-zA-Z]{'.$minLen.','.$maxLen.'}$/iu';break;
            case 'cn':$match = '/^[\x{4e00}-\x{9fa5}]{'.$minLen.','.$maxLen.'}$/iu';break;
            case 'alb':$match = '/^[0-9]{'.$minLen.','.$maxLen.'}$/';break;
            case 'enalb':$match = '/^[a-zA-Z0-9]{'.$minLen.','.$maxLen.'}$/iu';break;
            case 'all':$match = '/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]{'.$minLen.','.$maxLen.'}$/iu';break;
            //all限制为：只能是英文或者汉字或者数字的组合
        }
        return preg_match($match,$value);
    }

    #验证密码,$value传递值;$minLen最小长度;$maxLen最长长度;
    #@param string $value
    #@param int $length
    #@return boolean
    static function isPassword($value,$minLen=6,$maxLen=16){//支持空格
        $match='/^[\\~!@#$%^&*() -_=+|{}\[\],.?\/:;\'\"\d\w]{'.$minLen.','.$maxLen.'}$/i';
        $value=trim($value);
        if(!$value) return false;
        return preg_match($match,$value);
    }

    #验证eamil,$value传递值;$minLen最小长度;$maxLen最长长度;$match正则方式
    #@param string $value
    #@param int $length
    #@return boolean
    static function isEmail($value,$minLen=6,$maxLen=60,$match='/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/i'){
        if(!$value) return false;
        return (strlen($value)>=$minLen && strlen($value)<=$maxLen && preg_match($match,$value))?true:false;
    }

    #格式化money,$value传递值;小数点后最多2位
    #@param string $value
    #@return boolean
    static function formatMoney($value){
        return sprintf("%1\$.2f",$value);
    }

    #验证电话号码,$value传递值;$match正则方式
    #@param string $value
    #@return boolean
    static function isTelephone($value,$match='/^(0[1-9]{2,3})(-| )?\d{7,8}$/'){
        //支持国际版：$match='/^[+]?([0-9]){1,3}?[ |-]?(0[1-9]{2,3})(-| )?\d{7,8}$/'
        if(!$value) return false;
        return preg_match($match,$value);
    }

    #验证手机,$value传递值;$match正则方式
    #@param string $value
    #@param string $match
    #@return boolean
    static function isMobile($value,$match='/^13[0-9]{1}[0-9]{8}$|14[57]{1}[0-9]{8}|15[012356789]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$/'){
        //支持国际版：([0-9]{1,5}|0)?1([3|4|5|8])+([0-9]){9,10}
        if(!$value) return false;
        return preg_match($match,$value);
    }

    #验证IP,$value传递值;$match正则方式
    #@param string $value
    #@param string $match
    #@return boolean
    static function isIP($value,$match='/^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/'){
        if(!$value) return false;
        return preg_match($match,$value);
    }

    #验证身份证号码,$value传递值;$match正则方式
    #@param string $value
    #@param string $match
    #@return boolean
    static function isIDcard($value,$match='/^\d{6}((1[89])|(2\d))\d{2}((0\d)|(1[0-2]))((3[01])|([0-2]\d))\d{3}(\d|X)$/i'){
        if(!$value) return false;
        else if(strlen($value)>18) return false;
        return preg_match($match,$value);
    }

    #验证URL,$value传递值;$match正则方式
    #@param string $value
    #@param string $match
    #@return boolean
    static function isURL($value,$match='/^(http:\/\/)?(https:\/\/)?([\w\d-]+\.)+[\w-]+(\/[\d\w-.\/?%&=]*)?$/'){
        $value=strtolower(trim($value));
        if(!$value) return false;
        return preg_match($match,$value);
    }

    #验证是否是整数 正整数 0 负整数
    static function isInt($value,$minLen=0,$maxLen=50){
        $match = '/^-?[0-9]\d{'.$minLen.','.$maxLen.'}$/';
        return preg_match($match,intval($value));
    }
}
?>