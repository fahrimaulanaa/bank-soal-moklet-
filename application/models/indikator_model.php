<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Indikator_model extends CI_Model {

    public $db_tabel = 'indikator';
    public $id_mp = 0;


    public function __construct()
    {
        parent::__construct();
        $this->id_mp=$this->session->userdata('id_mp_sekarang');
    }

    public function load_form_rules_tambah()
    {
        $form_rules = array(
            array(
                'field' => 'id_indikator',
                'label' => 'Kode indikator'
            ),
            array(
                'field' => 'indikator',
                'label' => 'Indikator',
                'rules' => "required|max_length[255]|is_unique[$this->db_tabel.indikator]"
            ),
        );
        return $form_rules;
    }

    public function load_form_rules_edit()
    {
        $form_rules = array(
            array(
                'field' => 'id_indikator',
                'label' => 'Kode indikator'
            ),
            array(
                'field' => 'indikator',
                'label' => 'Indikator',
                'rules' => "required|max_length[255]|callback_is_indikator_exist"
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

    public function cari_id_indikator($id_soal)
    {
        $query= $this->db->where('id_soal', $id_soal)->get('soal');
        $row=$query->row();
        return $row->id_indikator;
    }

    public function cari_indikator($id_kd)
    {
        return $this->db->order_by('id_indikator', 'ASC')
            ->where('id_kd', $id_kd)
            ->get($this->db_tabel)
            ->result();
    }

    public function cari_semua()
    {
        return $this->db->order_by('indikator.id_indikator', 'ASC')
            ->join('kd', 'kd.id_kd = indikator.id_kd')
            ->where('indikator.id_mp', $this->id_mp)
            ->get($this->db_tabel)
            ->result();
    }

    public function cari($id_indikator)
    {
        return $this->db->where('id_indikator', $id_indikator)
            ->where('id_mp', $this->id_mp)
            ->limit(1)
            ->get($this->db_tabel)
            ->row();
    }

    public function buat_tabel($data)
    {//echo $this->session->userdata('id_mp_sekarang');
        $this->load->library('table');

        // buat class zebra di <tr>,untuk warna selang-seling
        $tmpl = array(
                'table_open'     => '<table border="0" cellpadding="4" cellspacing="0" class="datatable table table-striped table-bordered">'
                );
        $this->table->set_template($tmpl);

        /// heading tabel
        $this->table->set_heading('No', 'ID Indk', 'ID KD','Indikator', 'Aksi');

        $no = 0;
        foreach ($data as $row)
        {
            $this->table->add_row(
                ++$no,
                $row->id_indikator,
                $row->id_kd,
                $this->excerpt($row->indikator,7),
                anchor('indikator/edit/'.$row->id_indikator,'<i class="glyphicon glyphicon-pencil"></i> Edit',array('class' => 'btn btn-sm btn-info')).' '.
                anchor('indikator/hapus/'.$row->id_indikator,'<i class="glyphicon glyphicon-trash"></i> Hapus',array('class'=> 'btn btn-sm btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
            );
        }
        $tabel = $this->table->generate();

        return $tabel;
    }

    public function tambah()
    {
        $indikator = array(
            'id_indikator' => 'null',
            'id_mp' => $this->id_mp,
            'id_kd' => $this->input->post('id_kd'),
            'indikator' => $this->input->post('indikator')
        );
        $this->db->insert($this->db_tabel, $indikator);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function edit($id_indikator)
    {
        $indikator = array(
            //'id_indikator'=>$this->input->post('id_indikator'),
            'id_kd' => $this->input->post('id_kd'),
            'indikator'=>$this->input->post('indikator'),
        );

        // update db
        $this->db->where('id_indikator', $id_indikator);
        $this->db->update($this->db_tabel, $indikator);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function hapus($id_indikator)
    {
        $this->db->where('id_indikator', $id_indikator)->delete($this->db_tabel);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function excerpt($indikator,$limit){
        $excerpt = explode(' ', $indikator, $limit);
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
/* End of file indikator_model.php */
/* Location: ./application/models/indikator_model.php */