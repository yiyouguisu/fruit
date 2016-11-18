<volist name="data" id="vo">
	<div class="map_list" data-lat="{$vo.location.lat}" data-lng="{$vo.location.lng}" data-name="{$vo.name}">
          <div class="map_a">
	          {$vo.name}
	      </div>
          <div class="map_b">{$vo.addr}</div>
     </div>
</volist>