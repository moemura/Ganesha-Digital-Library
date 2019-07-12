<?php
/***************************************************************************
                          db.php  -  Database Object
                             -------------------
    begin                : August 06, 2004
    copyright            : (C) 2004 Hayun Kusumah, KMRG ITB
    email                : hayun@kmrg.itb.ac.id

 ***************************************************************************/
if (eregi("db.php",$_SERVER['PHP_SELF'])) {
    die();
}

class database { 
	var $print_script;
	var $prefix="";
	
	function database($gdl_db_conf=""){
		global $gdl_err,$gdl_content;
		
		include ("./config/db.php");
		@mysql_connect($gdl_db_conf['host'], $gdl_db_conf['uname'], $gdl_db_conf['password']);
		@mysql_select_db($gdl_db_conf['name']) or $gdl_content->set_error("Unable to select database","Error Connection");
		$this->prefix=$gdl_db_conf['prefix'];
	}
	
	function test_connection() {
		include ("./config/db.php");
		if (!@mysql_connect($gdl_db_conf['host'], $gdl_db_conf['uname'], $gdl_db_conf['password']))
			return "err";
	}
	
	function create_db($db_name) {
		$str_sql="create database `".$db_name."`;";
		$db_result=@mysql_query($str_sql);
		
		return $db_result;
	}
	
	
	function tables($tables){
		if (!empty($this->prefix))
			$prefix=$this->prefix."_";
			
			$table = explode(",",$tables);
			if (is_array($table)) {
				while (list($key,$val) = each($table)){
					$tab=explode(" ",$val);
					if (is_array($tab)) {
						$tabname=$tab[0];
						$tabalias=$tab[1];
					}
						
					if ($key<>0) $result .=",";
						$result .= "`".$prefix."$tabname"."` $tabalias";
				}
			}else{
				$result = "`".$prefix."$table`";
			}
			return $result;

	}
	
	function select($tables,$fields,$where="",$orderby="",$sort="",$limit="",$groupby="") { 
		$str_sql=" select $fields from ".$this->tables($tables);  
		if (!empty($where)) $str_sql.=" where $where"; 
		if(!empty($orderby)) {
			$arr_order 	= explode(",",$orderby);
			$arr_sort	= explode(",",$sort);
			
			$count = count($arr_order);
			for($i=0;$i<$count;$i++){
				if($i == 0)
					$str_order .= "$arr_order[$i]  $arr_sort[$i] ";
				else
					$str_order .= ",$arr_order[$i]  $arr_sort[$i] ";
			}
			$str_sql .= " order by $str_order ";
		}
		//if (!empty($orderby)) $str_sql.=" order by $orderby"; 
		//if (!empty($sort)) $str_sql.=" $sort";  
		if (!empty($limit)) $str_sql.=" limit $limit";
		if (!empty($groupby)) $str_sql.=" group by $groupby"; 

		$db_result = @mysql_query($str_sql);
		if($this->print_script){ 
			echo $str_sql;
			echo mysql_error();
		}
		return $db_result;
	}
	
	function insert($table,$fields="",$values="") { 
		$str_sql="insert into ".$this->tables($table);  
		if (!empty($fields)) $str_sql .=" ($fields)";
		if (!empty($values)) $str_sql .=" values($values)";
		$db_result = @mysql_query($str_sql);
		//echo $str_sql."<br>";
		//if (mysql_error())
		// echo mysql_error()."<br/><b>$str_sql</b><br/>";
		
		return $db_result;
	}  
  
	function update($table,$newvals,$where="") {  
		$str_sql="update ".$this->tables($table)." set $newvals";  
		if (!empty($where)) $str_sql.=" where $where";
		$db_result = @mysql_query($str_sql); 
/*		echo $str_sql."<br>";
		echo mysql_error();*/
		return $db_result; 
	}  
		  
	function delete($table,$where="") {
		$str_sql="delete from ".$this->tables($table);  
		if (!empty($where)) $str_sql.=" where $where "; 
		$db_result = @mysql_query($str_sql);
		return $db_result;
	}  

}

?>
