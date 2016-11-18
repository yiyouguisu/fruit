<?php
namespace Admin\Model;
use  Admin\Model\CommonModel;
class ConfigModel extends CommonModel {
    
      /**
     * 更新网站配置项
     * @param type $data 数据
     * @return boolean
     */
    public function saveConfig($data) {
        if (empty($data) || !is_array($data)) {
            $this->error = '配置数据不能为空！';
            return false;
        }
        foreach ($data as $key => $value) {
            if (empty($key)) {
                continue;
            }
            $saveData = array();
            $saveData["value"] = trim($value);
            if ($this->where(array("varname" => $key))->save($saveData) === false) {
                $this->error = "更新到{$key}项时，更新失败！";
                return false;
            }
        }
        return true;
    }
    /**
     * 更新缓存
     * @return type
     */
    public function config_cache() {
        $data = $this->order(array('id'=>'desc'))->select();
        F("web_config", $data);
        return $data;
    }

    /**
     * 后台有更新/编辑则删除缓存
     * @param type $data
     */
    public function _before_write($data) {
        parent::_before_write($data);
        F("web_config", NULL);
    }

    //删除操作时删除缓存
    public function _after_delete($data, $options) {
        parent::_after_delete($data, $options);
        $this->config_cache();
    }

    //更新数据后更新缓存
    public function _after_update($data, $options) {
        parent::_after_update($data, $options);
        $this->config_cache();
    }

    //插入数据后更新缓存
    public function _after_insert($data, $options) {
        parent::_after_insert($data, $options);
        $this->config_cache();
    }
  
}
