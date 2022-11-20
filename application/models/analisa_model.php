<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Analisa_model extends CI_Model {

    public $db_tabel = 'ujian';
    public $db_tabel_soal = 'soal';
    public $id_mp = 0;
    public $id_ujian = 0;
    //public $id_indikator = 0;


    public function __construct()
    {
        parent::__construct();
        $this->id_mp=$this->session->userdata('id_mp_sekarang');
        $this->id_ujian=$this->session->userdata('id_ujian_sekarang');
    }

    public function cari_mp()
    {
        $query= $this->db->where('id_mp', $this->id_mp)
            ->get('mp');
        $row=$query->row();
        return $row->mp;
    }

    public function cari_semua()
    {
        return $this->db->order_by('id_ujian', 'ASC')
            ->where('id_mp', $this->id_mp)
            ->get($this->db_tabel)
            ->result();
    }

    public function cari_semua_soal()
    {
        return $this->db->order_by('soal.id_soal', 'ASC')
            ->where('soal.id_mp', $this->id_mp)
            ->join('indikator', 'indikator.id_indikator = soal.id_indikator')
            ->join('kd', 'kd.id_kd = indikator.id_kd')
            ->get($this->db_tabel_soal)
            ->result();
    }

    public function cari($id_ujian)
    {
        return $this->db->where('id_ujian', $id_ujian)
            ->where('id_mp', $this->id_mp)
            ->limit(1)
            ->get($this->db_tabel)
            ->row();
    }

    public function cari_soal($id_indikator)
    {
        return $this->db->where('id_indikator', $id_indikator)
                ->get($this->db_tabel)
                ->result();
    }

    public function jumlah_soal($id_ujian)
    {
        $query= $this->db->where('id_ujian', $id_ujian)->get('ujian');
        $row=$query->row();
        return $row->jumlah_soal;
    }

    public function get_kelas($id_ujian)
    {
        $return[''] = 'Semua Kelas';
        $query = $this->db->distinct()->select('id_kelas')->where('id_ujian', $id_ujian)->get('tb_jawaban');
        foreach($query->result_array() as $row){
            $return[$row['id_kelas']] = $row['id_kelas'];
        }
        return $return;
    }

    public function ambil_soal($id_soal){
        return $this->db->where('id_soal', $id_soal)
                        ->where('id_mp', $this->id_mp)
                        ->limit(1)
                        ->get('soal')
                        ->result();
    }

    public function get_data_soal($id_soal){
        return $this->db->where('soal.id_mp', $this->id_mp)
                        ->where('soal.id_soal', $id_soal)
                        ->join('indikator', 'indikator.id_indikator = soal.id_indikator')
                        ->join('kd', 'kd.id_kd = indikator.id_kd')
                        ->limit(1)
                        ->get('soal')
                        ->row();
    }

    public function publish_ujian($id)
    {
        $ubah = array('share' => '1');
        $this->db->where('id_ujian', $id)
                 ->update('ujian', $ubah);

        return true;
    }

    public function unpublish_ujian($id)
    {
        $ubah = array('share' => '0');
        $this->db->where('id_ujian', $id)
                 ->update('ujian', $ubah);

        return true;
    }

    public function buat_tabel($data,$start)
    {
        $this->load->library('table');

        // buat class zebra di <tr>,untuk warna selang-seling
        $tmpl = array(
                'table_open'     => '<table border="0" cellpadding="4" cellspacing="0" class="datatable table table-striped table-bordered">'
                );
        $this->table->set_template($tmpl);

        /// heading tabel
        $this->table->set_heading('No', 'ID Ujian','Nama Ujian', 'Aksi');

        $no = $start;
        foreach ($data as $row)
        {
            if($row->share == '0'){
                $link = anchor('analisa/publish/'.$row->id_ujian,'<i class="glyphicon glyphicon-send"></i> Publish',array('class' => 'btn btn-success btn-sm'));
            } else {
                $link = anchor('analisa/unpublish/'.$row->id_ujian,'<i class="glyphicon glyphicon-send"></i> UnPublish',array('class' => 'btn btn-warning btn-sm'));
            }
            $this->table->add_row(
                ++$no,
                $row->id_ujian,
                $row->ujian,
                anchor('analisa/lihat/'.$row->id_ujian,'<i class="glyphicon glyphicon-refresh"></i> Analisa Hasil Ujian',array('class' => 'btn btn-info btn-sm')).'    '.
                $link
            );
        }
        $tabel = $this->table->generate();

        return $tabel;
    }

    public function buat_tabel_analisa($data,$jml_soal,$list_soal)
    {
        $this->load->library('table');

        // buat class zebra di <tr>,untuk warna selang-seling
        $tmpl = array(
                'table_open'     => '<table border="0" cellpadding="4" cellspacing="0" class="datatable table table-striped table-bordered" style="width: 2300px">'
                );
        $this->table->set_template($tmpl);

        //table header
        $cell_no = array('data' => 'No', 'style' => 'vertical-align: middle;font-weight: bold;text-transform: uppercase;background: #e5e5e5;', 'rowspan' => 2);
        $cell_nama = array('data' => 'Nama Siswa', 'style' => 'vertical-align: middle;font-weight: bold;text-transform: uppercase;background: #e5e5e5;width:15%;', 'rowspan' => 2);
        $cell_kelas = array('data' => 'Kelas', 'style' => 'vertical-align: middle;font-weight: bold;text-transform: uppercase;background: #e5e5e5;', 'rowspan' => 2);
        $cell_soal = array('data' => 'Butir Soal <span style="font-size:10px;text-transform: none;color: #a51c1b;">(Klik nomor soal untuk melihat detil soal)</span>', 'style' => 'font-weight: bold;text-transform: uppercase;background: #e5e5e5;', 'colspan' => $jml_soal);
        $cell_benar = array('data' => 'Benar', 'style' => 'vertical-align: middle;font-weight: bold;text-transform: uppercase;background: #e5e5e5;', 'rowspan' => 2);
        $cell_salah = array('data' => 'Salah', 'style' => 'vertical-align: middle;font-weight: bold;text-transform: uppercase;background: #e5e5e5;', 'rowspan' => 2);
        $cell_nilai = array('data' => 'Nilai', 'style' => 'vertical-align: middle;font-weight: bold;text-transform: uppercase;background: #e5e5e5;', 'rowspan' => 2);
        $cell_status = array('data' => 'Status', 'style' => 'vertical-align: middle;font-weight: bold;text-transform: uppercase;background: #e5e5e5;', 'rowspan' => 2);
    
        $this->table->add_row($cell_no,$cell_nama,$cell_kelas,$cell_soal,$cell_benar,$cell_salah,$cell_nilai,$cell_status);
        $array_soal = array();

        //get id soal
        $id_soal = array();
        $data_soal  = explode(",", $list_soal);
        for ($i = 0; $i < count($data_soal); $i++) {

            $pecah_soal = explode(":",$data_soal[$i]);

            $id_soal[$i] = $pecah_soal[1];
        }
        //sort id soal untuk link detil soal
        for($z=0;$z<count($id_soal);$z++){
            for($x=$z+1;$x<count($id_soal);$x++){
                if($id_soal[$z] > $id_soal[$x]){
                    $temp = $id_soal[$x];
                    $id_soal[$x] = $id_soal[$z];
                    $id_soal[$z] = $temp;
                }
            }
        }
        
        //menampilkan link id soal di tabel
        for($i=0;$i<$jml_soal;$i++){
            $no = $i+1;
            $array_soal[$i] = array('data' => "<a href='#' onclick='detil_soal($id_soal[$i],$no)' style='color: #fff'>$no</a>", 'style' => 'font-weight: 500;text-align:center;background: #e06968;');
        }
        $this->table->add_row($array_soal);

        //data hasil ujian siswa
        $no = 0;
        $hitung_benar = array(); //untuk menghitung jumlah benar per butir soal
        //inisialisasi
        for($x=0;$x<$jml_soal;$x++){
            $hitung_benar[$x] = 0;
        }

        if(!empty($data)){
            foreach ($data as $row)
            {
                //get id soal dan jawaban siswa
                $id_soal = array();
                $ambil_jawaban = $row->jawaban;
                $data_jawaban  = explode(",", $ambil_jawaban);
                for ($i = 0; $i < count($data_jawaban); $i++) {

                    $pecah_jawaban = explode(":",$data_jawaban[$i]);

                    $id_soal[$i] = $pecah_jawaban[0];
                    $jawaban_siswa[$i] = $pecah_jawaban[1];

                }

                //sort id soal dan jawaban siswa
                for($z=0;$z<count($id_soal);$z++){
                    for($x=$z+1;$x<count($id_soal);$x++){
                        if($id_soal[$z] > $id_soal[$x]){
                            $temp = $id_soal[$x];
                            $temp2 = $jawaban_siswa[$x];

                            $id_soal[$x] = $id_soal[$z];
                            $jawaban_siswa[$x] = $jawaban_siswa[$z];

                            $id_soal[$z] = $temp;
                            $jawaban_siswa[$z] = $temp2;
                        }
                    }
                }

                //get kunci dijadikan array
                for ($a = 0; $a < count($id_soal); $a++) {
                    $kunci_jawaban = $this->ambil_soal($id_soal[$a]);

                    foreach ($kunci_jawaban as $value) {
                        $_kunci[$a] = $value->jawaban;
                    }

                }

                //data add row
                $confirm="&#039;Apakah anda yakin menghapus jawaban siswa atas nama ".strtoupper($row->nama_siswa)."?&#039;";
                $array_add_row = array();
                $array_add_row[0] = ++$no;
                $array_add_row[1] = strtoupper($row->nama_siswa)." <a href='".base_url('analisa/reset_ujian/'.$this->uri->segment(3).'/'.$row->id)."' class='btn btn-default btn-xs' onclick='return confirm($confirm)'><i class='glyphicon glyphicon-refresh'></i> Reset</a>";
                $array_add_row[2] = $row->id_kelas;
                
                for($y=0;$y<count($id_soal);$y++){
                    //cek kunci dengan jawaban siswa
                    if(strtolower($jawaban_siswa[$y]) == $_kunci[$y]){
                        $array_add_row[$y+3] = array('data' => '<i class="glyphicon glyphicon-ok" style="color: #4CAF50;"></i>','style'=>'text-align:center');
                        $hitung_benar[$y] += 1;
                        
                    } else {
                        if(strtolower($jawaban_siswa[$y]) == '-'){
                            $array_add_row[$y+3] = array('data' => '<i class="glyphicon glyphicon-minus" style="color: #b9b9b9;"></i>','style'=>'text-align:center');
                            $hitung_benar[$y] += 0;
                        } else {
                            $array_add_row[$y+3] = array('data' => strtoupper($jawaban_siswa[$y]),'style'=>'text-align:center;color: #e06968;font-weight:bold');
                            $hitung_benar[$y] += 0;
                        }
                        
                    }
                }

                $array_add_row[count($id_soal)+3] = $row->jml_benar;
                $array_add_row[count($id_soal)+4] = $row->jml_salah;
                $array_add_row[count($id_soal)+5] = $row->nilai;

                if($row->status == 1){
                    $status = '<label class="label label-success">Selesai</label>';
                } else {
                    $status = '<label class="label label-warning">Sedang Mengerjakan</label>';
                }
                $array_add_row[count($id_soal)+6] = $status;
            
                $this->table->add_row($array_add_row);
            }
        } else {
            $row_null = array('data' => 'Data hasil ujian belum tersedia', 'style' => 'font-weight: bold;', 'colspan' => $jml_soal+6);
            $this->table->add_row($row_null);
        }

        //add row jumlah benar per butir soal
        $array_add_row_footer = array();
        $row_jml = array('data' => 'Jumlah Benar Per Soal', 'style' => 'background: #e5e5e5;font-weight: bold;text-transform: uppercase;text-align:right;', 'colspan' => 3);
        $array_add_row_footer[0] = $row_jml;
        for($a=0;$a<$jml_soal;$a++){
            $array_add_row_footer[$a+1] = array('data' => $hitung_benar[$a], 'style' => 'font-weight: bold;text-align:center;background: #e5e5e5;');
        }
        $array_add_row_footer[$jml_soal+1] = array('data' => '', 'style' => 'background: #e5e5e5;');
        $array_add_row_footer[$jml_soal+2] = array('data' => '', 'style' => 'background: #e5e5e5;');
        $array_add_row_footer[$jml_soal+3] = array('data' => '', 'style' => 'background: #e5e5e5;');

        $this->table->add_row($array_add_row_footer);

        $tabel = $this->table->generate();

        return $tabel;
    }


    public function ekspor($data,$jml_soal,$list_soal){
        //get id soal
        $id_soal = array();
        $data_soal  = explode(",", $list_soal);
        for ($i = 0; $i < count($data_soal); $i++) {

            $pecah_soal = explode(":",$data_soal[$i]);

            $id_soal[$i] = $pecah_soal[1];
        }
        //sort id soal untuk link detil soal
        for($z=0;$z<count($id_soal);$z++){
            for($x=$z+1;$x<count($id_soal);$x++){
                if($id_soal[$z] > $id_soal[$x]){
                    $temp = $id_soal[$x];
                    $id_soal[$x] = $id_soal[$z];
                    $id_soal[$z] = $temp;
                }
            }
        }

        //data hasil ujian siswa
        $no = 0;
        $hitung_benar = array(); //untuk menghitung jumlah benar per butir soal
        //inisialisasi
        for($x=0;$x<$jml_soal;$x++){
            $hitung_benar[$x] = 0;
        }

        if(!empty($data)){
            $index_siswa = 0;
            foreach ($data as $row){
                //get id soal dan jawaban siswa
                $id_soal = array();
                $ambil_jawaban = $row->jawaban;
                $data_jawaban  = explode(",", $ambil_jawaban);
                for ($i = 0; $i < count($data_jawaban); $i++) {

                    $pecah_jawaban = explode(":",$data_jawaban[$i]);

                    $id_soal[$i] = $pecah_jawaban[0];
                    $jawaban_siswa[$i] = $pecah_jawaban[1];

                }

                //sort id soal dan jawaban siswa
                for($z=0;$z<count($id_soal);$z++){
                    for($x=$z+1;$x<count($id_soal);$x++){
                        if($id_soal[$z] > $id_soal[$x]){
                            $temp = $id_soal[$x];
                            $temp2 = $jawaban_siswa[$x];

                            $id_soal[$x] = $id_soal[$z];
                            $jawaban_siswa[$x] = $jawaban_siswa[$z];

                            $id_soal[$z] = $temp;
                            $jawaban_siswa[$z] = $temp2;
                        }
                    }
                }

                //get kunci dijadikan array
                for ($a = 0; $a < count($id_soal); $a++) {
                    $kunci_jawaban = $this->ambil_soal($id_soal[$a]);

                    foreach ($kunci_jawaban as $value) {
                        $_kunci[$a] = $value->jawaban;
                    }

                }

                //data add row
                $array_add_row[$index_siswa][0] = ++$no;
                $array_add_row[$index_siswa][1] = strtoupper($row->nama_siswa);
                $array_add_row[$index_siswa][2] = $row->id_kelas;
                
                for($y=0;$y<count($id_soal);$y++){
                    //cek kunci dengan jawaban siswa
                    if(strtolower($jawaban_siswa[$y]) == $_kunci[$y]){
                        $array_add_row[$index_siswa][$y+3] = 1;
                        $hitung_benar[$y] += 1;
                        
                    } else {
                        $array_add_row[$index_siswa][$y+3] = 0;
                        $hitung_benar[$y] += 0;
                    }
                }

                $array_add_row[$index_siswa][count($id_soal)+3] = $row->jml_benar;
                $array_add_row[$index_siswa][count($id_soal)+4] = $row->jml_salah;
                $array_add_row[$index_siswa][count($id_soal)+5] = $row->nilai;

                $index_siswa++;
            }

        } else {
            redirect('analisa');
        }
        return $array_add_row;
    }

    public function excerpt($soal,$limit){
        $excerpt = explode(' ', $soal, $limit);
        if (count($excerpt)>=$limit) {
            array_pop($excerpt);
            $excerpt = implode(" ",$excerpt).'…';
        }else{
            $excerpt = implode(" ",$excerpt);
        }
        $excerpt = preg_replace('`[[^]]*]`','',$excerpt);

        return $excerpt;
    }

    public function get_data_jawaban($id_ujian)
    {
        return $this->db->where('id_ujian', $id_ujian)
                        ->order_by('nama_siswa','ASC')
                        ->order_by('id_kelas','ASC')
                        ->get('tb_jawaban')
                        ->result();
    }

    public function get_data_jawaban_by_kelas($id_ujian,$kelas)
    {
        return $this->db->where('id_ujian', $id_ujian)
                        ->where('id_kelas', $kelas)
                        ->order_by('nama_siswa','ASC')
                        ->order_by('id_kelas','ASC')
                        ->get('tb_jawaban')
                        ->result();
    }

    public function get_list_soal($id_ujian)
    {
        $query= $this->db->where('id_ujian', $id_ujian)->get('ujian');
        $row=$query->row();
        return $row->list_soal;
    }

    //    hitung jumlah total data
    function total_record() {
        $this->db->from($this->db_tabel);
        $this->db->where('id_mp', $this->id_mp);
        return $this->db->count_all_results();
    }
 
    //    tampilkan dengan limit
    function ujian_limit($limit, $start = 0) {

        $this->db->order_by('id_ujian', 'DESC');
        $this->db->where('id_mp', $this->id_mp);
        $this->db->limit($limit, $start);

        return $this->db->get($this->db_tabel);
    }
 
    function pilih_soal_limit($limit, $start = 0) {

        $this->db->order_by('soal.id_soal', 'DESC');
        $this->db->where('soal.id_mp', $this->id_mp);
        $this->db->join('indikator', 'indikator.id_indikator = soal.id_indikator');
        $this->db->join('kd', 'kd.id_kd = indikator.id_kd');
        $this->db->limit($limit, $start);

        return $this->db->get($this->db_tabel_soal);
    }

    public function reset_ujian($id_jawaban)
    {	
    	$this->db->where('id', $id_jawaban)
    			 ->delete('tb_jawaban');

    	if($this->db->affected_rows() > 0)
    	{
    		return TRUE;
    	} else {
    		return FALSE;
    	}
    }
}
/* End of file ujian_model.php */
/* Location: ./application/models/ujian_model.php */