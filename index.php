<?php
include('include/launch.php');
$engine = new mf_engine;
$head = new mf_head;

$engine->mf_gentime_start();
$head->html_doctype();
$head -> html_meta_tags("all", "Mateusz Wiśniewski", "slowa kluczowe", "opis", "TYTUŁ STRONY", "2 days", "pl");

$page = new mf_pages;
$page->getPage(1);
if($page->getHidden()==0){
	echo '<h2>'.$page->getTitle().'</h2>';
	echo '<small>'.$page->getCreated().'</small><br />';
	echo $page->getContent();
}


$user = new mf_users;
$user->setUsername("Mati");
echo $user->getUsername();


?>
<?php 
$engine -> mf_gentime_stop();

?>