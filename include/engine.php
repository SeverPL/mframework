<?php
#include('config.php');
/*
 * m.framework - engine - v.2.1.0.0.a
 * 19 Sierpnia 2014 15:14
 * 
 * Copyright by Mateusz Wiśniewski © 2014 
 */
$mf_ver_engine = '2.1.0.0a';
class mf_engine{	
	private $_mf_time_start = null;
 	private $_mf_debug_show_gentime = null;
 	
 	public function __construct($mf_debug_show_gentime){
 		$this->_mf_debug_show_gentime = $mf_debug_show_gentime; 		
 	}
 	
	function getExtension($str) {
    	     $i = strrpos($str,".");
         	if (!$i) { return ""; } 
         	$l = strlen($str) - $i;
         	$ext = substr($str,$i+1,$l);
         	return $ext;
 	}
 	
 	function tnij($string, $limit, $break=".", $pad="...")
 	{
 		// return with no change if string is shorter than $limit
 		if(strlen($string) <= $limit) return $string;
 	
 		// is $break present between $limit and the end of the string?
 		if(false !== ($breakpoint = strpos($string, $break, $limit))) {
 			if($breakpoint < strlen($string) - 1) {
 				$string = substr($string, 0, $breakpoint) . $pad;
 			}
 		}
 	
 		return $string;
 	}
 

 	function data($timestamp, $tryb){
 		//wersja 1.0.0.2
 		$rok = substr($timestamp, 0, -15);
 		$miesiac = substr($timestamp, 5, 2);
 		$dzien = substr($timestamp, 8, 2);
 		$godzina = substr($timestamp, -8, 5);
 		$godzinapelna = substr($timestamp, -8, 8);
 		$dzientygodnia = date("l", mktime(0, 0, 0, $miesiac, $dzien, $rok));
 		if($miesiac=="01"){ $miesiactext = "Stycznia";}
 		if($miesiac=="02"){ $miesiactext = "Lutego";}
 		if($miesiac=="03"){ $miesiactext = "Marca";}
 		if($miesiac=="04"){ $miesiactext = "Kwietnia";}
 		if($miesiac=="05"){ $miesiactext = "Maja";}
 		if($miesiac=="06"){ $miesiactext = "Czerwca";}
 		if($miesiac=="07"){ $miesiactext = "Lipca";}
 		if($miesiac=="08"){ $miesiactext = "Sierpnia";}
 		if($miesiac=="09"){ $miesiactext = "Września";}
 		if($miesiac=="10"){ $miesiactext = "Października";}
 		if($miesiac=="11"){ $miesiactext = "Listopada";}
 		if($miesiac=="12"){ $miesiactext = "Grudnia";}
 		$dzientygodnia = substr($dzientygodnia, 0, 3);
 		if($dzientygodnia=="Mon") {$dzientygodnia = "Poniedziałek";}
 		if($dzientygodnia=="Tue") {$dzientygodnia = "Wtorek";}
 		if($dzientygodnia=="Wed") {$dzientygodnia = "Środa";}
 		if($dzientygodnia=="Thu") {$dzientygodnia = "Czwartek";}
 		if($dzientygodnia=="Fri") {$dzientygodnia = "Piątek";}
 		if($dzientygodnia=="Sat") {$dzientygodnia = "Sobota";}
 		if($dzientygodnia=="Sun") {$dzientygodnia = "Niedziela";}
 		if($dzien=="01") {$dzien="1";}
 		if($dzien=="02") {$dzien="2";}
 		if($dzien=="03") {$dzien="3";}
 		if($dzien=="04") {$dzien="4";}
 		if($dzien=="05") {$dzien="5";}
 		if($dzien=="06") {$dzien="6";}
 		if($dzien=="07") {$dzien="7";}
 		if($dzien=="08") {$dzien="8";}
 		if($dzien=="09") {$dzien="9";}
 		if($tryb==1){$przetworzona = "$dzientygodnia, $dzien $miesiactext $rok $godzina";} // Niedziela, 2 Grudnia 2012 11:04
 		if($tryb==2){$przetworzona = "$dzientygodnia, $dzien $miesiactext $rok";} // Niedziela, 2 Grudnia 2012
 		if($tryb==3){$przetworzona = "$dzien $miesiactext $rok $godzina";} // 2 Grudnia 2012 11:04
 		if($tryb==4){$przetworzona = "$dzien $miesiactext $rok";} //2 Grudnia 2012
 		if($tryb==5){$przetworzona = "$dzien.$miesiac.$rok";} // 2.12.2012
 		if($tryb==6){$przetworzona = "$dzien.$miesiac.$rok $godzina";} // 2.12.2012 11:05
 		if($tryb==7){$przetworzona = "$dzien $miesiactext";} //2 Grudnia
 		if($tryb==8){$przetworzona = "$dzien.$miesiac";} // 2.12
 		if($tryb==9){$przetworzona = "$dzientygodnia";} // Niedziela
 		if($tryb==10){$przetworzona = "$godzina";} // 11:05
 		if($tryb==11){$przetworzona = "$godzinapelna";} // 11:05:11
 	
 		return $przetworzona;
 	}

