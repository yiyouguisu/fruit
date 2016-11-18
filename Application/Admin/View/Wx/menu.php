<include file="Common:Head" />
<link href="__CSS__/bootstrap.min.css" rel="stylesheet">
    <link href="__CSS__/font-awesome.min.css" rel="stylesheet">
    <link href="__CSS__/common.css" rel="stylesheet">
    <script>var require = { urlArgs: 'v=<?php  echo date('YmdH');?>' };</script>
    <script src="__JS__/lib/jquery-1.11.1.min.js"></script>
    <script src="__JS__/app/util.js"></script>
    <script src="__JS__/require.js"></script>
    <script src="__JS__/app/config.js"></script>
<link href="__CSS__/app.css" rel="stylesheet">
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <include file="Common:Nav"/>
        <!-- -->
        <div class="h_a">微信公众号菜单配置</div>
        <div class="table_full">
             <form method='post' class="J_ajaxForm"  id="myform" action="{:U('Admin/Wx/menu')}">
                <div class="conditionMenu" ng-controller="conditionMenuDesigner" id="conditionMenuDesigner">
                <div class="app clearfix">
                    <div class="app-preview">
                        <div class="app-header"></div>
                        <div class="app-content">
                            <div class="inner">
                                <div class="title">
                                    <h1><span>{{context.group.type == 3 ? "个性化菜单" : "默认菜单"}}</span></h1>
                                </div>
                            </div>
                            <div class="nav-menu">
                                <div class="js-quickmenu nav-menu-wx clearfix" ng-class="{0 : 'has-nav-0', 1 : 'has-nav-1', 2: 'has-nav-2', 3: 'has-nav-3', 4 : 'has-nav-3'}[context.group.button.length + 1]">
                                    <ul class="nav-group designer-x">
                                        <li class="nav-group-item js-sortable" ng-repeat="but in context.group.button" ng-class="{0 : '', 1 : 'active'}[context.activeItem == but ? 1 : 0 ]">
                                            <input type="hidden" data-role="parent" data-hash="{{but.$$hashKey}}"/>
                                            <a href="javascript:void(0);" title="拖动排序" ng-click="context.editBut('', but);">
                                                <i class="fa fa-minus-circle" ng-show="but.sub_button.length > 0"></i>
                                                {{but.name}}
                                            </a>
                                            <dl class="designer-y">
                                                <dd ng-repeat="subBut in but.sub_button"  ng-class="{0 : '', 1 : 'active'}[context.activeItem == subBut ? 1 : 0 ]">
                                                    <input type="hidden" data-role="sub" data-hash="{{subBut.$$hashKey}}"/>
                                                    <a href="javascript:void(0)" ng-click="context.editBut(subBut, but);">{{subBut.name}}</a>
                                                </dd>
                                                <dd ng-if="but.sub_button.length < 5" class="js-not-sortable">
                                                    <a href="javascript:void(0)" ng-click="context.addSubBut(but);"><i class="fa fa-plus"></i></a>
                                                </dd>
                                            </dl>
                                        </li>
                                        <li class="nav-group-item" class="js-not-sortable"ng-if="context.group.button.length < 3" ng-hide="context.group.disabled">
                                            <a href="javascript:void(0);" ng-click="context.addBut();" class="text-success">
                                                <i class="fa fa-plus"></i> 添加菜单
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="app-side">
                        <div class="menu app-conditionMenu-edit">
                            <div class="arrow-left"></div>
                            <div class="inner">
                                <div class="panel panel-default">
                                    <div class="panel-body form-horizontal">
                                        <div class="conditionMenu-wx">
                                            <div class="card">
                                                <div class="nav-region">
                                                    <div class="first-nav">
                                                        <h3>标题</h3>
                                                        <div class="alert">
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-2">标题</label>
                                                                <div class="col-xs-10">
                                                                    <input type="text" class="form-control" ng-model="context.group.title" ng-disabled="context.group.disabled"/>
                                                                    <span class="help-block">仅用于区分个性化菜单</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php  if($type == 3) { ?>
                                        <div class="alert alert-danger" style="background:#ebccd1">
                                            <i class="fa fa-info-circle"></i> 个性化菜单一旦上架提交后,将不可更改.如果您不需要某个菜单,您可以删除该菜单后重新设置新菜单.<br>
                                        </div>
                                        <div class="conditionMenu-wx">
                                            <div class="card">
                                                <div class="nav-region">
                                                    <div class="first-nav">
                                                        <h3>匹配设置</h3>
                                                        <div class="alert">
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-2">性别</label>
                                                                <div class="col-xs-10">
                                                                    <label class="radio-inline">
                                                                        <input type="radio" ng-model="context.group.matchrule.sex" value="0" ng-disabled="context.group.disabled"> 不限
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" ng-model="context.group.matchrule.sex" value="1" ng-disabled="context.group.disabled"> 男
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" ng-model="context.group.matchrule.sex" value="2" ng-disabled="context.group.disabled"> 女
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group fans-group">
                                                                <label class="control-label col-xs-2">粉丝分组</label>
                                                                <div class="col-xs-10">
                                                                    <label class="radio-inline"><input type="radio" name="group" value="-1" ng-model="context.group.matchrule.group_id" ng-disabled="context.group.disabled"/> 不限</label>
                                                                    <?php  if(is_array($groups)) { foreach($groups as $group) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="group" value="<?php  echo $group['id'];?>" ng-model="context.group.matchrule.group_id" ng-disabled="context.group.disabled"/> <?php  echo $group['name'];?></label>
                                                                    <?php  } } ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-2">客户端</label>
                                                                <div class="col-xs-10">
                                                                    <label class="radio-inline">
                                                                        <input type="radio" ng-model="context.group.matchrule.client_platform_type" value="0" ng-disabled="context.group.disabled"> 不限
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" ng-model="context.group.matchrule.client_platform_type" value="1" ng-disabled="context.group.disabled"> IOS(苹果)
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" ng-model="context.group.matchrule.client_platform_type" value="2" ng-disabled="context.group.disabled"> Android(安卓)
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" ng-model="context.group.matchrule.client_platform_type" value="3" ng-disabled="context.group.disabled"> Others(其他)
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-2">地区限制</label>
                                                                <div class="col-xs-10">
                                                                    <divstyle="margin-top:15px">
                                                                    <div class="row row-fix tpl-district-container">
                                                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                            <select name="" data-value="<?php  echo $menu['data']['matchrule']['province'];?>" ng-model="context.group.matchrule.province" ng-disabled="context.group.disabled" class="form-control tpl-province">
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                            <select name="" data-value="<?php  echo $menu['data']['matchrule']['city'];?>" ng-model="context.group.matchrule.city" ng-disabled="context.group.disabled" class="form-control tpl-city">
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php  } ?>
                                        <div class="card" ng-if="context.group.button.length > 0">
                                            <div class="btns">
                                                <a href="javascript:;" ng-click="context.removeBut(context.activeItem, context.activeType)"><i class="fa fa-times"></i></a>
                                            </div>
                                            <div class="nav-region">
                                                <div class="first-nav">
                                                    <h3>菜单设置</h3>
                                                    <div class="alert">
                                                        <div class="form-group">
                                                            <label class="control-label col-xs-2">菜单名称</label>
                                                            <div class="col-xs-10">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" name="" id="title" ng-model="context.activeItem.name" ng-disabled="context.group.disabled"/>
                                                                    <div class="input-group-btn">
                                                                        <span class="btn btn-primary" ng-click="context.selectEmoji();" ng-disabled="context.group.disabled"><i class="fa fa-github-alt"></i> 添加表情</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group" ng-if="context.activeType == 2 || (context.activeType == 1 && context.activeItem.sub_button.length == 0)">
                                                            <label class="control-label col-xs-2">菜单动作</label>
                                                            <div class="col-xs-10 menu-action">
                                                                <span <?php  if($_W['account']['level'] == '1') { ?>style="display:none;"<?php  } ?>>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="ipt" ng-model="context.activeItem.type" value="view" ng-disabled="context.group.disabled"> 链接
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="ipt" ng-model="context.activeItem.type" value="click" ng-disabled="context.group.disabled"> 触发关键字
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="ipt" ng-model="context.activeItem.type" value="scancode_push" ng-disabled="context.group.disabled"> 扫码
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="ipt" ng-model="context.activeItem.type" value="scancode_waitmsg" ng-disabled="context.group.disabled"> 扫码（等待信息）
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="ipt" ng-model="context.activeItem.type" value="pic_sysphoto" ng-disabled="context.group.disabled"> 系统拍照发图
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="ipt" ng-model="context.activeItem.type" value="pic_photo_or_album" ng-disabled="context.group.disabled"> 拍照或者相册发图
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="ipt" ng-model="context.activeItem.type" value="pic_weixin" ng-disabled="context.group.disabled"> 微信相册发图
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="ipt" ng-model="context.activeItem.type" value="location_select" ng-disabled="context.group.disabled"> 地理位置
                                                                </label>
                                                                </span>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="ipt" ng-model="context.activeItem.type" value="media_id" ng-disabled="context.group.disabled"> 回复素材
                                                                </label>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="ipt" ng-model="context.activeItem.type" value="view_limited" ng-disabled="context.group.disabled"> 跳转图文
                                                                </label>
                                                                <div <?php  if($_W['account']['level'] == '1') { ?>style="display:none;"<?php  } ?>ng-show="context.activeItem.type == 'view';">
                                                                    <hr />
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="ipt-U" type="text" ng-model="context.activeItem.U" ng-disabled="context.group.disabled"/>
                                                                        <div class="input-group-btn">
                                                                            <button class="btn btn-primary" id="search" ng-click="context.select_link()" ng-disabled="context.group.disabled"><i class="fa fa-external-link"></i> 系统链接</button>
                                                                        </div>
                                                                    </div>
                                                                    <span class="help-block">指定点击此菜单时要跳转的链接（注：链接需加http://）</span>
                                                                    <span class="help-block"><strong>注意: 由于接口限制. 如果你没有网页oAuth接口权限, 这里输入链接直接进入微站个人中心时将会有缺陷(有可能获得不到当前访问用户的身份信息. 如果没有oAuth接口权限, 建议你使用图文回复的形式来访问个人中心)</strong></span>
                                                                </div>
                                                                <div <?php  if($_W['account']['level'] != '1') { ?>ng-show="context.activeItem.type == 'media_id' || context.activeItem.type == 'view_limited';"<?php  } ?>>
                                                                    <hr />
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="ipt-U" type="text" ng-model="context.activeItem.media_id" ng-disabled="context.group.disabled"/>
                                                                        <div class="input-group-btn">
                                                                            <button class="btn btn-primary" id="media_id" ng-click="context.select_mediaid()" ng-disabled="context.group.disabled"><i class="fa fa-external-link"></i> 选择素材</button>
                                                                        </div>
                                                                    </div>
                                                                    <span class="help-block">公众平台的素材id</span>
                                                                </div>
                                                                <div ng-show="context.activeItem.type != 'view' && context.activeItem.type != 'media_id' && context.activeItem.type != 'view_limited'" style="position:relative;">
                                                                    <hr />
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="ipt-forward" type="text" ng-model="context.activeItem.key" ng-disabled="context.group.disabled"/>
                                                                        <div class="input-group-btn">
                                                                            <button class="btn btn-primary" id="search" ng-click="context.search()" ng-disabled="context.group.disabled"><i class="fa fa-search"></i> 搜索</button>
                                                                        </div>
                                                                    </div>
                                                                    <div id="key-result" style="width:100%;position:absolute;top:55px;left:0px;display:none;z-index:10000">
                                                                        <ul class="dropdown-menu" style="display:block;width:88%;"></ul>
                                                                    </div>
                                                                    <span class="help-block">指定点击此菜单时要执行的操作, 你可以在这里输入关键字, 那么点击这个菜单时就就相当于发送这个内容至微擎系统</span>
                                                                    <span class="help-block"><strong>这个过程是程序模拟的, 比如这里添加关键字: 优惠券, 那么点击这个菜单是, 微擎系统相当于接受了粉丝用户的消息, 内容为"优惠券"</strong></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input  type="hidden" name="menu" id="menu"/>
                <div class="shop-preview col-xs-12 col-sm-9 col-lg-10">
                    <div class="text-center alert alert-warning" style="background:#faebcc">
                            <span class="btn btn-primary" id="btn-submit" ng-click="context.submit();">上架</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="__JS__/common.js?v"></script>
    <script type="text/javascript">
    require(['angular', 'underscore', 'jquery.ui', 'jquery.caret', 'wechatDistrict'], function(angular, _, $, $, dis){
    
    angular.module('app', []).controller('conditionMenuDesigner', function($scope, $http){
        $scope.context = {};
        $scope.context.group = <?php echo !empty($params) ? json_encode($params) : "null"?>;

        if(!$scope.context.group) {
            $scope.context.group = {
                title: '标题',
                type: "<?php  echo $type;?>",
                button: [{
                    name: '菜单名称',
                    type: 'U',
                    U: '',
                    key: '',
                    media_id : '',
                    sub_button: []
                }]
            };
            $scope.context.group.title = '个性化菜单';

        }
        $scope.context.activeIndex = 0;
        $scope.context.activeBut = $scope.context.group['button'][$scope.context.activeIndex];
        $scope.context.activeItem = $scope.context.activeBut;
        $scope.context.activeType = 1; //标识一级菜单
        
        $scope.context.submit = function(){
            var group = $scope.context.group;
            group.button = _.sortBy(group.button, function(h){
                var elm = $(':hidden[data-role="parent"][data-hash="' + h.$$hashKey + '"]');
                return elm.parent().index();
            });
            angular.forEach(group.button, function(j){
                j.sub_button = _.sortBy(j.sub_button, function(h){
                    var e = $(':hidden[data-role="sub"][data-hash="' + h.$$hashKey + '"]');
                    return e.parent().index();
                });
            });

            if(!$.trim(group.title)) {
                alert('没有设置标题');
                return false;
            }
            if(group.button.length < 1) {
                alert('没有设置菜单');
                return false;
            }
            var error = {name: '', action: ''};
            angular.forEach(group.button, function(val, index){
                if($.trim(val.name) == '') {
                    this.name += '第' + (index + 1) + '个一级菜单未设置菜单名称 \r\n';
                }
                if(val.sub_button.length > 0) {
                    angular.forEach(val.sub_button, function(v, index1){
                        if($.trim(v.name) == '') {
                            this.name += '第' + (index + 1) + '个一级菜单中的第' + (index1 + 1) + '个二级菜单未设置菜单名称 \r\n';
                        }
                        if((v.type == 'view' && $.trim(v.U) == '') || ((v.type != 'view' && v.type != 'media_id' && v.type != 'view_limited') && $.trim(v.key) == '') || ((v.type == 'media_id' || v.type == 'view_limited') && !$.trim(v.media_id))) {
                            this.action += '菜单【' + val.name + '】的子菜单【' + v.name + '】未设置操作选项. \r\n';
                        }
                    }, error);
                } else {
                    //console.dir(val);
                    if((val.type == 'view' && $.trim(val.U) == '') || ((val.type != 'view' && val.type != 'media_id' && val.type != 'view_limited') && $.trim(val.key) == '') || ((val.type == 'media_id' || val.type == 'view_limited') && !$.trim(val.media_id))) {
                        this.action += '菜单【' + val.name + '】不存在子菜单并且未设置操作选项.  \r\n';
                    }
                }
            }, error);

            if(error.name) {
                alert(error.title);
                return;
            }
            if(error.action) {
                alert(error.action);
                return;
            }
            //$('#btn-submit').attr('disabled', true);
            $("#menu").val(group);
            $("#myform").submit();
            // $http.post("{:U('Admin/Wx/menu')}", {group: group}).success(function(dat){
            //     if(dat.status == 0) {
            //        // $('#btn-submit').attr('disabled', false);
            //         alert(dat.info);
            //     } else {
            //         console.log(dat.menu);
            //         alert("创建菜单成功");
            //     }
            // });
        }

        $scope.context.triggerActiveBut = function(but){
            var index = $.inArray(but, $scope.context.group.button);
            if(index == -1) return false;
            $scope.context.activeIndex = index;
            $scope.context.activeBut = $scope.context.group['button'][$scope.context.activeIndex];
            $scope.context.activeItem = $scope.context.activeBut;
            $scope.context.activeType = 1;
        };

        $scope.context.editBut = function(subbut, but){
            $scope.context.triggerActiveBut(but);
            if(!subbut) {
                $scope.context.activeItem = but;
                $scope.context.activeType = 1;
            } else {
                $scope.context.activeItem = subbut;
                $scope.context.activeType = 2;
            }
        };

        $scope.context.addBut = function(){
            if($scope.context.group['button'].length >= 3) {
                return;
            }
            $scope.context.group['button'].push({
                name: '菜单名称',
                type: 'view',
                U: '',
                key: '',
                media_id : '',
                sub_button: []
            });
            var but = $scope.context.group['button'][$scope.context.group.button.length - 1];
            $scope.context.triggerActiveBut(but);
            $('.designer-x').sortable({
                items: '.js-sortable',
                axis: 'x'
            });
        }

        $scope.context.removeBut = function(but, type){
            if(type == 1) {
                if(!confirm('将同时删除所有子菜单,是否继续')) {
                    return false;
                }
                $scope.context.group.button = _.without($scope.context.group.button, but);
                $scope.context.triggerActiveBut($scope.context.group['button'][0]);
            } else {
                $scope.context.activeBut.sub_button = _.without($scope.context.activeBut.sub_button, but);
                $scope.context.triggerActiveBut($scope.context.activeBut);
            }
        };

        $scope.context.addSubBut = function(but){
            if($scope.context.group.disabled == 1) {
                return false;
            }
            $scope.context.triggerActiveBut(but);
            if($scope.context.activeBut.sub_button.length >= 5) {
                return;
            }
            $scope.context.activeBut.sub_button.push({
                name: '子菜单名称',
                type: 'U',
                U: '',
                key: '',
                media_id : ''
            });
            $('.designer-y').sortable({
                items: 'dd',
                axis: 'y',
                cancel: '.js-not-sortable'
            });
            $scope.context.activeItem = $scope.context.activeBut.sub_button[$scope.context.activeBut.sub_button.length - 1];
            $scope.context.activeType = 2;
        }
    });
    angular.bootstrap($('#conditionMenuDesigner')[0], ['app']);
    $(function(){
        $('.designer-y').sortable({
            items: 'dd',
            axis: 'y',
            cancel: '.js-not-sortable'
        });

        $('.designer-x').sortable({
            items: '.js-sortable',
            axis: 'x'
        });
    });
});
</script>
</body>
</html>