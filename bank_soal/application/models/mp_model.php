<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MP_model extends CI_Model {

    public $db_tabel = 'mp';

    public function __construct()
    {
        parent::__construct();
    }

    public function load_form_rules_tambah()
    {
        $form_rules = array(
            array(
                'field' => 'id_mp',
                'label' => 'Kode MP'
            ),
            array(
                'field' => 'mp',
                'label' => 'Mata Pelajaran',
                'rules' => "required|max_length[32]|is_unique[$this->db_tabel.mp]"
            ),
        );
        return $form_rules;
    }

    public function load_form_rules_edit()
    {
        $form_rules = array(
            array(
                'field' => 'id_mp',
                'label' => 'Kode MP'
            ),
            array(
                'field' => 'mp',
                'label' => 'Mata Pelajaran',
                'rules' => "required|max_length[32]|callback_is_mp_exist"
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

    public function cari_semua()
    {
        return $this->db->order_by('id_mp', 'ASC')
            ->get($this->db_tabel)
            ->result();
    }

    public function cari($id_mp)
    {
        return $this->db->where('id_mp', $id_mp)
            ->limit(1)
            ->get($this->db_tabel)
            ->row();
    }

    public function buat_tabel($data)
    {
        $this->load->library('table');

        // buat class zebra di <tr>,untuk warna selang-seling
        $tmpl = array(
                'table_open'     => '<table border="0" cellpadding="4" cellspacing="0" class="datatable table table-striped table-bordered">'
                );
        $this->table->set_template($tmpl);

        /// heading tabel
        $this->table->set_heading('No', 'Kode MP', 'Mata Pelajaran', 'Aksi');

        $no = 0;
        foreach ($data as $row)
        {
            $this->table->add_row(
                ++$no,
                $row->id_mp,
                $row->mp,
                anchor('mp/edit/'.$row->id_mp,'<i class="glyphicon glyphicon-pencil"></i> Edit',array('class' => 'btn btn-info')).' '.
                anchor('mp/hapus/'.$row->id_mp,'<i class="glyphicon glyphicon-trash"></i> Hapus',array('class'=> 'btn btn-danger','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
            );
        }
        $tabel = $this->table->generate();

        return $tabel;
    }

    public function tambah()
    {
        $mp = array(
            'id_mp' => 'null',
            'mp' => $this->input->post('mp')
        );
        $this->db->insert($this->db_tabel, $mp);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function edit($id_mp)
    {
        $mp = array(
            //'id_mp'=>$this->input->post('id_mp'),
            'mp'=>$this->input->post('mp'),
        );

        // update db
        $this->db->where('id_mp', $id_mp);
        $this->db->update($this->db_tabel, $mp);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function hapus($id_mp)
    {
        $this->db->where('id_mp', $id_mp)->delete($this->db_tabel);

        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

}
/* End of file mp_model.php */
/* Location: ./application/models/mp_model.php */