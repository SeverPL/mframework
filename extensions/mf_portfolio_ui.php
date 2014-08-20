<?php
/* Rozszerzenie 'Portfolio UI' dla m.framework
 * Wersja rozszerzenia: 1.0.0.0 a
* 20 Sierpnia 2014 11:29
* Mateusz Wiśniewski © 2014
*
* mf_portfolio_ui dziedziczy klasę mf_portfolio
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

class mf_portfolio_ui extends mf_portfolio{
	
	public function showPortfolio($tytul, $opis){
		if(isset($_GET['category']) AND !isset($_GET['id'])){
			echo 'kategoria';
		}
		elseif(isset($_GET['category']) AND isset($_GET['id'])){
			echo 'pozycja';
		}
		else{
			echo '<article>';
			echo '<h1>'.$tytul.'</h1>';
			echo $opis;
			$this->showCategoriesMain();
			echo '</article>';		
		}
	}
	private function showCategoriesMain(){
		$this->getCategories();
		while($row = mysql_fetch_assoc($this->_categories)){
			echo '<div class="PortfolioCategoryButton">';
			if($row['CATIMG']!=null){echo '<img src="images/www.png" alt="Strony WWW" />';}
			echo '<a href="index.php?page=projects&amp;category='.$row['ID'].'">'.$row['TITLE'].'</a></div>';
		}
	}
}