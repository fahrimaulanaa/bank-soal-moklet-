<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Entry_model extends CI_Model {

    public $db_tabel = 'soal';
    public $id_mp = 0;
   // public $pass_soal=0;


    public function __construct()
    {
        parent::__construct();
        $this->id_mp=$this->session->userdata('id_mp_sekarang');
    }

    public function load_form_rules_tambah()
    {
        $form_rules = array(
            array(
                'field' => 'id_soal',
                'label' => 'Kode soal'
            ),
            array(
                'field' => 'soal',
                'label' => 'Soal',
                'rules' => "required|is_unique[$this->db_tabel.soal]"
            ),
        );
        return $form_rules;
    }

    public function load_form_rules_edit()
    {
        $form_rules = array(
            array(
                'field' => 'id_soal',
                'label' => 'Kode Soal'
            ),
            array(
                'field' => 'soal',
                'label' => 'Soal',
                'rules' => "required|callback_is_entry_exist"
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
        return $this->db->order_by('id_soal', 'ASC')
            ->where('id_mp', $this->id_mp)
            ->get($this->db_tabel)
            ->result();
    }

    public function cari($id_soal)
    {
        return $this->db->where('id_soal', $id_soal)
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

    public function buat_tabel($data)
    {//echo $this->session->userdata('id_mp_sekarang');
        $this->load->library('table');

        $tmpl = array(
                'table_open'     => '<table border="0" cellpadding="4" cellspacing="0" class="datatable table table-striped table-bordered">'
                );
        $this->table->set_template($tmpl);

        /// heading tabel
        $this->table->set_heading('No', 'ID Soal', 'ID indk','Soal','Jwb', 'Kesulitan','Gambar','Suara','Aksi');

        $no = 0;
        foreach ($data as $row)
        {
            if($row->gambar != NULL){
                $gambar = '<a class="tooltip"><i class="glyphicon glyphicon-search"></i> Lihat 
                               <span class="tooltiptext">
                                <img src="'.base_url().'uploads/'.$row->id_soal.'.jpg" style="width: 400px;">
                                </span>
                           </a>';
                $soal = $this->excerpt_soal($row->soal,4).'
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
                $soal = $this->excerpt_soal($row->soal,4).'
                            <a class="tooltip">(preview soal) 
                               <span class="tooltiptext">
                                   <textarea disabled>'.$row->soal.'</textarea>
                                </span>
                           </a>';
            }

            if($row->soal != NULL){
                
            } else {
                $soal = '-';
            }

            if($row->suara != NULL){
                $suara = '<a class="tooltip"><i class="glyphicon glyphicon-play"></i> Play 
                               <span class="tooltiptext">
                                   <audio controls>
                                        <source src="'.base_url().'uploads/'.$row->id_soal.'.mp3" type="audio/mpeg">
                                   </audio>
                                </span>
                           </a>';
            } else {
                $suara = '-';
            }

            $this->table->add_row(
                ++$no,
                $row->id_soal,
                $row->id_indikator,
                $soal,
                strtoupper($row->jawaban),
                $row->sulit,
                $gambar,
                $suara,
                '<div class="btn-group">'.
                anchor('entry/edit/'.$row->id_soal,'<i class="glyphicon glyphicon-pencil"></i> Edit',array('class' => 'btn btn-sm btn-info')).' '.
                anchor('entry/gambar/'.$row->id_soal,'<i class="glyphicon glyphicon-picture"></i> Gambar',array('class' => 'btn btn-sm btn-default')).' '.
                anchor('entry/lagu/'.$row->id_soal,'<i class="glyphicon glyphicon-music"></i> Suara',array('class' => 'btn btn-sm btn-default')).
                anchor('entry/hapus/'.$row->id_soal,'<i class="glyphicon glyphicon-trash"></i> Hapus',array('class'=> 'btn btn-sm btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')")).' '.
                '</div>'
            );
        }
        $tabel = $this->table->generate();

        return $tabel;
    }

    public function get_soal($id_indikator)
    {
        $data = "";
        $soal = '';
        $sql = 'SELECT * FROM soal WHERE id_indikator='.$id_indikator;
        $a = $this->db->query($sql);

        if(!empty($a)){
            $gambar = '';
            $suara = '';
            $i = 1;
            foreach($a->result_array() as $row1){
                foreach($a->result_array() as $row){
                    $data['id_soal']=$row1['id_soal'];
                    $data['soal']=$row1['soal'];
                    $data['gambar']=$row1['gambar'];
                    $data['suara']=$row1['suara'];
                }
                $soal[$row1['id_soal']] = $data;
                $i++;

            }
            return $soal;
        } else {
            return $soal;
        }
        
    }

    public function get_soal_by_id($id_soal)
    {
        $data = "";
        $soal = '';
        $sql = 'SELECT * FROM soal WHERE id_soal='.$id_soal;
        $a = $this->db->query($sql);

        if(!empty($a)){
            foreach($a->result_array() as $row){
                $data['id_soal']=$row['id_soal'];
                $data['soal']=$row['soal'];
                if($row['gambar'] != null){
                    $data['gambar']= '<i class="glyphicon glyphicon-search"></i> Lihat';
                } else {
                    $data['gambar']='-';
                }
                if($row['suara'] != null){
                    $data['suara']= '<i class="glyphicon glyphicon-play"></i> Mainkan';
                } else {
                    $data['suara']='-';
                }
                $data['level']=$row['sulit'];
            }
            //$soal[$row1['id_soal']] = $data;

            return $data;
        } else {
            return $data;
        }
        
    }

    public function get_gambar_suara($id_indikator)
    {
        $data = "";
        $sql = 'SELECT id_soal, gambar, suara FROM soal WHERE id_indikator='.$id_indikator;
        $a = $this->db->query($sql);

        if(!empty($a)){
            $gambar = '';
            $suara = '';
            foreach($a->result_array() as $row){
                if($row['gambar'] != NULL){
                    $gambar = $row['id_soal'];
                } else {
                    $gambar = 'tidak ada gambar';
                }

                if($row['suara'] != NULL){
                    $suara = $row['id_soal'];
                } else {
                    $suara = 'tidak ada suara';
                }

                $data[$row['id_soal']] = $gambar;
                //$data['suara'.$row['id_soal']] = $suara;
            }
            return $data;
        } else {
            return $data;
        }
        
    }

    public function buf($id_soal=null)
    {
        $buf = array(
            'buf' => $id_soal
        );
        //$this->db->insert('buf', $buf);
        //$this->db->where('id_soal', $id_soal);
        $this->db->update('buf', $buf);
        //echo 'buf';
        //echo $id_soal;
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

    public function edit_gambar($id_soal=null)
    {
        $gambar = array(
            'gambar' => 'ada'
        );
        //$this->db->insert('buf', $buf);
        $this->db->where('id_soal', $id_soal);
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

    public function edit_lagu($id_soal=null)
    {
        $suara = array(
            'suara' => 'ada'
        );
        //$this->db->insert('buf', $buf);
        $this->db->where('id_soal', $id_soal);
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
        $soal = array(
            'id_soal' => 'null',
            'id_mp' => $this->id_mp,
            'sulit' => $this->input->post('sulit')+1,
            'jawaban' => $this->input->post('jawaban')+1,
            'id_indikator' => $this->input->post('id_indikator'),
            'soal' => $this->input->post('soal')
        );
        $this->db->insert($this->db_tabel, $soal);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function edit($id_soal)
    {
        $soal = array(
            //'id_soal'=>$this->input->post('id_soal'),
            'id_indikator' => $this->input->post('id_indikator'),
            'sulit' => $this->input->post('sulit')+1,
            'jawaban' => $this->input->post('jawaban')+1,
            'soal'=>$this->input->post('soal'),
        );

        // update db
        $this->db->where('id_soal', $id_soal);
        $this->db->update($this->db_tabel, $soal);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function hapus($id_soal)
    {
        $this->db->where('id_soal', $id_soal)->delete($this->db_tabel);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function excerpt_soal($soal,$limit){
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

}
/* End of file entry_model.php */
/* Location: ./application/models/entry_model.php */