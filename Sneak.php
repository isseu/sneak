<?php 
 /***********************************************************************
 * Sneak.php - v 1.0 - 09/04/2013
 * Proyect Webpage: https://github.com/isseu/Sneak
 * Author: isseu (@isseu) http://www.isseu.com
 ************************************************************************/
$version = 1.0;

require( dirname(__FILE__) . '/src/lib.php');
$supported_methods = array( 
	"ASCII to HEX" => 1,
	"HEX to ASCII" => 2,
	"ASCII to BIN" => 3,
	"BIN to ASCII" => 4,
	"BIN to HEX" => 5,
	"HEX to BIN" => 6,
	"BASE64 Encode" => 7,
	"BASE64 Decode" => 8,
	"Backwards" => 9,
	"ASCII to ASCII CODE" => 10,
	"ASCII CODE to ASCII" => 11,
	"ASCII to SQL CHAR()" => 12,
	"ASCII to String.fromCharCode()" => 13,
	"URLEncode" => 14,
	"URLDecode" => 15,
	"l33t Encode" => 16,
	);

foreach (hash_algos() as $n => $hash) {
	$supported_methods[strtoupper($hash)] = count($supported_methods) + 1;
}

$keys = array_keys($supported_methods);

?>
<html>
<head>
	<title>Hasher</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="./src/style.css" rel="stylesheet" type="text/css" />
</head>
<script type="text/javascript" src="./src/ZeroClipboard-1.1.7/ZeroClipboard.js"></script>
<script type="text/javascript" src="./src/main.js"></script>
<div id="container">
	<div id="sneak_titulo">Sneak</div>
	<form method="post" accept-charset="UTF-8">
		<div class="aside">
			<textarea name="Data" id="Data"><?php

			if( isset($_POST['submit']) && isset($_POST['Data'])) {
				$text = $_POST['Data'];	
				$text = urldecode( stripslashes( $text) );
				echo htmlentities( $text );
			}

			?></textarea>
			<div id="sneak_result">
				<div id="res_text" name="res_text">
					<ol id="sneak_lista"><?php 
					if( isset($_POST['submit']) && isset($_POST['Data'])) {
						$eleccion = $_POST['cryptmethod'];
						settype( $eleccion, "integer" );
						$text = $_POST['Data'];	
						$text = urldecode( stripslashes( $text) );
						$orig_text = htmlentities( $text );
						$algs = hash_algos();
						if(in_array($eleccion, $supported_methods)){
							switch ($eleccion) {
									case 1:
										$text = asc2hex($text);
										break;
									case 2:
										$text = hex2asc($text);
										break;
									case 3:
										$text = asc2bin($text);
										break;
									case 4:
										$text = bin2asc($text);
										break;
									case 5:
										$text = binary2hex($text);
										break;
									case 6:
										$text = hex2binary($text);
										break;
									case 7:
										$text = base64_encode($text);
										break;
									case 8:
										$text = base64_decode( str_replace(" ", "", $text ) );
										break;
									case 9:
										$text = strrev( $text );
										break;
									case 10:
										$text = ascii2asciicode($text);
										break;
									case 11:
										$text = asciicode2ascii($text);
										break;
									case 12:
										$text = ascii2mysql($text);
										break;
									case 13:
										$text = ascii2fromcharcode($text);
										break;
									case 14:
										$text = urlencode($text);
										break;
									case 15:
										$text = urldecode($text);
										break;
									case 16:
										$text = leetencode($text);
										break;
									default:
										if($eleccion > count($supported_methods) - count(hash_algos()))
										{
											$text = hash(strtolower($keys[$eleccion - 1]), $text);
										}
										break;
								}	
						}else{
							$text = "Encriptacion no soportada.";
						}       	
						echo text2list(htmlentities($text));
					}
					?></ol>
				</div>
			</div>
			<div id="footer_aside">
				<div id="icons">
					<a id="hover" onclick="togglev()" title="No margins" ><img id="marginicon" src="./src/images/t.gif"></a>
					<a id="hover" onclick="document.getElementsByName('Data')[0].innerHTML = contenido_res();" title="Send up"><img id="upicon" src="./src/images/t.gif"></a>
					<a title="Copy to clipboard" id="copy-button" ><img id="copyicon" src="./src/images/t.gif"></a>
				</div>
			</div>
		</div>
		<div class="bside">
			<div class="sneak_contenedor_select">
				<select name="cryptmethod" id="cryptmethod" autofocus>
					<?php
						$eleccion = $_POST['cryptmethod'];
						settype( $eleccion, "integer" );
					?>
					<optgroup label="Encoding">
					<?php
					for ( $i = 0 ; $i < count($supported_methods) - count(hash_algos()); $i++ ) { 
						echo "<option ".(($eleccion == $i + 1)?"selected ":"")."value='".($i + 1)."'>".$keys[$i]."</option>";
					}
					?>
					</optgroup>
					<optgroup label="Hashing">
					<?php
					for ( $i = count($supported_methods) - count(hash_algos()) ; $i < count($supported_methods); $i++ ) { 
						echo "<option ".(($eleccion == $i + 1)?"selected ":"")."value='".($i + 1)."'>".$keys[$i]."</option>";
					}
					?>
					</optgroup>
				</select>
			</div>
			<input type="submit" name="submit" class="sneak_btn" value="OK" />
			<input type="reset" value="Clear" class="sneak_btn" onclick="document.getElementsByName('Data')[0].innerHTML = '';"/>
		</div>
		<div id="footer">
			<p>
				<font size="1">
					Sneak <?php printf("%.2f",$version); ?><br />
					Author <a href="http://www.isseu.com">Isseu</a>, &copy; <?php echo date('Y'); ?><br>
					Download script <a href="https://github.com/isseu/Sneak">here</a>
				</font>
			</p>
		</div>
	</form>
</div>
<script type="text/javascript">

var clip = new ZeroClipboard(document.getElementById("copy-button"), {
  moviePath: "./src/ZeroClipboard-1.1.7/ZeroClipboard.swf"
} );

clip.on( 'complete', function(client, args) {
  alert("Copied text to clipboard");
} );
clip.on( 'dataRequested', function ( client, args ) {
  clip.setText( contenido_res() );
} );

</script>

</body>
</html>