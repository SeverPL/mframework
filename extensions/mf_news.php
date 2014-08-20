<?php
/* Rozszerzenie 'Newsy' dla m.framework
 * Wersja rozszerzenia: 1.0.0.0 a
*19 Sierpnia 2014 10:21
* Mateusz Wiśniewski © 2014
*
* mf_news
* 
* 
* DO KONSTRUKTORA NALEŻY PRZEKAZAĆ W ZMIENNEJ $db obiekt klasy mf_db A W ZMIENNEJ $mf_prefix PREFIX DO BAZY DANYCH, W ZMIENEJ $engine OBIEKT KLASY mf_engine
*/
class mf_news{
	protected $_engine = null;
	protected $_db = null;
	protected $_mf_prefix = null;
	protected $_id = null;
	protected $_title = null;
	protected $_content = null;
	protected $_hidden = null;
	protected $_created = null;
	protected $_edited = null;
	protected $_autor = null;
	protected $_checkall = null; //ilosc wszystkich newsow
	protected $_check = null; //ilosc newsow w zapytaniu
	protected $_array = null; //tablica do przechowywania wyniku z sqla
	
	public function __construct($mf_prefix, $db, $engine){
		$this->_db = $db;
		$this->_mf_prefix = $mf_prefix;
		$this->_engine = $engine;
	}
	
	public function getLastNews($ilosc, $ascdesc){ //ilosc, ASC/DESC
		$query = "SELECT * FROM ".$this->_mf_prefix."news WHERE HIDDEN='0' ORDER BY CREATED $ascdesc LIMIT $ilosc";
		$result = $this->_db->mf_mysql_query($query);
		$this->_check = mysql_num_rows($result);
		if($this->_check==0){
			return null;
		}
		elseif($this->_check==1){
			$row = mysql_fetch_assoc($result);
			$this->_id = $row['ID'];
			$this->_title = $row['TITLE'];
			$this->_content = $row['CONTENT'];
			$this->_hidden = $row['HIDDEN'];
			$this->_created = $row['CREATED'];
			$this->_edited = $row['EDITED'];
			$this->_autor = $row['AUTOR'];
			$this->_array = $result;
			return $this->_array;
		}
		else{
			$this->_array = $result;
			return $this->_array;
		}
	}
	
	public function sprawdzIlosc($ukryte){ //ukryte true lub false jesli ma liczyc rowniez ukryte newsy
		if($ukryte==false){
			$query = "SELECT * FROM ".$this->_mf_prefix."news WHERE HIDDEN='0' ORDER BY ID DESC";
		}
		else{
			$query = "SELECT * FROM ".$this->_mf_prefix."news' ORDER BY ID DESC";
		}
		$result = $this->_db->mf_mysql_query($query);
		$this->_checkall = mysql_num_rows($result);
		return $this->_checkall;
	}
}