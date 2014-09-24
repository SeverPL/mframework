<?php
/* Rozszerzenie 'Upload (adm)' dla m.framework
 * Wersja rozszerzenia: 0.1.0.0a
* 23 Września 2014 15:21
* Mateusz Wiśniewski © 2014
*/
class mf_adm_upload{
	private $_path = null;
	private $_filepath = null;
	private $_zippath = null;
	private $_zipfilepath = null;
	private $_thumbpath = null;
	
	private $_zipfile = null; //plik zip w sciezce zippath
	private $_filename = null;
	private $_tmpfilename = null;
	private $_filesize = null;
	private $_imagename = null;
	private $_imagepath = null;
	
	private $_helppath=null;
	
	/**
	 * 
	 * @param string $helppath sciezka do glownego katalogu
	 */
	public function __construct($helppath){
		$this->_helppath = $helppath; //sciezka do pliku
		$this->_tmpfilename = $_FILES['mf_file'] ['tmp_name'];
		$this->_filename = $_FILES['mf_file'] ['name'];
		$this->_filesize = $_FILES['mf_file'] ['size'];
		$this->_path = $this->_helppath.'upload/';
		$this->_zippath = $this->_helppath.'upload/zip/';
		$this->_thumbpath = $this->_helppath.'upload/thumb/';
		
		$this->_zipfile = "$this->_zippath".$this->_filename; //sciezka sklejona z nazwa pliku
	}


	public function __destruct()
	{
		$des = unlink($this->_zipfile);
		if($des){
			echo 'Plik zip został usunięty!';
		}		
	}
	/*
	 * Przetwarza plik zip
	 */
	public function zipProcess(){
		try{
			$this->zipReceive();
			$this->zipOpen();
		}
		catch ( Exception $exception ) {
			echo $exception->getMessage();
		}
	}
	private function zipReceive(){
		if (is_uploaded_file ( $this->_tmpfilename )) {
			$move = move_uploaded_file ( $this->_tmpfilename, $this->_zipfile ); //przeniesienie pliku tymczasowego do katalogu upload/zip
			if($move){
				echo "Plik: <strong>$this->_filename</strong> o rozmiarze
				<strong>$this->_filesize bajtów</strong> został przesłany na serwer!<br /><br />";
				$this->_filesize = $this->_filesize / 1024;
			}
			else{
				throw new Exception('Nie można przenieść pliku!');
			}
		}		
		else {
			throw new Exception('Wystąpił błąd!');
		}		
	}
	private function zipOpen(){
		// sprawdza, czy funkcja 'zip_open' jest obslugiwana przez serwer
		if (function_exists ( 'zip_open' )) {
			$zip = zip_open ( $this->_zipfile ); // pobranie uchwytu
			if ($zip) {
				// petla odczytujaca kolejny rekord z archiwum
				while ( $zip_entry = zip_read ( $zip ) ) {
					// jesli nazwa 'zip_entry_name' jest zakonczona jest pzrez '/' to znaczy ze mamy do czynienia z folderem
					if (preg_match ( "/\/$/", zip_entry_name ( $zip_entry ) )) {
						// tworzenie folderu w $sciezka
						@mkdir ( $this->_path . zip_entry_name ( $zip_entry ) );
					} 					

					// LIK
					else {
						// 'otwieranie' rekordu
						if (zip_entry_open ( $zip, $zip_entry )) {
							// tworzenie pliku $sciezka. zip_entry_name ( $zip_entry )
							$f = fopen ( $this->_path . zip_entry_name ( $zip_entry ), 'w' );
							// zapisywanie do pliku zip_entry_read ( $zip_entry, zip_entry_filesize ( $zip_entry ) ); zip_entry_filesize okresla glugosc rozpakowan
							fwrite ( $f, zip_entry_read ( $zip_entry, zip_entry_filesize ( $zip_entry ) ) );
							$this->_imagename = zip_entry_name ( $zip_entry );
							fclose ( $f );
							$this->processZipImage();
							zip_entry_close ( $zip_entry );
						}
					}
				}
				zip_close ( $zip );
			}
		} 
		else{
			throw new Exception('Brak obsługi plików zip na serwerze!');
		}
	}
	private function processZipImage(){
		define ( "MAX_SIZE", "16000" );
		$this->_imagepath = "$this->_path".$this->_imagename;
		if (isset ( $this->_imagepath )) {
			$filename = stripslashes ( $this->_imagepath );
			$extension = mf_engine::getExtension ( $filename );
			$extension = strtolower ( $extension );
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				throw new Exception('Nieznane rozszerzenie');
			} else {
				$size = filesize ( $this->_imagepath );
		
				if ($size > MAX_SIZE * 1024) {
					throw new Exception('Przekroczono dopuszczalny rozmiar zdjęcia!');
				}
		
				if ($extension == "jpg" || $extension == "jpeg") {
					$src = imagecreatefromjpeg ( $this->_imagepath );
				} else if ($extension == "png") {
					#$uploadedfile = $_FILES ['file'] ['tmp_name'];
					$src = imagecreatefrompng ( $this->_imagepath );
				} else {
					$src = imagecreatefromgif ( $this->_imagepath );
				}
		
				list ( $width, $height ) = getimagesize ( $this->_imagepath );
				if ($height == $width) {
					$orient = "kwadrat";
				} else {
					if ($height < $width) {
						$orient = "poziom";
					} else {
						$orient = "pion";
					}
				}
		
				$new = 700; // dluzszy bok "<"
				if ($height < $width) {
					$newheight = ($height / $width) * $new;
					$tmp = imagecreatetruecolor ( $new, $newheight );
				} else {
					$newwidth = ($width * $new) / $height;
					$tmp = imagecreatetruecolor ( $newwidth, $new );
				}
		
				$new1 = 200; // zawsze szerokosc
				if ($height < $width) {
					$newheight1 = ($height / $width) * $new1;
					$tmp1 = imagecreatetruecolor ( $new1, $newheight1 );
				} else {
					$newheight1 = ($height / $width) * $new1; // tutej
					$tmp1 = imagecreatetruecolor ( $new1, $newheight1 ); // tutej
				}
		
				if ($height < $width) {
					imagecopyresampled ( $tmp, $src, 0, 0, 0, 0, $new, $newheight, $width, $height );
				} else {
					imagecopyresampled ( $tmp, $src, 0, 0, 0, 0, $newwidth, $new, $width, $height );
				}
		
				if ($height < $width) {
					imagecopyresampled ( $tmp1, $src, 0, 0, 0, 0, $new1, $newheight1, $width, $height );
				} else {
					imagecopyresampled ( $tmp1, $src, 0, 0, 0, 0, $new1, $newheight1, $width, $height );
				}
		
				
		
				$link = "$this->_path" . $this->_imagename;
				$link1 = $this->_thumbpath."/200" . $this->_imagename;

		
				imagejpeg ( $tmp, $link, 100 );
				imagejpeg ( $tmp1, $link1, 100 );		
				imagedestroy ( $src );
				#imagedestroy ( $tmp );

		
				$tlink = $this->_imagepath;
				echo $this->_imagepath.'<br />';
				#mysql_query ( "INSERT INTO t4u_upload (LINK, UID, THUMB, SCRIPTION, ORIENT) VALUES ('" . $link . "', '" . $uid . "','" . $tlink . "','" . $desc . "','" . $orient . "')" ) or die ( mysql_error () );
			}
		}
	}
}