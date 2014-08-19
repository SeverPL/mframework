/*
 * Testowy silnik jQuery
 * v.0.0.0.1 a
 */

$( document ).ready(function() {
	function chowacz($chowacz, $chowane){
		$($chowacz).click(function(){
			$($chowane).fadeOut(1500);
		});
	}
	function zastepywacz($zastepywacz, $zastepywane, $string){
		$($zastepywacz).click(function(){
			$($zastepywane).html($string);
		});
	}
	
	//chowacz('#cbutton', '#cookie');
	//zastepywacz('#cbutton', '#cookie', '<h1>Test</h1>');
});