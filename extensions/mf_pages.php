<?php
/* Rozszerzenie 'Podstrony' dla m.framework
 * Wersja rozszerzenia: 2.1.1.0 a
 * 24 Września 2014 18:18
 * Mateusz Wiśniewski © 2014
 * 
 * mf_pages
 * 
 * klasy CSS:
 * 
 * Pola bazy danych:
 * ID
 * TITLE - tytuł
 * CONTENT - zawartosc
 * CREATED - Data utworzenia
 * EDITED - Data edycji
 * HIDDEN - ukrywa badz pokazuje podstrone
 * 
 * 
 * DO KONSTRUKTORA NALEŻY PRZEKAZAĆ PREFIX BAZY DANYCH
 */
class mf_pages{
	private $_db = null;
	private $_mf_prefix = null;
	private $_id = null;
	private $_title = null;
	private $_content = null;
	private $_hidden = null;
	private $_created = null;
	private $_edited = null;
	
	public function __construct($mf_prefix){
		$this->_mf_prefix = $mf_prefix;
	}
	
	public function getPage($gid){ //wczytanie danych
		$query = "SELECT * FROM ".$this->_mf_prefix."pages WHERE ID='$gid'";
		$result = mf_db::mf_mysql_query($query);
		$check = mysql_num_rows($result);
		if($check>0){
			$row = mysql_fetch_assoc($result);
			$this->_id = $row['ID'];
			$this->_title = $row['TITLE'];
			$this->_content = $row['CONTENT'];
			$this->_created = $row['CREATED'];
			$this->_edited = $row['EDITED'];
			$this->_hidden = $row['HIDDEN'];
			return true;
		}
		else{
			return false;
		}	
	}
	
	public function getId(){ //zwrot id
		return $this->_id;
	}
	
	public function getTitle(){ //zwrot id
		return $this->_title;
	}
	
	public function getContent(){ //zwrot zawartości
		return $this->_content;
	}
	
	public function getCreated(){ //zwrot daty utworzenia
		return $this->_created;
	}
	
	public function getEdited(){ //zwrot daty edycji
		return $this->_edited;
	}
	
	public function getHidden(){ //zwrot parametru hidden
		return $this->_hidden;
	}
	
	
}

?>