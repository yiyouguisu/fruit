<include file="Common:Head" /><body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <!-- <include file="Common:Nav"/> -->
       <!--  <div class="h_a">搜索</div>
        <form method="get" action="{:U('Admin/Package/index')}">
            <input type="hidden" value="1" name="search">
            <div class="search_type cc mb10">
                <div class="mb10"> 
                    <span class="mr20">
                        下单时间：
                        <input type="text" name="start_time" class="input length_2 J_datetime" value="{$Think.get.start_time}" style="width:80px;">
                        -
                        <input type="text" class="input length_2 J_datetime" name="end_time" value="{$Think.get.end_time}" style="width:80px;">
                        订单来源：
                        <select class="select_2" name="ordersource" style="width:85px;">
                            <option value=""  <empty name="Think.get.ordersource">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.ordersource eq '1'"> selected</if>>手机web</option>
                            <option value="2" <if condition=" $Think.get.ordersource eq '2'"> selected</if>>App</option>
                            <option value="3" <if condition=" $Think.get.ordersource eq '3'"> selected</if>>饿了么</option>
                            <option value="4" <if condition=" $Think.get.ordersource eq '4'"> selected</if>>口碑外卖</option>
                            <option value="5" <if condition=" $Think.get.ordersource eq '5'"> selected</if>>售后订单</option>
                        </select>
                        订单类型：
                        <select class="select_2" name="ordertype" style="width:85px;">
                            <option value=""  <empty name="Think.get.ordertype">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.ordertype eq '1'"> selected</if>>一般订单</option>
                            <option value="2" <if condition=" $Think.get.ordertype eq '2'"> selected</if>>预购订单</option>
                            <option value="3" <if condition=" $Think.get.ordertype eq '3'"> selected</if>>企业订单</option>
                            <option value="4" <if condition=" $Think.get.ordertype eq '4'"> selected</if>>称重订单 </option>
                        </select>
                        支付方式：
                        <select class="select_2" name="paytype" style="width:85px;">
                            <option value=""  <empty name="Think.get.paytype">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.paytype eq '1'"> selected</if>>支付宝</option>
                            <option value="2" <if condition=" $Think.get.paytype eq '2'"> selected</if>>微信</option>
                            <option value="3" <if condition=" $Think.get.paytype eq '3'"> selected</if>>货到付款</option>
                        </select>
                        订单类型：
                        <select class="select_2" name="ordertype" style="width:85px;">
                            <option value=""  <empty name="Think.get.ordertype">selected</empty>>全部</option>
                            <option value="1" <if condition=" $Think.get.ordertype eq '1'"> selected</if>>普通订单</option>
                            <option value="2" <if condition=" $Think.get.ordertype eq '2'"> selected</if>>预购订单</option>
                            <option value="3" <if condition=" $Think.get.ordertype eq '3'"> selected</if>>企业订单</option>
                        </select>
                        包装状态：
                        <select class="select_2" name="package_status" style="width:85px;">
                            <option value=""  <empty name="Think.get.package_status">selected</empty>>全部</option>
                            <option value="0" <if condition=" $Think.get.package_status eq '0'"> selected</if>>待包装</option>
                            <option value="1" <if condition=" $Think.get.package_status eq '1'"> selected</if>>包装中</option>
                            <option value="2" <if condition=" $Think.get.package_status eq '2'"> selected</if>>包装完成</option>
                        </select>
                        订单号：
                        <input type="text" class="input length_2" name="keyword" style="width:200px;" value="{$Think.get.keyword}" placeholder="请输入订单号...">
                        <button class="btn">搜索</button>
                    </span>
                    </div>
            </div>
        </form>  -->
        <style>
            .tab_nav {
                padding:10px 15px 0;
                margin-bottom:10px;
                    margin-left: 27%;
            }
            .tab_nav ul{
                border-bottom:1px solid #e3e3e3;
                padding:0 5px;
                height:50px;
                clear:both;
            }
            .tab_nav ul li{
                float:left;
                margin-right: 70px;
                border: 1px s;
                /* border: 1px solid #e3e3e3; */
                border-bottom: 0 none;
                /* color: #333; */
                font-weight: 700;
                background: #fff;
                position: relative;
                border-radius: 10px;
                margin-bottom: -1px;
            }
            .tab_nav ul li a{
                float:left;
                display:block;
                padding:0 10px;
                height:25px;
                line-height:23px;
                    height: 40px;
                    line-height: 40px;
            }
            .tab_nav ul li.current a{
                /*border:1px solid #e3e3e3;*/
                border-bottom:0 none;
                color:#333;
                font-weight:700;
                background:#fff;
                position:relative;
                border-radius:2px;
                margin-bottom:-1px;
                height: 40px;
                line-height: 40px;
                border-radius: 10px;
            }
            .pop_cont{
                padding:0 15px;
            }
        </style>
        <div class="tab_nav" style="margin-bottom:0px">
            <ul class="J_tabs_nav1">
              <li class=""><a href="{:U('Admin/Package/index')}">全部订单</a><i>+{$allnum|default="0"}</i></li>
              <li class=""><a href="{:U('Admin/Package/waitpackage')}">待包装</a><i>+{$waitpackagenum|default="0"}</i></li>
              <li class=""><a href="{:U('Admin/Package/packaging')}">包装中</a><i>+{$packagingnum|default="0"}</i></li>
              <li class="current"><a href="{:U('Admin/Package/packagdone')}">包装完成</a><i>+{$packagdonenum|default="0"}</i></li>
            </ul>
        </div>

        <form action="{:U('Admin/Package/action')}" method="post" >
                <volist name="data" id="vo">
            <div class="liv_box">
                <div class="liv_list">
                    <div class="liv_top">
                        <div class="liv_a1 fl">
                          <input type="checkbox" class="J_check liv_check" data-yid="J_check_y" data-xid="J_check_x" name="ids[]" value="{$vo.id}">
                          <label><span>{$vo.inputtime|date="Y-m-d H:i:s",###}</span>订单号：{$vo.orderid}</label>
                        </div>
                        <div class="liv_a2 fl">[<a href="javascript:;" data-href="{:U('Admin/Order/download',array('orderid'=>$vo['orderid']))}" onclick="image_priview('{$vo.ordercode}');">查看二维码</a>]</div>
                        <div class="liv_a2 fl">下单账号：{:getuserinfo($vo['uid'])}</div>
                    </div>
                    <div class="liv_btm">
                        <div class="liv_left fl" style="width: 55%;">
                          <volist name="vo['productinfo']" id="v">
                            <div class="liv_b">
                                <div class="liv_c fl">
                                    <img src="{$v.thumb}"></div>
                                <div class="liv_d fl">
                                    <div class="liv_d1">
                                        <a href="">{$v.title}</a></div>
                                    <div class="liv_d2">
                                        <a href="">品牌：{$v.brand} 规格：{$v.standard}   单位：{:getunit($v['unit'])}</a>
                                      </div>
                                      <eq name="v['product_type']" value="4" >
                                        <div class="liv_d3">
                                          <if condition=" $v.isweigh eq 1"> 
                                                已称重[{$v.weightime|date="Y-m-d H:i:s",###}]
                                                <else /> 
                                                未称重
                                            </if>
                                        </div>
                                      </eq>
                                </div>

                                <div class="liv_e fl">
                                    <div class="liv_e1">{$v.oldprice}</div>
                                    <div class="liv_e2">{$v.nowprice}</div>
                                </div>
                                <div class="liv_f fl">x{$v.nums}</div>
                                <div class="liv_g fl">
                                    <div class="liv_g1">
                                      <if condition=" $v.product_type eq '0'"> [企业商品]</if>
                                      <if condition=" $v.product_type eq '1'"> [一般商品]</if>
                                      <if condition=" $v.product_type eq '2'"> [团购商品]</if>
                                      <if condition=" $v.product_type eq '3'"> [预购商品]</if>
                                      <if condition=" $v.product_type eq '4'"> [称重商品]</if>
                                    </div>
                                    
                                        <eq name="v['product_type']" value="4" >
                                        
                                          <if condition=" $v.isweigh eq 0"> 
                                            <input type="button" onClick="omnipotent('selectid','{:U('Admin/Package/weigh',array('id'=>$v['id']))}','填写称重信息',1,700,400)" value="需要称重">
                                            </if>
                                        
                                        </eq>
                                        
                                        
                                        
                                </div>
                            </div>
                          </volist>
                          <!--   <div class="over_a">
                                <div class="over_b fl">【{$vo.name}】{$vo.tel}</div>
                                <div class="over_c fl">{:getarea($vo['area'])}</div>
                                <div class="over_d fl">{$vo.areatext}{$vo.address}</div>
                                <div class="over_d fl" style="    margin-left: 10px;">{:getordersendtime($vo['orderid'],2)}</div>
                            </div> -->

                        </div>
                        <div class="liv_right fl" style="width:45%;">
                            <div class="fish_a fl">
                                
                                <div class="fish_a1">{$vo.total}<a href="javascript:;" onclick="lalert({$vo['money']},{$vo['wallet']},{$vo['discount']},{$vo['total']},{$vo['wait_money']},{$vo['yes_money']},{$vo['yes_money_total']},{$vo['pay_status']},{$vo['ordertype']});" class="info"><img src="__IMG__/info.png" style="width: 20px;display: inline-block;margin-bottom: -5px;"></a></div>
                                <div class="fish_a2">含运费（{$vo.delivery|default="0.00"}）</div>
                                <div class="fish_a3">
                                  {:getordersource($vo['ordersource'])}
                                </div>
                                <div class="fish_a3">
                                <eq name="vo['ordertype']" value="1">一般订单</eq>
                                <eq name="vo['ordertype']" value="2" >预购订单</eq>
                                <eq name="vo['ordertype']" value="3" >企业订单</eq>
                                <eq name="vo['ordertype']" value="4" >称重订单</eq>
                                </div>
                            </div>

                            <div class="fish_c fl">
                              <div class="fish_c1">
                                <if condition="$vo['status'] neq 5">
                                    <empty name="vo['puid']">
                                        待派发</br>
                                        <else />
                                        <eq name="vo['package_status']" value="0">已派发给【{:getAuser($vo['puid'])}】</br></eq>
                                        <eq name="vo['package_status']" value="1">【{:getAuser($vo['puid'])}】正在包装中</br></eq>
                                        <eq name="vo['package_status']" value="2">【{:getAuser($vo['puid'])}】包装完成</br></eq>
                                        <empty name="vo['ruid']">
                                          待配送</br>
                                          <else />
                                          <eq name="vo['delivery_status']" value="0">【{:getuser($vo['ruid'])}】待发货</br></eq>
                                          <eq name="vo['delivery_status']" value="1">【{:getuser($vo['ruid'])}】正在配送中</br></eq>
                                          <eq name="vo['delivery_status']" value="4">【{:getuser($vo['ruid'])}】配送完成</br></eq>
                                      </empty>
                                    </empty>
                                    
                                    <else />
                                    订单已完成<br/>{$vo.donetime|date="Y-m-d H:i:s",###}</br>
                                </if>
                              </div>
                                <div class="fish_c2">
                                  <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Order/show',array('orderid'=>$vo['orderid']))}','订单详情',1,700,400)">查看详情</a></br>
                                </div>
                                <div class="fish_c2">{:getpaystyle($vo['orderid'])}</div>
                                <div class="fish_c2">{:getpaystatus($vo['pay_status'],$vo['orderid'])}</div>
                            </div>

                                <div class="fish_d fl">
                                    <if condition="$vo.package_status eq 0">待包装[<a href="{:U('Admin/Package/package',array('orderid'=>$vo['orderid']))}">包装</a>]</if>
                                    <if condition="$vo.package_status eq 1">包装中[<a href="{:U('Admin/Package/packagedone',array('orderid'=>$vo['orderid']))}">包装完成</a>]</br>{:getAuser($vo['puid'])}</br>{$vo.package_time|date="Y-m-d H:i:s",###}</br></if>
                                    <if condition="$vo.package_status eq 2">包装完成</br>{:getAuser($vo['puid'])}</br>{$vo.package_donetime|date="Y-m-d H:i:s",###}</br></if>
                                    <eq name="vo['print_status']" value="0">
                                    <a href="javascript:;" onClick="omnipotent('selectid','{:U('Admin/Order/orderprint',array('orderid'=>$vo['orderid']))}','打印订单',1,390,250)">打印</a>
                                    <a href="{:U('Admin/Order/printorder',array('orderid'=>$vo['orderid']))}">更新打印状态</a>
                                    <else />
                                    已打印
                                </eq>
                                </div>

                            </div>
                        </div>
                        <div class="liv_btm">
                        <div class="over_b fl">【{$vo.name}】{$vo.tel}</div>
                        <div class="over_c fl">{:getarea($vo['area'])}</div>
                        <div class="over_d fl">{$vo.areatext}{$vo.address}</div>
                        <div class="over_d fl" style="    margin-left: 10px;">{:getordersendtime($vo['orderid'],2)}</div>
                    </div>
                    <div class="liv_btm">
                            <div class="over_b fl">【订单留言】:{$vo.buyerremark|default="无订单留言"}</div>
                        </div>
                        <div class="liv_btm">
                            <div class="over_b fl">【贺卡留言】:{$vo.cardremark|default="无贺卡留言"}</div>
                        </div>
                    </div>
                </div>
            </volist>
                <div class="p10">
                    <div class="pages"> {$Page} </div>
                    
                </div>
        </form>
            <div class="btn_wrap">
                <div class="btn_wrap_pd">
                    <if condition="authcheck('Admin/Package/excel')">
                        <form method="post" action="{:U('Admin/Package/excel')}">
                            <input type="hidden" value="1" name="search">
                            <input type="hidden" name="start_time" value="{$Think.get.start_time}" >
                            <input type="hidden"  name="end_time" value="{$Think.get.end_time}" >
                            <input type="hidden"  name="ordersource" value="{$Think.get.ordersource}" >
                            <input type="hidden"  name="paytype" value="{$Think.get.paytype}" >
                            <input type="hidden"  name="ordertype" value="{$Think.get.ordertype}" >
                            <input type="hidden"  name="package_status" value="{$Think.get.package_status}" >
                            <input type="hidden"  name="keyword" value="{$Think.get.keyword}" >
                            <button class="btn btn_submit mr10 " type="submit" name="submit" value="del">导出当前数据</button>
                        </form> 
                    </if>
                </div>
            </div>


    </div>

    <script src="__JS__/common.js?v"></script>
        <script src="__JS__/content_addtop.js"></script>
    <script>
        $(function () {
            $(".productshow a").click(function () {
                var href = $(this).attr("href");
                window.location.href = href;
                return false;
            })
             $(".productshow").click(function () {
                 var obj = "#product_" + $(this).data("id");
                 $(obj).toggle();
             })
            
        })
    </script>
</body>
</html>