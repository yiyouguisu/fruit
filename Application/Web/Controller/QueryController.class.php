<?php

namespace Web\Controller;

use Web\Common\CommonController;

class QueryController extends CommonController {
    public function _initialize() {
        $this->cart_total_num();
    }
    
    public function index() {
        $data=M('search_keyword')->order(array('hit'=>'desc','inputtime'=>'desc'))->field("keyword,hit")->select();
        foreach ($data as $key=>$value)
        {
        	$data[$key]['hotkey']=urlencode($data[$key]['keyword']);
        }
        //dump($data);
        $this->assign('data',$data);
        //历史搜索
        $keywordlist = array_filter(explode(",", $_COOKIE['web_historykeyword']));
        $values = array();
        foreach ($keywordlist as $key=>$value)
        {
            $keywordlist['hotkey'][$key]['name'] = urlencode($keywordlist[$key]);
            $keywordlist['hotkey'][$key]['value'] = $keywordlist[$key];
        }
        //dump($keywordlist);
        $this->assign("keywordlist" , $keywordlist['hotkey']);
    	$this->display();
    }

    public function lists(){
        $keyword = I('get.keyword');
        $keyword = urldecode($keyword);
        $this->assign('keyword',$keyword);
        //dump($keyword);
        
        $flag = 0;
        if($_COOKIE['web_historykeyword']==null)
        {
            cookie('historykeyword',$keyword);
        }
        else
        {
            $keywordlist = array_filter(explode(",", $_COOKIE['web_historykeyword']));
            foreach ($keywordlist as $key=>$value)
            {
            	if($keywordlist[$key] == $keyword){
                    $flag = 1;
                }
            }
            if($flag == 0){
                cookie('historykeyword',$_COOKIE['web_historykeyword'].','.$keyword);
            }
        }
        
        $goods_info_img = U('Web/Product/infoview',array('id'=>'{goods_id}'));
    	$goods_info_title = U('Web/Product/infoview',array('id'=>'{goods_id}'));
        //这里尝试下加入购物车的相关信息
		$this->assign("goods_info_img" , $goods_info_img);
        $this->assign("goods_info_title" , $goods_info_title);
        $this->display();
    }

    public function dclear(){
        cookie('historykeyword',null);
        $this->ajaxReturn('success');
    }
}