<?php 
/* Rozszerzenie 'Newsy' dla m.framework
 * Wersja rozszerzenia: 1.0.0.0 a
* 20 Sierpnia 2014 09:27
* Mateusz Wiśniewski © 2014
*
* mf_news_ui
*/
class mf_news_ui extends mf_news{
	private $_result = null;
	
	public function pokazsideNewsy($ilosc, $ascdesc){
		$this->_result = $this->getLastNews($ilosc, $ascdesc);
		
		if($this->_result==null){echo 'NULL';}
		else{
			while($row = mysql_fetch_assoc($this->_result)){
				$this->_id = $row['ID'];
				$this->_title = $row['TITLE'];
				$this->_content = $row['CONTENT'];
				$this->_created = $row['CREATED'];
				$this->_created = $this->_engine->data($this->_created, 4);
				$this->_edited = $row['EDITED'];
				$this->_autor = $row['AUTOR'];
				
				echo'<div class="SideNewsBox">';
					echo '<h3>'.$this->_title.'</h3>';
					echo '<p class="data">'.$this->_created.'</p>';
				echo'</div>';
			}
		}
		echo '<div class="NewsBoxBottom">pokaż wszystkie &raquo;';
	}
}

?>