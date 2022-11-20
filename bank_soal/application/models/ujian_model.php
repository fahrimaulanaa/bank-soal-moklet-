<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ujian_model extends CI_Model {

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

    public function load_form_rules_tambah()
    {
        $form_rules = array(
            array(
                'field' => 'id_ujian',
                'label' => 'Kode ujian'
            ),
            array(
                'field' => 'ujian',
                'label' => 'Nama Ujian',
                'rules' => "required|max_length[255]|is_unique[$this->db_tabel.ujian]"
            ),
            array(
                'field' => 'jumlah_soal',
                'label' => 'Jumlah soal',
                'rules' => "required|numeric|strip_tags"
            ),
        );
        return $form_rules;
    }

    public function load_form_rules_edit()
    {
        $form_rules = array(
            array(
                'field' => 'id_ujian',
                'label' => 'Kode ujian'
            ),
            array(
                'field' => 'ujian',
                'label' => 'Nama Ujian',
                'rules' => "required|max_length[255]"
            ),
            array(
                'field' => 'jumlah_soal',
                'label' => 'Jumlah soal',
                'rules' => "required|numeric|strip_tags"
            ),
        );
        return $form_rules;
    }

    public function validasi_tambah()
    {
        $form = $this->load_form_rules_tambah();
        $this->form_validation->set_rules($form);

        if ($this->form_validation->run())
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function validasi_edit()
    {
        $form = $this->load_form_rules_edit();
        $this->form_validation->set_rules($form);

        if ($this->form_validation->run())
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function validasi_pilih_soal($jumlah_soal)
    {
        $jml_soal = count($this->input->post('id_soal_terpilih'));
        if ($jml_soal < $jumlah_soal || $jml_soal > $jumlah_soal)
        {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function cari_mp()
    {
        $query= $this->db->where('id_mp', $this->id_mp)
            ->get('mp');
        $row=$query->row();
        return $row->mp;
    }

    public function nilai_buf()
    {
        $query= $this->db->get('buf');
        $row=$query->row();
        return $row->buf;
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

    public function buat_tabel($data,$start)
    {//echo $this->session->userdata('id_mp_sekarang');
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
            if($row->list_soal != ""){
                $btn_soal = anchor('ujian/pilih_soal/'.$row->id_ujian,'<i class="glyphicon glyphicon-list"></i> Ubah Soal',array('class' => 'btn btn-success btn-sm')).' ';
                //$btn_lihat = anchor('ujian/lihat_soal/'.$row->id_ujian,'<i class="glyphicon glyphicon-eye"></i> Lihat Soal',array('class' => 'btn btn-primary btn-sm', 'target' => '_blank')).' ';
            } else {
                $btn_soal = anchor('ujian/pilih_soal/'.$row->id_ujian,'<i class="glyphicon glyphicon-list"></i> Pilih Soal &nbsp;',array('class' => 'btn btn-warning btn-sm')).' ';
                //$btn_lihat = '';
            }

            $this->table->add_row(
                ++$no,
                $row->id_ujian,
                $row->ujian,
                anchor('ujian/edit/'.$row->id_ujian,'<i class="glyphicon glyphicon-pencil"></i> Edit',array('class' => 'btn btn-info btn-sm')).' '.
                $btn_soal.
                //$btn_lihat.
                //anchor('ujian/pilih_soal/'.$row->id_ujian,'Pilih Soal',array('class' => 'edit')).' '.
                anchor('ujian/hapus/'.$row->id_ujian,'<i class="glyphicon glyphicon-trash"></i> Hapus',array('class'=> 'btn btn-danger btn-sm','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
            );
        }
        $tabel = $this->table->generate();

        return $tabel;
    }

    public function buat_tabel_pilih_soal($data)
    {
        //mengambil data soal yang tersimpan
        $soal_terpilih = $this->ujian->get_list_soal($this->uri->segment(3));
        $array_list_soal = explode(',', $soal_terpilih); //explode id soal
        if(!empty($soal_terpilih)){
            for($x=0;$x<count($array_list_soal);$x++){
                $list_id_soal = end(explode(':', $array_list_soal[$x]));
                $id_soal[$x] = $list_id_soal;

            }
        }

        $this->load->library('table');

        // buat class zebra di <tr>,untuk warna selang-seling
        $tmpl = array(
                'table_open'     => '<table border="0" cellpadding="4" cellspacing="0" class="datatable table table-striped table-bordered">'
                );
        $this->table->set_template($tmpl);

        /// heading tabel
        $this->table->set_heading('No','KD','Indikator','ID Soal','Soal','Jwb', 'Kesulitan','Gambar','Suara','Pilih');

        $no = 0;
        $index = 0;
        $checkbox = "";
        foreach ($data as $row)
        {
            if($row->gambar != NULL){
                $gambar = '<a class="tooltip"><i class="glyphicon glyphicon-search"></i> Lihat 
                               <span class="tooltiptext media">
                                <img src="'.base_url().'uploads/'.$row->id_soal.'.jpg" style="width: 400px;">
                                </span>
                           </a>';
                $soal = $this->excerpt($row->soal,5).'
                            <a class="tooltip">(preview soal) 
                               <span class="tooltiptext">
                                   <textarea disabled>|------------------|
|  Gambar Soal  |
|------------------|

'.$row->soal.'</textarea>
                                </span>
                           </a>';
            } else {
                $gambar = '-';
                $soal = $this->excerpt($row->soal,5).'
                            <a class="tooltip">(preview soal) 
                               <span class="tooltiptext">
                                   <textarea disabled>'.$row->soal.'</textarea>
                                </span>
                           </a>';
            }

            if($row->kd != NULL || $row->indikator != NULL){
                $kd = $this->excerpt($row->kd,4).'
                            <a class="tooltip">(preview KD) 
                               <span class="tooltiptext">
                                   <textarea style="height:50px;" disabled>'.$row->kd.'</textarea>
                                </span>
                           </a>';
                $indikator = $this->excerpt($row->indikator,4).'
                            <a class="tooltip">(preview indikator) 
                               <span class="tooltiptext">
                                   <textarea style="height:50px;" disabled>'.$row->indikator.'</textarea>
                                </span>
                           </a>';
            } else {
                $kd = '-';
                $indikator = '-';
            }

            if($row->suara != NULL){
                $suara = '<a class="tooltip"><i class="glyphicon glyphicon-play"></i> Play 
                               <span class="tooltiptext media">
                                   <audio controls>
                                        <source src="'.base_url().'uploads/'.$row->id_soal.'.mp3" type="audio/mpeg">
                                   </audio>
                                </span>
                           </a>';
            } else {
                $suara = '-';
            }
            
            if(!empty($this->input->post('id_soal_terpilih'))){ //prefill soal tercentang saat gagal simpan
                for($a=0;$a<count($this->input->post('id_soal_terpilih'));$a++) {
                    if($this->input->post('id_soal_terpilih')[$a] == $row->id_soal){
                        $checkbox = '<input type="checkbox" name="id_soal_terpilih[]" value="'.$this->input->post('id_soal_terpilih')[$a].'" checked="checked" />';
                        break;
                    } else {
                        $checkbox = '<input type="checkbox" name="id_soal_terpilih[]" value="'.$row->id_soal.'" />';
                    }
                }
            } else if(!empty($id_soal)) { //menampilkan soal terpilih saat diedit
                for($a=0;$a<count($id_soal);$a++) {
                    if($id_soal[$a] == $row->id_soal){
                        $checkbox = '<input type="checkbox" name="id_soal_terpilih[]" value="'.$id_soal[$a].'" checked="checked" />';
                        break;
                    } else {
                        $checkbox = '<input type="checkbox" name="id_soal_terpilih[]" value="'.$row->id_soal.'" />';
                    }
                }
            } else { //belum ada data soal yang dipilih
                $checkbox = '<input type="checkbox" name="id_soal_terpilih[]" value="'.$row->id_soal.'" />';
            }

            $this->table->add_row(
                ++$no,
                $kd,
                $indikator,
                $row->id_soal,
                $soal,
                strtoupper($row->jawaban),
                $row->sulit,
                $gambar,
                $suara,
                $checkbox
            );
            $index++;
        }

        $tabel = $this->table->generate();

        return $tabel;
    }

    public function simpan_soal_ujian($id_ujian,$jumlah_soal)
    {
            
        $update_    = "";
        $_no = 0;
        for ($i = 0; $i < $jumlah_soal; $i++) {
            if(trim($this->input->post('id_soal_terpilih')[$i] != '')){
                ++$_no;

                if(!empty($this->input->post('id_soal_terpilih')[$i])){
                    $update_ .= $_no.":".$this->input->post('id_soal_terpilih')[$i].",";
                } else {
                    $update_ .= $_no.":,";
                }
            }
        }
        $update_ = substr($update_, 0, -1);

        $ujian = array('list_soal' => $update_);

        // update db
        $this->db->where('id_ujian', $id_ujian);
        $this->db->update($this->db_tabel, $ujian);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function buf($id_ujian=null)
    {
        $buf = array(
            'buf' => $id_ujian
        );
        //$this->db->insert('buf', $buf);
        //$this->db->where('id_ujian', $id_ujian);
        $this->db->update('buf', $buf);
        //echo 'buf';
        //echo $id_ujian;
        //echo  $this->db;

        if($this->db->affected_rows() > 0)
        {
            return TRUE;

        }
        else
        {
            return TRUE;

        }
    }

    public function edit_gambar($id_ujian=null)
    {
        $gambar = array(
            'gambar' => 'ada'
        );
        //$this->db->insert('buf', $buf);
        $this->db->where('id_ujian', $id_ujian);
        $this->db->update('soal', $gambar);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return TRUE;
        }
    }

    public function edit_lagu($id_ujian=null)
    {
        $suara = array(
            'suara' => 'ada'
        );
        //$this->db->insert('buf', $buf);
        $this->db->where('id_ujian', $id_ujian);
        $this->db->update('soal', $suara);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return TRUE;
        }
    }

    public function tambah()
    {
        //membuat nomor soal
        /*$list_soal = "";
        for($i=1;$i<=$this->input->post('jumlah_soal');$i++) {
            $list_soal .= $i.":,";
        }*/
        //menghilangkan comma di akhir
        $list_soal = substr($list_soal, 0, -1);

        $ujian = array(
            'id_ujian' => 'null',
            'id_mp' => $this->id_mp,
            'jumlah_soal' => $this->input->post('jumlah_soal'),
            'ujian' => $this->input->post('ujian')
        );
        $this->db->insert($this->db_tabel, $ujian);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function edit($id_ujian)
    {
        $ujian = array(
            //'id_ujian'=>$this->input->post('id_ujian'),
            //'id_kd' => $this->input->post('id_kd'),
            'ujian'=>$this->input->post('ujian'),
            'jumlah_soal'=>$this->input->post('jumlah_soal'),
        );

        // update db
        $this->db->where('id_ujian', $id_ujian);
        $this->db->update($this->db_tabel, $ujian);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function hapus($id_ujian)
    {
        $this->db->where('id_ujian', $id_ujian)->delete($this->db_tabel);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
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
}
/* End of file ujian_model.php */
/* Location: ./application/models/ujian_model.php */