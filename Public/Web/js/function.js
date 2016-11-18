$(function(){
    $(".addIcon").click(function(){
      var va=$(this).siblings(".number"),cnt=va.val(),ca=parseInt(cnt),ca=ca+1;va.val(ca);
    });
    $(".redIcon").click(function(){
      var va=$(this).siblings(".number"),cnt=va.val(),ca=parseInt(cnt),ca=ca-1;
      if(ca<1){alert("请输入不要小于1的整数"); ca=1;va.val(ca);}else{va.val(ca);}
    });
})
function ajax_collect(item_id, chanel, obj) {
      if(chanel==1){
        url="/index.php/Web/Product/ajax_collect.html";
        if($.cookie('ProductsCollect')){
          if($.cookie('ProductsCollect').split(',').indexOf(item_id+'')!=-1){
            alert('您已收藏过此商品，请勿重复收藏。');
            return; 
          }
        }
      }else if(chanel==2){
        url="/index.php/Web/Store/ajax_collect.html";
        if($.cookie('StoresCollect')){
          if($.cookie('StoresCollect').split(',').indexOf(item_id+'')!=-1){
            alert('您已收藏过此商铺，请勿重复收藏。');
            return; 
          }
        }
      }
      $.ajax({
          url: url,
          type: "post",
          data: {"item_id":item_id,"chanel":chanel},
          dataType: "json",
          success: function(data) {
              var d = eval(data);
              if (d.status == 1) {
                if(chanel==1){
                  var ps = $.cookie('ProductsCollect');
                  if (ps) {
                      $.cookie('ProductsCollect', ps + ',' + item_id);
                  }else {
                      $.cookie('ProductsCollect', item_id);
                  }
                }else if(chanel==2){
                  var ps = $.cookie('StoresCollect');
                  if (ps) {
                      $.cookie('StoresCollect', ps + ',' + item_id);
                  }else {
                      $.cookie('StoresCollect', item_id);
                  }
                }
                $(obj).attr('disabled',false);
                alert(d.msg);
              } else {
                alert(d.msg);
                return false;
              }
          }
      });
  }