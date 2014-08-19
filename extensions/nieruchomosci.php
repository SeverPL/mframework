<?php

function nier_szukaj_form(){?>
	<form class="SzukajForm">
		<input type="text" value="Typ nieruchomości" name="typ" />
		<select name="operacja" >
			<option>Typ operacji</option>
		</select>
		<input type="text" value="Miasto" name="miasto" />
		<input type="submit" value="Szukaj" />
	</form>
<?php }

?>