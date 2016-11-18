<?php
namespace Admin\Model;
use  Admin\Model\CommonModel;
class StoreModel extends CommonModel {
     //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
         array('title', 'require', '请输入门店名称！', 1, 'regex', 3),
          array('contact', 'require', '请输入门店负责人手机号码！', 1, 'regex', 3),
        
    );

   
}
