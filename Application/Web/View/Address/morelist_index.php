<volist name="data" id="vo">
	<div class="map_list" data-lat="{$vo.point.y}" data-lng="{$vo.point.x}" data-name="{$vo.name}">
          <div class="map_a">
          	<eq name="key" value="0"><div class="map_img"><img src="__IMG__/img.png"></div></eq>
	          <span>{$vo.name}</span>
	      </div>
          <div class="map_b">{$vo.addr}</div>
     </div>
</volist>