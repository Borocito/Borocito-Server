<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST" && array_key_exists("file", $_FILES)) {
		// if (is_uploaded_file($_FILES['file']['tmp_name']) && file_exists($_FILES['file']['tmp_name'])) {
		move_uploaded_file($_FILES["file"]["tmp_name"], "Files/" . $_FILES["file"]["name"]);
		// }
	} else {
		die("
<h1>Archivos de telemetria</h1>
<h3>Borocito Server-Side</h3>
<p>
	<li>Envia <em>FILE as binary</em> y guarda el archivo dentro del servidor.</li>
	<ul>
		<strong>Uso</strong>:
		<pre>
	[POST multipart/form-data:
		params:
			file
	]
		</pre>
		<ul>
			<li>file: Binary content from ClientSide.</li>
		</ul>
	</ul>
</p>
		");
	}
?>