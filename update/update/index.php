<?php
//字段修改记录
header("Content-type: text/html; charset=utf-8");
$root_path="../../";
$vs=require($root_path.'Conf/version.php');
$current_banben=$vs['release'];
$update_banben='20140930.1';
$need_banben='20140930';
$ver='2.3';

if($current_banben<$need_banben){
	exit('请升级到'.$need_banben.'后再升级此补丁');
}
if($current_banben>=$update_banben){
	exit('您已经升级过:<b> V</b>'.$ver.' <'.$update_banben.'> 版本了！<a href="javascript:history.go(-1);">返回上一页</a>');
}
$arr = require($root_path."Conf/db.php");
$dbpre=$arr['DB_PREFIX'];
$conn =mysql_connect($arr['DB_HOST'],$arr['DB_USER'],$arr['DB_PWD']) or die("连接数据库失败!");
mysql_select_db($arr['DB_NAME'],$conn);
mysql_query("set names utf8");
// (`tplid`, `name`, `views`, `type`, `imgurl`, `info`, `attr`, `status`, `sort`)
echo '更新开始...<br>';
if(!pdo_fieldexists("{$dbpre}dining_company",'sctime')){
	$sql=mysql_query("ALTER TABLE `{$dbpre}dining_company` ADD `sctime`  text COMMENT '送餐时间';");
	if($sql){
		echo '店铺送餐时间功能添加成功';
	}else{
		echo '店铺送餐时间功能添加失败';
	}
	echo "</br> ";
}
if(!pdo_fieldexists("{$dbpre}wxuser","shoplistid")){
	$sql=mysql_query("ALTER TABLE `{$dbpre}wxuser` ADD `shoplistid` varchar(10) NOT NULL COMMENT '商城列表模版ID', ADD `shoplistname` varchar(20) NOT NULL COMMENT '商城列表模版名';");
	if($sql){
		echo '商城字段增加成功';
	}else{
		echo '商城字段增加失败';
	}  
	echo "</br> ";
}

echo '创建数据表开始...<br>';
$sqlfile = 'update.sql';
$sqls = _get_sql($sqlfile);
foreach ($sqls as $sql) {
	//替换前缀
	$sql = str_replace('`zfwx_', '`' . $dbpre, $sql);
	$run = mysql_query($sql, $conn);
	//获得表名
	if (substr($sql, 0, 12) == 'CREATE TABLE') {
		$table_name = $dbpre . preg_replace("/CREATE TABLE IF NOT EXISTS `" . $dbpre . "([a-z0-9_]+)` .*/is", "\\1", $sql);
		echo $table_name.'创建成功...<br>';
	}
	//获得表名
	if (substr($sql, 0, 11) == 'INSERT INTO') {
		$table_name2 = $dbpre . preg_replace("/INSERT INTO `" . $dbpre . "([a-z0-9_]+)` .*/is", "\\1", $sql);
		echo $table_name2.'插入数据成功...<br>';
	}
}


file_put_contents($root_path.'data/update.log',$update_banben.' '.date('Y-m-d H:i:s')."\r\n",FILE_APPEND);
config_edit($ver,$update_banben,$root_path);
echo "<br>执行更新完成，开始清除缓存！";
echo "<br>缓存路径：/data/runtime";
$delrunlog=delrun($root_path.'data/runtime');
echo $delrunlog;
echo "<br>缓存清理完成！本次更新结束！！";
function _get_sql($sql_file) {
	$contents = file_get_contents($sql_file);
	$contents = str_replace("\r\n", "\n", $contents);
	$contents = trim(str_replace("\r", "\n", $contents));
	$return_items = $items = array();
	$items = explode(";\n", $contents);

	foreach ($items as $item) {
		$return_item = '';
		$item = trim($item);
		$lines = explode("\n", $item);
		foreach ($lines as $line) {
			if (isset($line[1]) && $line[0] . $line[1] == '--') {
				continue;
			}
			$return_item .= $line;
		}
		if ($return_item) {
			$return_items[] = $return_item; //.";";
		}
	}
	return $return_items;
}
//写入config文件
function config_edit($ver,$release,$root_path) {
	$config_file = $root_path.'Conf/version.php';
	$config_ver = include($config_file);
	$config_ver['ver']=$ver;
	$config_ver['release']=$release;
	if (phpversion() > 5.0){
		@file_put_contents($config_file, "<?php\nreturn ".var_export($config_ver,true).";\n?>");
	}
}
//验证数据字段
function pdo_fieldexists($tablename, $fieldname = '') {
	$isexists = mysql_query("DESCRIBE ".$tablename." `".$fieldname."`");
	$isexists = mysql_fetch_array($isexists);
	return !empty($isexists) ? true : false;
}
//判断属性值是否存在
function db_fieldvalue($tablename, $fieldname = '',$value='') {
	$isexists = mysql_fetch_array(mysql_query("select count(*) from ".$tablename." where ".$fieldname." = '".$value."'"));
	return $isexists[0]?true:false;
}
//检测表是否存在
function existTable($table){
	return mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table."'"))?true:false;
}
//clearRuntime
function delrun($rundir) {
    //先删除目录下的文件：
    $dh=opendir($rundir);
	$delstr='清理缓存开始..';
    while($file=readdir($dh)){
        if($file!="."&&$file!=".."){
            $fullpath=$rundir."/".$file;
            if(is_dir($fullpath)){
               $deldir= delrundir($fullpath);
				$delstr.="</br>文件夹：";
				$delstr.= $deldir ?"{$fullpath}删除成功！":"{$fullpath}删除失败！";
			}else{
                $delfile=unlink($fullpath);
				$delstr.="</br>文件：";
				$delstr.= $delfile?"{$fullpath}删除成功！":"{$fullpath}删除失败！";
            }
        }        
    }
    closedir($dh);
	return $delstr;
}
function delrundir($rundir){
    //先删除目录下的文件：
    $dh=opendir($rundir);
    while($file=readdir($dh)){
        if($file!="."&&$file!=".."){
            $fullpath=$rundir."/".$file;
            if(is_dir($fullpath)){
                delrundir($fullpath);
            }else{
                unlink($fullpath);
            }
        }        
    }
    closedir($dh);
    //删除目录文件夹
    if(rmdir($rundir)){
        return  true;
    }else{
        return false;
    }   
}
?>	