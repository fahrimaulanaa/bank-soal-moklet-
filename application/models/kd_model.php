<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class KD_model extends CI_Model {

    public $db_tabel = 'kd';
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
                'field' => 'id_kd',
                'label' => 'Kode kd'
            ),
            array(
                'field' => 'kd',
                'label' => 'Kompetensi Dasar',
                'rules' => "required|is_unique[$this->db_tabel.kd]"
            ),
        );
        return $form_rules;
    }

    public function load_form_rules_edit()
    {
        $form_rules = array(
            array(
                'field' => 'id_kd',
                'label' => 'Kode kd'
            ),
            array(
                'field' => 'kd',
                'label' => 'Kompetensi Dasar',
                'rules' => "required|callback_is_kd_exist"
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

    public function cari_id_kd($id_indikator)
    {
        $query= $this->db->where('id_indikator', $id_indikator)->get('indikator');
        $row=$query->row();
        return $row->id_kd;
    }

    public function cari_semua()
    {
        return $this->db->order_by('id_kd', 'ASC')
            ->where('id_mp', $this->id_mp)
            ->get($this->db_tabel)
            ->result();
    }

    public function cari($id_kd)
    {
        return $this->db->where('id_kd', $id_kd)
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
        $this->table->set_heading('No', 'Id KD', 'Kompetensi Dasar', 'Aksi');

        $no = 0;
        foreach ($data as $row)
        {
            $this->table->add_row(
                ++$no,
                $row->id_kd,
                $this->excerpt($row->kd,7),
                anchor('kd/edit/'.$row->id_kd,'<i class="glyphicon glyphicon-pencil"></i> Edit',array('class' => 'btn btn-info btn-sm')).' '.
                anchor('kd/hapus/'.$row->id_kd,'<i class="glyphicon glyphicon-trash"></i> Hapus',array('class'=> 'btn btn-danger btn-sm','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
            );
        }
        $tabel = $this->table->generate();

        return $tabel;
    }

    public function tambah()
    {
        $kd = array(
            'id_kd' => 'null',
            'id_mp' => $this->id_mp,
            'kd' => $this->input->post('kd')
        );
        $this->db->insert($this->db_tabel, $kd);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function edit($id_kd)
    {
        $kd = array(
            //'id_kd'=>$this->input->post('id_kd'),
            //'id_mp' => $this->id_mp,
            'kd'=>$this->input->post('kd'),
        );

        // update db
        $this->db->where('id_kd', $id_kd);
        $this->db->update($this->db_tabel, $kd);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function hapus($id_kd)
    {
        $this->db->where('id_kd', $id_kd)->delete($this->db_tabel);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function excerpt($kd,$limit){
        $excerpt = explode(' ', $kd, $limit);
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
/* End of file kd_model.php */
/* Location: ./application/models/kd_model.php */