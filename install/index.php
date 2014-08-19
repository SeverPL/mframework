<?php
/*
 * m.framework - instalator - v.1.0.1.0
* 20 czerwca 2014 10:40
*
* Copyright by Mateusz Wiśniewski © 2014
*/
$step = $_GET['step'];
require ('installerlaunch.php');
mf_gentime_start();
html_doctype();
html_meta_tags('disallow', 'Mateusz Wiśniewski', 'brak tagów', 'Instalacja m.framework', 'Instalacja m.framework', 'none', 'pl');

function installer_db_connect($db_host, $db_user, $db_pass, $db_name){
	$chandle = mysql_connect($db_host, $db_user, $db_pass); //logowanie do bazy
	$selectdb = mysql_select_db($db_name, $chandle); //wybor bazy
	$setnames = mysql_query("SET NAMES 'utf8'");
}
?>
<link href='http://fonts.googleapis.com/css?family=Lato:300&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<?php
	html_css('install.css');
	jquery_init();
?>
</head>
<body>
<?php 

if(!isset($step)){
	echo '<div class="dark-matter">';
	echo '<h1>Instalacja m.framework - v 1.0.1.0 a</h1>';
	echo '<h3>Sprawdzanie środowiska</h3><hr />';
	echo '<p>Witaj w instalatorze m.framework, instalator sprawdzi teraz czy środowisko spełnia wymagane warunki</p>';
	include('../extensions/extensions.php'); //incudowanie rozszerzen do wyswietlenia wersji
	echo '<table>';
	echo '<tr><td></td><td>Na serwerze</td><td>Wymagane</td></tr>';
	echo '<tr><td>PHP</td><td></td><td>5.0</td></tr>';
	echo '<tr><td>MySQL</td><td></td><td>5.0</td></tr>';
	echo '<tr><td>CHMOD dbconfig.php</td><td></td><td>777</td></tr>';
	echo '<tr><td></td><td>Wersja modułów</td><td>Wersja instalatora</td></tr>'; // do ewentualnych zmian
	echo "<tr><td>Silnik</td><td>$mf_ver_engine</td><td>$mf_ver_engine</td></tr>";
	echo "<tr><td>Users</td><td>$mf_ver_users</td><td>$mf_ver_engine</td></tr>";
	echo "<tr><td>Pages</td><td>$mf_ver_pages</td><td>$mf_ver_engine</td></tr>";
	echo '</table>';	
	
	echo '<form  method="POST" action="index.php?step=2">';
	echo '<input type="submit" value="Dalej" class="button" />';
	echo '</form>';
	echo '</div>';
		
} elseif($step=='2'){
	?>
	<div class="dark-matter">
		<h1>Instalacja m.framework - v 1.0.1.0 a</h1>
		<h3>Instalacja bazy danych</h3>
		<p>W tym kroku instalator zainstaluje bazę danych</p>
		<table>
			<form method="post" action="index.php?step=3">
			<tr><td>Host":</td><td><input type="text" name="dbhost" id="dbhost" /></td></tr>
			<tr><td>Użytkownik":</td><td><input type="text" name="dbuser" id="dbuser" /></td></tr>
			<tr><td>Hasło</td><td><input type="text" name="dbpass" id="dbpass" /></td></tr>
			<tr><td>Nazwa bazy:</td><td><input type="text" name="dbname" id="dbname" /></td></tr>
			<tr><td>Prefix tabel:</td><td><input type="text" name="dbprefix" id="dbprefix" value="mf_" title="" /></td></tr>
			<tr><td></td><td><input class="button" type="submit" value="Dalej" /></td></tr>
			<hr />
			</form>
		</table>
	</div>
<?php 
}
elseif($step=='3'){
	$db_host = $_POST['dbhost'];
	$db_user = $_POST['dbuser'];
	$db_pass = $_POST['dbpass'];
	$db_name = $_POST['dbname'];
	$mf_prefix = $_POST['dbprefix'];
	
	?><div class="dark-matter">
	<h1>Instalacja m.framework - v 1.0.1.0 a</h1>
	<h3>Instalacja bazy danych</h3><?php 
	
	$chandle = mysql_connect($db_host, $db_user, $db_pass); //logowanie do bazy
	$selectdb = mysql_select_db($db_name, $chandle); //wybor bazy
	$setnames = mysql_query("SET NAMES 'utf8'");
	
	echo '<p>';
	if($chandle){echo '<span style="color:green;">Połączenie z bazą, OK<br />';}
	else { echo '<span style="color:red;">Błąd! Nie ustanowiono połączenia z bazą!<br />'; }
	
	if($selectdb){echo '<span style="color:green;">Znaleziono bazę danych, OK<br />';}
	else { echo '<span style="color:red;">Błąd! Nie znaleziono bazy danych!<br />'; }
	
	if($setnames){echo '<span style="color:green;">Kodowanie połączeń - UTF8, OK<br />';}
	else { echo '<span style="color:red;">Błąd! Nie udało się ustawić kodowania na UTF8!<br />'; }
	echo '</p>';
	
		
	if($chandle AND $selectdb AND $setnames){
		
			/* Blok modyfikacji config.php */
			$config = '../include/dbconfig.php';
			chmod($config, 0777); //Nadanie chmodu 777 dla pliku dbconfig.php
			
			if(is_writable($config)){ //sprawdzenie czy dbconfig.php jest zapisywalny
				$plik = fopen($config, "w"); //otwarcie pliku do odczytu i zapisu
				#flock($plik, 'LOCK_EX'); //zablokowanie pliku
				$ustawienia = "<?\$db_user = '$db_user';\$db_pass = '$db_pass';\$db_host = '$db_host';\$db_name = '$db_name';\$mf_prefix = '$mf_prefix';?> "; //dane do zapisu w pliku dbconfig.php
				$zapis = fwrite($plik, $ustawienia); //zapisywanie do pliku
				#flock($plik, 'LOCK_UN'); //odblokowanie pliku
				fclose($plik);	//zamkniecie pliku
				if($zapis){echo '<p style="color:green;">Zapis ustawień połączenia do pliku dbconfig.php zakończony</p>';}
				else{ echo'<p style="color:red;">Błąd! Nie udało się zapisać danych do pliku dbconfig.php</p>'; }
			}
			else { echo '<p style="color:red;">Błąd! Plik dbconfig.php nie ma praw do zapisu ! ustaw CHMOD 777</p>'; }
			/* Koniec modyfikacji config.php */



		echo '<form action="index.php?step=4" method="POST">';
		echo '<input class="button" type="submit" value="Dalej" />';
		echo '</form>';
	}
	?> </div><?php
}
elseif($step=='4'){
	?><div class="dark-matter">
	<h1>Instalacja m.framework - v 1.0.1.0 a</h1>
	<h3>Instalacja bazy danych</h3>
	<p>Wybierz dane które chcesz zainstalować</p>
	<form action="index.php?step=5" method="POST"><?php
	echo '<input type="checkbox" name="clear_db" value="1"/>Wyczyść bazę danych<br />';
	echo '<input type="checkbox" name="core_db" value="1"/>Jądro<br />';
	echo '<input type="checkbox" disabled="true"/>Newsy<br />';
	echo '<input type="checkbox" name="pages_db" value="1"/>Podstrony<br />';
	echo '<input type="checkbox" disabled="true"/>Galerie<br />';
	echo '<input type="checkbox" name="users_db" value="1"/>Uzytkownicy<br />';
	echo '<br /><input class="button" type="submit" value="Dalej" />';
	?></form></div><?php 	 
}
elseif($step=='5'){
	?><div class="dark-matter">
	<h1>Instalacja m.framework - v 1.0.1.0 a</h1>
	<h3>Instalacja bazy danych</h3>
	<p>Wynik instalacji bazy danych:</p>
	<?php 
	$clear_db = $_POST['clear_db'];
	$core_db = $_POST['core_db'];
	$pages_db = $_POST['pages_db'];
	$users_db = $_POST['users_db'];
	
	#installer_db_connect($db_host, $db_user, $db_pass, $db_name);
	
	$chandle = mysql_connect($db_host, $db_user, $db_pass); //logowanie do bazy
	$selectdb = mysql_select_db($db_name, $chandle); //wybor bazy
	$setnames = mysql_query("SET NAMES 'utf8'");
	
	if($clear_db=='1'){ //czyszczenie bazy danych
		$clear_q1 = mf_mysql_query('DROP TABLE * ');
		if($clear_q1){echo '<p style="color:green;">Usunięto wszystkie tabele z bazy danych</p>';}
		else {echo '<p style="color:red;">Nie udało się usunąć tabel z bazy danych</p>'; echo mysql_error(); }
	}
	if($core_db=='1'){ //instalacja jądra bazy
		$core_query1 = 
			"CREATE TABLE `".$mf_prefix."logs` ( 
				`ID` int(11) NOT NULL AUTO_INCREMENT,
				`IP` text COLLATE utf8_unicode_ci NOT NULL,
				`CZAS` text COLLATE utf8_unicode_ci NOT NULL,
				`AKCJA` text COLLATE utf8_unicode_ci NOT NULL, 
				`TYP` int(11) NOT NULL, PRIMARY KEY (`ID`)
			) 
			"; // tworzenie tabeli logów
		/*
		 * ENGINE=MyISAM 
			DEFAULT CHARSET=utf8 
			COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
		 */
		$core_q1 = mf_mysql_query($core_query1);
		if($core_q1){echo '<p style="color:green;">Jądro - utworzono strukturę tabeli logs</p>';}
		else {echo '<p style="color:red;">Jądro - Nie udało się utworzyć struktury tabeli logs</p>'; echo mysql_error(); }
		
		$inst_data = date("Y-m-d H:i:s");
		$core_q2 = mf_mysql_query("INSERT INTO `".$mf_prefix."logs` (`ID`, `IP`, `CZAS`, `AKCJA`, `TYP`) VALUES (1, '127.0.0.1', '".$inst_data."','Instalacja bazy SQL', 3);");
		
	}
	if($pages_db=='1'){ //instalacja podstron
		$pages_query1 =
		" CREATE TABLE `".$mf_prefix."pages` (
			`ID` INT NOT NULL AUTO_INCREMENT ,
			`TITLE` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
			`CONTENT` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
			`CREATED` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			`EDITED` DATETIME NOT NULL ,
			`HIDDEN` INT( 1 ) NOT NULL ,
			PRIMARY KEY ( `ID` )
			) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci 
			"; // tworzenie tabeli 
		$pages_q1 = mf_mysql_query($pages_query1);
		if($pages_q1){echo '<p style="color:green;">Podstrony - utworzono strukturę tabeli pages</p>';}
		else {echo '<p style="color:red;">Podstrony - Nie udało się utworzyć struktury tabeli pages</p>'; echo mysql_error(); }
	
		$inst_data = date("Y-m-d H:i:s");
		$pages_q2 = mf_mysql_query("INSERT INTO `".$mf_prefix."logs` (`ID`, `IP`, `CZAS`, `AKCJA`, `TYP`) VALUES (2, '127.0.0.1', '".$inst_data."','Instalacja bazy SQL podstron', 3);");
	
	}
	if($users_db=='1'){ //instalacja uzytkownikow
		$users_query1 =
		" CREATE TABLE `".$mf_prefix."users` (
			`ID` INT NOT NULL AUTO_INCREMENT ,
			`USER` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
			`PASSWORD` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
			`MAIL` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
			`ACTIVE` INT NOT NULL ,
			`LASTLOGIN` DATETIME NOT NULL ,
			PRIMARY KEY ( `ID` )
			) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci 
			"; // tworzenie tabeli
		$users_q1 = mf_mysql_query($users_query1);
		if($users_q1){echo '<p style="color:green;">Użytkownicy - utworzono strukturę tabeli users</p>';}
		else {echo '<p style="color:red;">Użytkownicy - Nie udało się utworzyć struktury tabeli users</p>'; echo mysql_error(); }
	
		$inst_data = date("Y-m-d H:i:s");
		$users_q2 = mf_mysql_query("INSERT INTO `".$mf_prefix."logs` (`ID`, `IP`, `CZAS`, `AKCJA`, `TYP`) VALUES (3, '127.0.0.1', '".$inst_data."','Instalacja bazy SQL uzytkownikow', 3);");
	
	}
	
	echo "<p>Ilość wykonanych zapytań SQL: $mf_sql_queries</p>";
	?>
	</div>
	<?php 
}
else{
	?><div class="dark-matter">
		<h1>Instalacja m.framework - v 1.0.1.0 a</h1>
		<h3>Błąd!</h3>
		<p>Uruchom ponownie instalator</p>
	</div>
	<?php 
}
mf_gentime_stop();
?>
</body>
</html>