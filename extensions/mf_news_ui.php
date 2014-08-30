<?php 
/**
*  Rozszerzenie 'Newsy' dla m.framework - klasa wyświetlająca
* @version 1.1.1.0 a
* @author Mateusz Wiśniewski 
* 20 Sierpnia 2014 09:27
* © 2014 
*/
class mf_news_ui extends mf_news{
	private $_result = null;
	
	/**
	 * Wyświetlanie bocznego panelu z newsami
	 * @param integer $ilosc Ilość wyświetlanych newsów
	 * @param string $ascdesc [ASC/DESC] rosnąco lub malejąco
	 * @param boolean $showalllink pokaż link do wszystkich newsów
	 */
	public function pokazsideNewsy($ilosc, $ascdesc,$showalllink){
		$this->getLastNews($ilosc, $ascdesc);
		
		if($this->_array==null){echo 'NULL';}
		else{
			while($row = mysql_fetch_assoc($this->_array)){
				$this->_id = $row['ID'];
				$this->_title = $row['TITLE'];
				$this->_content = $row['CONTENT'];
				$this->_created = $row['CREATED'];
				$this->_created = $this->_engine->data($this->_created, 4);
				$this->_edited = $row['EDITED'];
				$this->_autor = $row['AUTOR'];
				
				echo '<aside>';
				echo'<div class="SideNewsBox">';
					echo '<a href="index.php?page=news&amp;id='.$row['ID'].'"><h1>'.$this->_title.'</h1></a>';
					echo '<p class="data">'.$this->_created.'</p>';
					echo '<p>'.$row['SHORTCONTENT'].'</p>';
				echo'</div>';
				echo '</aside>';
			}
		}
		if($showalllink==true){echo '<div class="NewsBoxBottom"><a href="?page=news">pokaż wszystkie</a> &raquo;';}
	}
	/**
	 * Pokazuje wszystkie newsy z bazy danych
	 * @param string $ascdesc [ASC/DESC] rosnąco lub malejąco
	 */
	public function showAllNews($ascdesc){
		try{
			$this->getAllNews($ascdesc);
			while ($row = mysql_fetch_assoc($this->_array)){
				$this->_created = $row['CREATED'];
				$this->_created = $this->_engine->data($this->_created, 4);
				echo'<div class="NewsBox">';
				echo '<h1>'.$row['TITLE'].'</h1>';
				echo '<p class="data">'.$this->_created.'</p>';
				echo '<p>'.$row['CONTENT'].'</p>';
				echo'</div>';				
			}
		}
		catch(Exception $exception){
			echo $exception->getMessage();
		}
		
	}
}

?>