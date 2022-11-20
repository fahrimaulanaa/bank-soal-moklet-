<?php
	class Siswa extends CI_Controller {
	
	  public function index()
	  {
		$this->load->library('calendar');
		echo $this->calendar->generate();
		
		
	    $this->load->model('Siswa_model', 'siswa',true);
			
	    $data['siswa'] = $this->siswa->cari_semua()->result();
			
	    $this->load->view('siswa_view', $data);
		
	  }
		
	}
?>