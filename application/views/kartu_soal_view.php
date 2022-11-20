<!DOCTYPE html>
<html>
<head>
	<title>Kartu Soal | <?php echo $info_ujian->ujian; ?></title>
	<style type="text/css">
		body {
			font-size: 15px;
			font-family: Arial;
		}

		table {
			border-collapse: collapse;
		}
	</style>
</head>
<body>
	<table>
		<tr>
			<td>Nama Ujian</td>
			<td>:</td>
			<td colspan="2"><?php echo $info_ujian->ujian; ?></td>
		</tr>
		<tr>
			<td>Jumlah Soal</td>
			<td>:</td>
			<td colspan="2"><?php echo count($kartu_soal); ?></td>
		</tr>
		<tr>
			<td>Tgl Ujian</td>
			<td>:</td>
			<td colspan="2"><?php echo date('d F Y', strtotime($info_ujian->tgl_ujian)); ?></td>
		</tr>
	</table>
	<table border="1">
		<tr>
			<th>NO</th>
			<th>KD</th>
			<th>INDIKATOR</th>
			<th>SOAL</th>
			<th>JWB</th>
		</tr>
		<?php
			$no = 0;
			$img = "";
			for ($i=0; $i < count($kartu_soal); $i++) {
				if($kartu_soal[$i]['gambar'] != NULL){
					$img = '<img src="'.base_url('uploads/'.$kartu_soal[$i]['id_soal'].'.jpg').'">';
				}
				echo '
					<tr>
						<td>'.++$no.'</td>
						<td>'.$kartu_soal[$i]['kd'].'</td>
						<td>'.$kartu_soal[$i]['indikator'].'</td>
						<td>
							'.$img.'
							<pre>'.$kartu_soal[$i]['soal'].'</pre>
						</td>
						<td>
							<center>'.strtoupper($kartu_soal[$i]['jawaban']).'</center>
						</td>
					</tr>
				';
				$img = "";
			}

		?>
	</table>
	<script type="text/javascript" src="<?php echo base_url()?>asset/js/jquery-1.12.3.js"></script>
	<script type="text/javascript">
		$("img").error(function(){
		     $(this).hide();
		});
	</script>
</body>
</html>