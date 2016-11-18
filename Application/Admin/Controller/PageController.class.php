<?php

namespace Admin\Controller;

use Admin\Common\CommonController;

class PageController extends CommonController {

    public function index() {
       /* $data = M("page")->order(array("id" => "asc"))->select();
        foreach ($data as $key => $value) {
            $data[$key]["catname"]= M("category")->where("id=" . $value['catid'])->getField("catname");
            $child= M("category")->where("id=" . $value['catid'])->getField("child");
            if($child==0){
                unset($data[$key]); 
            }
        }
        $this->assign("data", $data);*/
        $count = D("category")->where("modelid=1")->count();
        //$page = new \Think\Page($count, 10);
        $page->setConfig("theme","<span class=\"rows\">当前%NOW_PAGE%/%TOTAL_PAGE%页</span>%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%<span class=\"rows\">共 %TOTAL_ROW% 条记录</span>");
        $page->setConfig("prev","上一页");
        $page->setConfig("next","下一页");
        $page->setConfig("first","第一页");
        $page->setConfig("last","最后一页");
        $list = D("category")->where("modelid=1")->order(array("listorder" => "DESC"))->select();
        $tree = new \Think\Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $r['str_manage'] = '';
        foreach ($list as $r) {
            $title = M("page")->where("catid=" . $r['id'])->find();
            if ($r['child'] == 1) {
                $r['str_manage'] .= '<a href="' . U("Admin/Page/edit", array("catid" => $r['id'])) . '">修改</a>';
            }
            if ($title) {
                $r['time'] = date("Y-m-d H:i:s", $title["updatetime"]);
                $r['title'] = $this->str_cut($title["title"],20);
                 $r['username'] =  $title["username"];
            } else {
                $r['time'] = "还未修改过";
                $r['title'] = $r["catname"];
            }
            $array[] = $r;
        }
        $tree->init($array);
        $str = "<tr>
	 <td align='center'>\$id</td>
	 <td>\$title</td>
                   <td>\$spacer\$catname</td>
                   <td align='center'>\$time</td>
                   <td align='center'>\$username</td>
	<td align='center'>\$str_manage</td>
	</tr>";
        $data = $tree->get_tree(0, $str);
        $this->assign("data", $data);
        $this->display();
    }

    /**
     * 编辑
     */
    public function edit() {
        if ($_POST) {
             $data = D("page")->where("catid=" . $_POST['catid'])->find();
              $description=trim($_POST["description"]);
              if(empty($description)){
                    $description=$this->str_cut(trim(strip_tags($_POST['content'])),250);
                }
            if (D("page")->create()) {
                D("page")->updatetime = time();
                D("page")->username = $_SESSION['user'];
                D("page")->description =$description;
                if ($data) {
                    $id = D("page")->save();
                } else {
                    $id = D("page")->add();
                }
                if ($id) {
                    $this->success("修改成功！", U("Admin/page/index"));
                } else {
                    $this->error("修改失败！");
                }
            } else {
                $this->error(D("page")->getError());
            }
        } else {
            $data = D("page")->where("catid=" . $_GET['catid'])->find();
            $catname = M("category")->where("id=" . $_GET['catid'])->getField("catname");
            if (!$data) {
                $data["catid"] = $_GET['catid'];
                $data["title"] = $catname;
            }
            $this->assign("data", $data);
            $this->display();
        }
    }

}