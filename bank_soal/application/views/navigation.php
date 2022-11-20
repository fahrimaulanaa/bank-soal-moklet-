<?php 
	$active_indk = '';
	$active_kd = '';
	$active_uj = '';
	$active_entry = '';
	$active_mp = '';
	
	if($this->uri->segment(1) == 'indikator'){
		$active_indk = 'active';
	}
	else if($this->uri->segment(1) == 'kd'){
		$active_kd = 'active';
	}
	else if($this->uri->segment(1) == 'ujian'){
		$active_uj = 'active';
	}
	else if($this->uri->segment(1) == 'entry'){
		$active_entry = 'active';
	}
	else if($this->uri->segment(1) == 'mp'){
		$active_mp = 'active';
	}
	else {
		$active = '';
	}

?>

<ul id="menu_tab">	
	<li id="tab_absen"><?php echo anchor('entry', '<i class="glyphicon glyphicon-th-list"></i> Entry','class="'.$active_entry.'"');?></li>
	<!-- <li id="tab_rekap"><?php echo anchor('mp', 'MP');?></li> -->
	<li id="tab_siswa"><?php echo anchor('kd', '<i class="glyphicon glyphicon-star"></i> Kompetensi Dasar','class="'.$active_kd.'"');?></li>
	<li id="tab_semester"><?php echo anchor('indikator', '<i class="glyphicon glyphicon-heart"></i> Indikator','class="'.$active_indk.'"');?></li>
	<!--li id="tab_kelas"><?php echo anchor('kelas', 'Kelas');?></li-->
	<li id="tab_ujian"><?php echo anchor('ujian', '<i class="glyphicon glyphicon-pencil"></i> Ujian','class="'.$active_uj.'"');?></li>
	<li id="tab_logout"><?php echo anchor('login/logout', '<i class="glyphicon glyphicon-log-out"></i> Logout', array('onclick' => "return confirm('Anda yakin akan logout?')"));?></li>
</ul>