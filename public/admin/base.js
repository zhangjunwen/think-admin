/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function LocationScript() {
    window.parent.location.reload(); //刷新父页面
    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
    parent.layer.close(index);  // 关闭layer
}

