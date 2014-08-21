<?php
/* Rozszerzenie 'Portfolio UI' dla m.framework
 * Wersja rozszerzenia: 1.1.0.0 a
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
	private $_images = null;
	private $_imagesamount = null;
	
	public function showPortfolio($tytul, $opis){
		if(isset($_GET['category']) AND !isset($_GET['id'])){ //widok kategorii
			echo '<aside><nav>';
			$this->showCategoryMenu();
			echo '</nav></aside><article>';
			$this->showCategoryElements();
			echo '</article>';
		}
		elseif(isset($_GET['category']) AND isset($_GET['id'])){ //widok elementu
			echo '<aside><nav>';
			$this->showCategoryMenu();
			echo '</nav></aside><article>';
			$this->showElement();
			echo '</article>';
		}
		else{ //widok główny działu
			echo '<article>';
			echo '<h1>'.$tytul.'</h1>';
			echo $opis;
			$this->showCategoriesMain();
			echo '</article>';		
		}
	}
	private function showCategoryMenu(){
		$this->getCategories();
		echo'<div class="PortfolioCategoryMenu">';
		while($row = mysql_fetch_assoc($this->_categories)){
			echo '<div class="PortfolioMenuBox"><a href="index.php?page=projects&amp;category='.$row['ID'].'">'.$row['TITLE'].'</a></div>';
				if($_GET['category']==$row['ID']){
					$this->getElementsByCategory($_GET['category']);
					while($row = mysql_fetch_assoc($this->_elements)){
						echo '<div class="PortfolioSubmenuBox">';
						echo '<a href="index.php?page=projects&amp;category='.$_GET['category'].'&amp;id='.$row['ID'].'" class="link">&raquo; '.$row['TITLE'].'</a>';
						echo '</div>';
					}
				}
		}
		echo '</div>';
	}
	private function showCategoryElements(){
		$this->getElementsByCategory($_GET['category']);
		echo '<div class="PortfolioElementsContainer">';
		if($this->_elementsnum!=0){
			while($row = mysql_fetch_assoc($this->_elements)){
				echo '<div class="PortfolioElementsBox">';
					echo '<div class="PortfolioElementsPhotoBox"><img alt="Miniaturka projektu" src="'.$row['MAINIMAGE'].'" /></div>';
					echo '<h1>'.$row['TITLE'].'</h1>';
					echo $row['SHORTDESC'];
					echo '<a class="link" href="index.php?page=projects&amp;category='.$_GET['category'].'&amp;id='.$row['ID'].'"> więcej &raquo;</a>';
				echo '</div>';
			}
		}
		else{
			echo '<h1>Brak elementów do wyświetlenia.</h1>';
			echo '<p>Narazie w tej kategorii nic nie ma, zapraszam wkrótce!';
		}
		echo '</div>';
	}
	private function showCategoriesMain(){
		$this->getCategories();
		while($row = mysql_fetch_assoc($this->_categories)){
			echo '<div class="PortfolioCategoryButton">';
			if($row['CATIMG']!=null){echo '<img src="images/www.png" alt="Strony WWW" />';}
			echo '<a href="index.php?page=projects&amp;category='.$row['ID'].'">'.$row['TITLE'].'</a></div>';
		}
	}
	private function showElement(){
		$this->getElementById($_GET['id']);
		$row = mysql_fetch_assoc($this->_elements);
		$this->_images = $row['IMAGES'];
		$this->_imagesamount = $row['IMAGESAMOUNT'];
		echo '<div class="PortfolioElementsContainer">';
			echo '<h1>'.$row['TITLE'].'</h1>';
			echo $row['DESC'];
			$this->showGallery();
		echo '</div>';
	}
	private function showGallery(){
		echo '<h1>Galeria</h1>';
		if($this->_imagesamount==0){}
		elseif($this->_imagesamount==1){
			echo '<img src="'.$this->_images.'(1).png" alt="" />';
		}
		else{
			echo '<div class="highslide-gallery">';
			for($i=1;$i<=$this->_imagesamount;$i++){
				echo'<a class="highslide" onclick="return hs.expand(this)" href="'.$this->_images.'('.$i.').png"> <img title="Powiększ" src="'.$this->_images.'('.$i.')_t.png" alt="test" /> </a>';
				echo '<div class="highslide-caption"></div>';			
			}
			echo '</div>';
		}		
	}
}