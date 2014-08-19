<?php
/*
 * m.framework - dbconnector - v.2.1.0.0
* 19 Sierpnia 2014 15:42
*
* Copyright by Mateusz Wiśniewski © 2014
*/
$chandle = mysql_connect($db_host, $db_user, $db_pass)
or die("Błąd połączenia z bazą");
mysql_select_db($db_name, $chandle) or die ($db_name . " Nie znaleziono bazy danych. " . $db_user);
mysql_query("SET NAMES 'utf8'");

class mf_db{
	
	public function mf_mysql_query($query){
		global $mf_sql_queries;
		global $mf_debug_show_queries;
		$ins = mysql_query($query);
		if($mf_debug_show_queries == true){
			echo $query;
		}
		if($ins){
			$mf_sql_queries++;
			return $ins;
		}
	}
	public function mf_log($tresc, $typ){
		global $mf_prefix;
		global $mf_debug_log;
	
		/*
		 * Typy logów
		* 1 - zwykły (np usera na stronie)
		* 2 - administracyjny (działania administratorów
				* 3 - systemowy
				* 4 - błąd
				*/
	
		//pobieranie IP
		if($mf_debug_log==true){
			if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
				$akcjaip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			else {
				$akcjaip = $_SERVER['REMOTE_ADDR'];
			}
	
			$czas = date("Y-m-d H:i:s");
			$query = "INSERT INTO ".$mf_prefix."logs SET IP='$akcjaip', AKCJA='$tresc', CZAS='$czas', TYP='$typ'";
			$this->mf_mysql_query($query);
			#===============================================
		}
	}
}
?>