 	private function mf_microtime(){ //funkcja do mierzenia czasu generowania strony
 		list($usec, $sec) = explode(" ",microtime());
 		return ((float)$usec + (float)$sec);
 	}
 	public function mf_gentime_start(){ //funkcja do rozpoczęcia mierzenia czasu generowania strony, umieszczana na początku dokumentu
 		$this->_mf_time_start = $this->mf_microtime();
 	}
 	public function mf_gentime_stop(){
 		$mf_time_end = $this->mf_microtime();
 		$mf_gentime = substr($mf_time_end - $this->_mf_time_start, 0, 8);
 		if($this->_mf_debug_show_gentime==true){
 			echo"<center><small>Strona została wygnerowana w $mf_gentime sekund.</small></center>";
 		}
 	}
 	
}

class mf_head{
	function html_meta_tags($robots, $autor, $keywords, $description, $title, $revisit, $lang){
		echo "<meta name=\"robots\" content=\"$robots\" />" . "\n"; //all
		echo "<meta name=\"author\" content=\"$autor\" />" . "\n";
		echo "<meta name=\"keywords\" content=\"$keywords\" />" . "\n";
		echo "<meta name=\"revisit-after\" content=\"$revisit\" />" . "\n"; //2 days
		echo "<meta name=\"description\" content=\"$description\" />" . "\n";
		echo "<meta http-equiv=\"Content-Language\" content=\"$lang\" />" . "\n";
		echo '<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />' . "\n";
		echo "<title>$title</title>" . "\n";
	}
	function html5_meta_tags($robots, $autor, $keywords, $description, $title, $revisit){
		echo "<meta name=\"robots\" content=\"$robots\" />" . "\n"; //all
		echo "<meta name=\"author\" content=\"$autor\" />" . "\n";
		echo "<meta name=\"keywords\" content=\"$keywords\" />" . "\n";
		echo "<meta name=\"revisit-after\" content=\"$revisit\" />" . "\n"; //2 days
		echo "<meta name=\"description\" content=\"$description\" />" . "\n";
		echo '<meta charset="utf-8" />' . "\n";
		echo "<title>$title</title>" . "\n";
	}
	
	function html_doctype(){
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
		echo '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
	}
	function html_css($sciezka){
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$sciezka\" />" . "\n";
	}
	function html5_css($sciezka){
		echo "<link rel=\"stylesheet\" href=\"$sciezka\" />" . "\n";
	}
	function html_script($sciezka){
		echo "<script type=\"text/javascript\" src=\"$sciezka\"></script>" . "\n";
	}
	function html5_script($sciezka){
		echo "<script type=\"text/javascript\" src=\"$sciezka\"></script>" . "\n";
	}
	function cookieinfo(){
		if(!isset($_COOKIE['wizyta'])) {
			setcookie('wizyta', time(), time() + 30 * 86400);
			echo "<div class=\"cookie\" id=\"cookiediv\"><div class=\"cookieinfo\">
					<h3>Ta strona wykorzystuje pliki cookies</h3>
					<p>W tym serwisie stosuje się pliki cookies, które są zapisywane na dysku użytkownika. Zablokowanie możliwości zapisywania plików cookies może spowodować utrudnienia lub brak działania niektórych funkcji serwisu.</p> <p>Niedokonanie zmian ustawień przeglądarki internetowej na ustawienia blokujące cookies jest jednoznaczne z wyrażeniem zgody na ich zapisywanie.</p>
					<div class=\"cookiebutton\" id=\"cbutton\">OK, Rozumiem</div>
				</div>
				</div>
				";
		}
	}
}



