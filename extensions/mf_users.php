<?php
/* Rozszerzenie 'Users' dla m.framework
 * Wersja rozszerzenia: 1.1.0.0 a
* 21 Czerwca 2014 13:35
* Mateusz Wiśniewski © 2014
*
* mf_users
* WYMAGA KLASY mf_db
*
* klasy CSS:
*
* Pola bazy danych:
* ID
* USER - login
* PASSWORD - haslo
* MAIL - adres email
* ACTIVE - Aktywnosc konta
* LASTLOGIN - data ostatniego logowania
*/
$mf_ver_users = '2.0.0.0a';

function mf_users_logincheck($metoda){ //funkcja zwraca true jesli uzytkownik jest zalogowany, false jesli nie
	/*
	 * Dozwolone metody sprawdzenia:
	 * sql - Sprawdza zalogowanie za pomocą SQLa i ciastek
	 * cookie - sprawdza zalogowanie tylko za pomocą ciasteczka 'zalogowany' - bez weryfikacji danych logowania
	 */
	global $mf_prefix;
	if($metoda=='cookie'){
		if(isset($_SESSION['mf_zalogowany'])){
			if($_SESSION['mf_zalogowany']==true){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
		
	}
	elseif($metoda=='sql'){	
		if(isset($_SESSION['mf_user']) AND isset($_SESSION['mf_pass'])){
			$userlogin = $_SESSION['mf_user'];
			$userpass = $_SESSION['mf_pass'];
		
			$query = "SELECT * FROM ".$mf_prefix."users WHERE `USER`='$userlogin' AND `PASSWORD`='$userpass'";
			$result = mf_mysql_query($query);
			$check = mysql_num_rows($result);
			if($check>0){
				return true; //user niezalogowany
			}
			else {
				return false; //user niezalogowany
			}
		}
		else{
			return false; //user niezalogowany
		}
	}
}

function mf_users_login($user, $password){ //logowanie uzytkownika podanymi danymi do logowania
	global $mf_prefix;
	$query = "SELECT * FROM ".$mf_prefix."users WHERE `USER`='$user' AND `PASSWORD`='$password'";
	$result = mf_mysql_query($query);
	$check = mysql_num_rows($result);
	if($check>0){ //poprawne zalogowanie
		$_SESSION['mf_userid'] = '';
		$_SESSION['mf_zalogowany'] = true;
		$_SESSION['mf_user'] = $user;
		$_SESSION['mf_pass'] = $password;
		$lastlogindate = date('Y-m-d H:i:s');
		$query2 = "UPDATE ".$mf_prefix."users SET LASTLOGIN='$lastlogindate' WHERE `USER`='$user'"; //aktualizacja daty ostatniego logoawania
		$log_content = "Zalogowano: $user";
		mf_log($log_content, '1');		
		return true; 
	}
	else {
		return false;
	}
}

function mf_users_createuser($user, $password, $email){ // tworzenie uzytkownika z podanymi danymi
	global $mf_prefix;
	$query = "SELECT * FROM ".$mf_prefix."users WHERE `USER`='$user'";
	$result = mf_mysql_query($query);
	$check = mysql_num_rows($result);
	if($check==0){ //jezeli nie ma takiego użytkownika
		$query = "SELECT * FROM ".$mf_prefix."users WHERE `MAIL`='$email'";
		$result = mf_mysql_query($query);
		$check = mysql_num_rows($result);
		if($check==0){ // rejestracja użytkownika
			$query = "INSERT INTO ".$mf_prefix."users SET `USER`='$user',`PASSWORD`='$password', `MAIL`='$email'";
			$insert = mf_mysql_query($query);
			if($insert){ //rejestracja uzytkownika pomyslna
				$log_content = "Rejestracja użytkownika $user na adres email $email";
				mf_log($log_content, '1');
				return true;
			}
			else{ //zwrot błędu sql
				$sql_error = mysql_error();
				$log_content = "Błąd SQL przy rejestracji użytkownika $user na adres email $email - $sql_error";
				mf_log($log_content, '4');
				return $sql_error;
			}
		}
		else{
			$mail_error = "Podany adres e-mail został już zarejestrowany";
			return $mail_error;
		}
	}
	else{ //zwrot błędu jeśli użytkownik już istnieje
		$user_error = "Użytkownik już istnieje";
		return $user_error;
	}
}

 class mf_users{
 	private $_id = null;
 	private $_user = null;
 	private $_password = null;
 	private $_mail = null;
 	private $_active = null;
 	private $_lastlogin = null;
 	
 	public function setUsername($user){ //ustawienie nazwy uzytkownika
 		$this->_user = $user;
 	}
 	public function getUsername(){ //pobranie nazwy uzytkownika
 		return $this->_user;
 	}
 	public function setPassword($pass){ //ustawienie hasla
 		$this->_password = $pass;
 	}
 	public function setMail($mail){ //ustawienie maila
 		$this->_mail = $mail;
 	}
 	public function getMail(){ //pobraniemaila
 		return $this->_mail;
 	}
 	public function setActive($active){
 		$this->_active = $active;
 	}
 	public function getActive(){
 		return $this->_active;
 	}
 	
 	public function setUserdata($user, $pass, $mail){
 		$this->_user = $user;
 		$this->_password = $pass;
 		$this->_mail = $mail;
 	}
 	public function createUser(){ //tworzenie uzytkownika
 		global $mf_prefix;
 		$db = new mf_db; //WYMAGA KLASY mf_db
 		$query = "SELECT * FROM ".$mf_prefix."users WHERE `USER`='$this->_user'";
 		$result = $db->mf_mysql_query($query);
 		$check = mysql_num_rows($result);
 		if($check==0){ //jezeli nie ma takiego użytkownika
 			
 			$query = "SELECT * FROM ".$mf_prefix."users WHERE `MAIL`='$this->_email'";
 			$result = $db->mf_mysql_query($query);
 			$check = mysql_num_rows($result);
 			
 			if($check==0){ // rejestracja użytkownika
 				$query = "INSERT INTO ".$mf_prefix."users SET `USER`='$user',`PASSWORD`='$password', `MAIL`='$email'";
 				$insert = $db->mf_mysql_query($query);
 				if($insert){ //rejestracja uzytkownika pomyslna
 					$log_content = "Rejestracja użytkownika $user na adres email $email";
 					$db->mf_log($log_content, '1');
 					return true;
 				}
 				else{ //zwrot błędu sql
 					$sql_error = mysql_error();
 					$log_content = "Błąd SQL przy rejestracji użytkownika $user na adres email $email - $sql_error";
 					$db->mf_log($log_content, '4');
 					return $sql_error;
 				}
 			}
 			else{
 				$mail_error = "Podany adres e-mail został już zarejestrowany";
 				return $mail_error;
 			}
 		}
 		else{ //zwrot błędu jeśli użytkownik już istnieje
 			$user_error = "Użytkownik już istnieje";
 			return $user_error;
 		}
 	}
 	public function logincheck($metoda){ //funkcja zwraca true jesli uzytkownik jest zalogowany, false jesli nie
	/*
	 * Dozwolone metody sprawdzenia:
	 * sql - Sprawdza zalogowanie za pomocą SQLa i ciastek
	 * cookie - sprawdza zalogowanie tylko za pomocą ciasteczka 'zalogowany' - bez weryfikacji danych logowania
	 */
	global $mf_prefix;
	$db = new mf_db;
	if($metoda=='cookie'){
		if(isset($_SESSION['mf_zalogowany'])){
			if($_SESSION['mf_zalogowany']==true){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
		
	}
	elseif($metoda=='sql'){	
		if(isset($_SESSION['mf_user']) AND isset($_SESSION['mf_pass'])){
			$userlogin = $_SESSION['mf_user'];
			$userpass = $_SESSION['mf_pass'];
		
			$query = "SELECT * FROM ".$mf_prefix."users WHERE `USER`='$userlogin' AND `PASSWORD`='$userpass'";
			$result = $db->mf_mysql_query($query);
			$check = mysql_num_rows($result);
			if($check>0){
				return true; //user niezalogowany
			}
			else {
				return false; //user niezalogowany
			}
		}
		else{
			return false; //user niezalogowany
		}
	}
}
 }