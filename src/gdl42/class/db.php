<?php
/***************************************************************************
                          db.php  -  Database Object
                             -------------------
    begin                : August 06, 2004
    copyright            : (C) 2004 Hayun Kusumah, KMRG ITB
    email                : hayun@kmrg.itb.ac.id

 ***************************************************************************/
if (preg_match("/db.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

class database { 
	var $print_script;
	var $prefix="";
	var $con;
	
	function database($gdl_db_conf=array()){
		global $gdl_err,$gdl_content;
		
		include ("./config/db.php");
		$this->con = @mysqli_connect($gdl_db_conf['host'], $gdl_db_conf['uname'], $gdl_db_conf['password']);
		@mysqli_select_db($this->con, $gdl_db_conf['name']) or $gdl_content->set_error("Unable to select database","Error Connection");
		$this->prefix=$gdl_db_conf['prefix'];
	}
	
	function test_connection() {
		include ("./config/db.php");
		if (!@mysqli_connect($gdl_db_conf['host'], $gdl_db_conf['uname'], $gdl_db_conf['password']))
			return "err";
	}
	
	function create_db($db_name) {
		$str_sql="create database `".$db_name."`;";
		$db_result=mysqli_query($this->con, $str_sql);
		
		return $db_result;
	}
	
	
	function tables($tables){
		if (!empty($this->prefix))
			$prefix=$this->prefix."_";
			
			$result = '';
			$table = explode(",",$tables);
			if (is_array($table)) {
				while (list($key,$val) = each($table)){
					$tab=explode(" ",$val);
					if (is_array($tab)) {
						$tabname= isset($tab[0]) ? $tab[0] : null;
						$tabalias=isset($tab[1]) ? $tab[1] : null;
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

		$db_result = @mysqli_query($this->con, $str_sql);
		if($this->print_script){ 
			echo $str_sql;
			echo mysqli_error($this->con);
		}
		return $db_result;
	}
	
	function insert($table,$fields="",$values="") { 
		$str_sql="insert into ".$this->tables($table);  
		if (!empty($fields)) $str_sql .=" ($fields)";
		if (!empty($values)) $str_sql .=" values($values)";
		$db_result = @mysqli_query($this->con, $str_sql);
		//echo $str_sql."<br>";
		//if (mysqli_error($this->con))
		//	echo mysqli_error($this->con)."<br/><b>$str_sql</b><br/>";
		
		return $db_result;
	}  
  
	function update($table,$newvals,$where="") {  
		$str_sql="update ".$this->tables($table)." set $newvals";  
		if (!empty($where)) $str_sql.=" where $where";
		$db_result = @mysqli_query($this->con, $str_sql); 
/*		echo $str_sql."<br>";
		echo mysqli_error($this->con);*/
		return $db_result; 
	}  
		  
	function delete($table,$where="") {
		$str_sql="delete from ".$this->tables($table);  
		if (!empty($where)) $str_sql.=" where $where "; 
		$db_result = @mysqli_query($this->con, $str_sql);
		return $db_result;
	}  

	// from https://stackoverflow.com/questions/37596450/old-password-function-in-5-7-5
	// equivalent to MySQL's OLD_PASSWORD() function
    function mysql3password($input, $hex = true) {
        $nr    = 1345345333;
        $add   = 7;
        $nr2   = 0x12345671;
        $tmp   = null;
        $inlen = strlen($input);
        for ($i = 0; $i < $inlen; $i++) {
            $byte = substr($input, $i, 1);
            if ($byte == ' ' || $byte == "\t") {
                continue;
            }
            $tmp = ord($byte);
            $nr ^= ((($nr & 63) + $add) * $tmp) + (($nr << 8) & 0xFFFFFFFF);
            $nr2 += (($nr2 << 8) & 0xFFFFFFFF) ^ $nr;
            $add += $tmp;
        }
        $out_a  = $nr & ((1 << 31) - 1);
        $out_b  = $nr2 & ((1 << 31) - 1);
        $output = sprintf("%08x%08x", $out_a, $out_b);
        if ($hex) {
            return $output;
        }

        return hexHashToBin($output);
    }

    function hexHashToBin($hex) {
        $bin = "";
        $len = strlen($hex);
        for ($i = 0; $i < $len; $i += 2) {
            $byte_hex  = substr($hex, $i, 2);
            $byte_dec  = hexdec($byte_hex);
            $byte_char = chr($byte_dec);
            $bin .= $byte_char;
        }

        return $bin;
    }
}
?>