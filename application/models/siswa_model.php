<?php 
	class Siswa_model extends CI_Model {
		
		public function cari_semua()
		{
			//$this->load->database();
			return $this->db->get('siswa');
		}	
		
	}
?>