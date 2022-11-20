	<html>
	<head>
		<title>Data Siswa</title>
	</head>
	<body>
	<h2>Data Siswa</h2>
	<?php
	foreach($siswa as $row) {
		echo 'Nama : ' . $row->nis . '<br>';
		echo 'Nama : ' . $row->nama . '<br>';
		echo '<hr>';
	}
	?>
	</body>
</html>