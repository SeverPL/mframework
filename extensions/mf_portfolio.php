<?php
/* Rozszerzenie 'Portfolio' dla m.framework
 * Wersja rozszerzenia: 1.0.2.0 a
* 20 Sierpnia 2014 11:29
* Mateusz Wiśniewski © 2014
*
* mf_portfolio
* 
* Tabele bazy danych:
*---------------- port_cateogires
*- ID
*- TITLE
*- HIDDEN
*- CATIMG
*---------------- portfolio
*- ID
*- TITLE
*- DESC
*- CATEGORY
*- IMAGES
*/

class mf_portfolio{
	protected $_engine = null;
	protected $_db = null;
	protected $_mf_prefix = null;
	protected $_categories = null;
	protected $_categoriesnum = null;
	protected $_elements = null;
	protected $_elementsnum = null;
	
	public function __construct(){
		global $mf_prefix;
		$this->_mf_prefix = $mf_prefix;
	}
	
	public function getCategories(){ //pobieranie kategorii do tablicy
		$query = "SELECT * FROM ".$this->_mf_prefix."port_categories WHERE HIDDEN='0' ORDER BY ID ASC";
		$this->_categories = mf_db::mf_mysql_query($query);
		$this->_categoriesnum = mysql_num_rows($this->_categories);
		if($this->_categoriesnum==0){
			return null;
		}
		else{
			return $this->_categories;
		}
	}
	
	public function getElementById($gid){ //pobieranie elementu do tablicy po ID
		$query = "SELECT * FROM ".$this->_mf_prefix."portfolio WHERE ID='$gid' AND HIDDEN='0'";
		$this->_elements = mf_db::mf_mysql_query($query);
		$this->_elementsnum = mysql_num_rows($this->_elements);
		return $this->_elements;		
	}
	public function getElementsByCategory($cat){
		$query = "SELECT * FROM ".$this->_mf_prefix."portfolio WHERE CATEGORY='$cat' AND HIDDEN='0'";
		$this->_elements = mf_db::mf_mysql_query($query);
		$this->_elementsnum = mysql_num_rows($this->_elements);
		return $this->_elements;		
	}
	public function getElementsnum(){
		return $this->_elementsnum;
	}
	public function countElementsByCategory($cat){//liczenie elementow wg kategorii
		$query = "SELECT * FROM ".$this->_mf_prefix."portfolio WHERE CATEGORY='$cat' AND HIDDEN='0'";
		$result = mf_db::mf_mysql_query($query);
		$this->_elementsnum = mysql_num_rows($result);
		return $this->_elementsnum;
	}
}