<?php
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
	
	/**
	* Rozszerzenie 'Newsy' dla m.framework
	* 28 Sierpnia 2014 12:38 © 2014
	* @version 1.1.0.0 a
	* @author Mateusz Wiśniewski
	* @param string $mf_prefix prefix bazy danych
	* @param object $db obiekt klasy mf_db
	* @param object $engine obiekt klasy mf_engine
	*/
	public function __construct($mf_prefix, $db, $engine){
		$this->_db = $db;
		$this->_mf_prefix = $mf_prefix;
		$this->_engine = $engine;
	}
	
	/**
	 * Pobiera newsy z bazy danych
	 * 
	 * @param integer $ilosc ilość newsów
	 * @param string $ascdesc [ASC/DESC] rosnąco lub malejąco
	 * @return NULL nie zwraca nic jeśli nie ma newsów spełniająych podane kryteria
	 * @return array tablica z newsami: ID, TITLE, CONTENT, HIDDEN, CREATED, EDITED, AUTOR
	 */

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
	
	/**
	 * Zwraca wszystkie newsy w bazie danych jako tablice którą zapisuje w polu _array : ID, TITLE, CONTENT, HIDDEN, CREATED, EDITED, AUTOR
	 * @param string $ascdesc [ASC/DESC] sortowanie rosnąco lub malejąco
	 * @throws Exception gdy nie ma newsów w bazie danych
	 */
	public function getAllNews($ascdesc){  
		$query = "SELECT * FROM ".$this->_mf_prefix."news WHERE HIDDEN='0' ORDER BY CREATED $ascdesc";
		$result = $this->_db->mf_mysql_query($query);
		$this->_check = mysql_num_rows($result);
		if($this->_check==0){
			throw new Exception('Brak newsów w bazie danych!');
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
	/**
	 * Zwraca ilość wszystkich newsów w bazie danych
	 * @param bool $ukryte czy  ma liczyć także ukryte newsy
	 * @return int ilość newsów
	 */
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