<?php

namespace Api\Controller;

use Api\Common\CommonController;

class AutoDbController extends CommonController {
    /**
     * 文件指针
     * @var resource
     */
    protected $fp;

    /**
     * 备份文件信息 part - 卷号，name - 文件名
     * @var array
     */
    protected $file;

    /**
     * 当前打开文件大小
     * @var integer
     */
    protected $size = 0;

    /**
     * 备份配置
     * @var array
     */
    protected $config;

	var $runTime_1;
    function _initialize() {
		$this->runTime_1 = microtime(true);
        parent::_initialize();
		set_time_limit(0);
        $this->config = array(
                'path'     => realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR,
                'part'     => C('DATA_BACKUP_PART_SIZE'),
                'compress' => C('DATA_BACKUP_COMPRESS'),
                'level'    => C('DATA_BACKUP_COMPRESS_LEVEL'),
            );
        $this->file = array(
                'name' => date('Ymd-His', NOW_TIME),
                'part' => 1,
            );
    }
	/**
     * 打开一个卷，用于写入数据
     * @param  integer $size 写入数据的大小
     */
    private function open($size){
            $backuppath = $this->config['path'];
            $filename   = "{$backuppath}{$this->file['name']}-{$this->file['part']}.sql";
            if($this->config['compress']){
                $filename = "{$filename}.gz";
                $this->fp = @gzopen($filename, "a{$this->config['level']}");
            } else {
                $this->fp = @fopen($filename, 'a');
            }
        
    }

    /**
     * 执行备份
     */
    public function backupBackground(){
        $strOut="";
        $sql  = "-- -----------------------------\n";
        $sql .= "-- Think MySQL Data Transfer \n";
        $sql .= "-- \n";
        $sql .= "-- Host     : " . C('DB_HOST') . "\n";
        $sql .= "-- Port     : " . C('DB_PORT') . "\n";
        $sql .= "-- Database : " . C('DB_NAME') . "\n";
        $sql .= "-- \n";
        $sql .= "-- Part : #{$this->file['part']}\n";
        $sql .= "-- Date : " . date("Y-m-d H:i:s") . "\n";
        $sql .= "-- -----------------------------\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
        $this->writesql($sql);
        $strOut.="系统自动备份开始\r\n";
        $list  = M()->query("SHOW TABLE STATUS like 'zz_%'");
        foreach ($list as $value)
        {   
            $result = M()->query("SHOW CREATE TABLE `{$value['Name']}`");
            $sql  = "\n";
            $sql .= "-- -----------------------------\n";
            $sql .= "-- Table structure for `{$value['Name']}`\n";
            $sql .= "-- -----------------------------\n";
            $sql .= "DROP TABLE IF EXISTS `{$value['Name']}`;\n";
            $sql .= trim($result[0]['Create Table']) . ";\n\n";
            $this->writesql($sql);
            $strOut.="数据表{$value['Name']}结构备份成功\r\n";

        	$sql  = "-- -----------------------------\n";
            $sql .= "-- Records of `{$value['Name']}`\n";
            $sql .= "-- -----------------------------\n";
            $this->writesql($sql);

            $result = M()->query("SELECT COUNT(*) AS count FROM `{$value['Name']}`");
            $count  = $result['0']['count'];
            $i=0;
            do{
                $data = M()->query("SELECT * FROM `{$value['Name']}` LIMIT {$i}, 1000");
                foreach ($data as $row) {
                    $row = array_map('mysql_real_escape_string', $row);
                    $sql = "INSERT INTO `{$value['Name']}` VALUES ('" . implode("', '", $row) . "');\n";
                    $this->writesql($sql);
                }
                unset($data);
                ob_flush();
                flush();
                $i+=1000;
            }while($i<$count);
            $strOut.="数据表{$value['Name']}数据备份成功\r\n";
        }
        $strOut.="系统自动备份结束\r\n";;
        $log = array(
	        "task_info" => $strOut,
	        "add_time" => date("Y-m-d H:i:s"),
	        "run_time" => (microtime(true)-$this->runTime_1)
	    );
	    M("task_log")->add($log);
        
    }

    /**
     * 写入SQL语句
     * @param  string $sql 要写入的SQL语句
     *  boolean     true - 写入成功，false - 写入失败！
     */
    private function writesql($sql){
        $size = strlen($sql);
        
        //由于压缩原因，无法计算出压缩后的长度，这里假设压缩率为50%，
        //一般情况压缩率都会高于50%；
        $size = $this->config['compress'] ? $size / 2 : $size;
        
        $this->open($size); 
        return $this->config['compress'] ? @gzwrite($this->fp, $sql) : @fwrite($this->fp, $sql);
    }
	/**
     * 析构方法，用于关闭文件资源
     */
    public function __destruct(){
        $this->config['compress'] ? @gzclose($this->fp) : @fclose($this->fp);
    }
}


