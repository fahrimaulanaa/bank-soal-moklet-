<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {

    public $db_tabel = 'pengguna';

    public function load_form_rules()
    {
        $form_rules = array(
                            array(
                                'field' => 'username',
                                'label' => 'Username',
                                'rules' => 'required'
                            ),
                            array(
                                'field' => 'password',
                                'label' => 'Password',
                                'rules' => 'required'
                            ),
        );
        return $form_rules;
    }

    public function validasi()
    {
        $form = $this->load_form_rules();
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

    // cek status user, login atau tidak?
    public function cek_user()
    {
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));

        $query = $this->db->where('username', $username)
                          ->where('password', $password)
                          ->join('mp', 'pengguna.id_mp = mp.id_mp')
                          ->limit(1)
                          ->get($this->db_tabel);

       $row= $query->row();
						  //echo $this->input->post('username');
						  //echo $this->input->post('password');
						  //echo  $query->num_rows();

        if ($query->num_rows() == 1)
        {
            $data = array('username' => $username, 
                          'login'    => TRUE,
                          'mapel'    => $row->mp,
                          'nama_user'=> $row->nama);
            // buat data session jika login benar
            $this->session->set_userdata($data);

            $this->session->set_userdata('id_mp_sekarang', $row->id_mp);
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function logout()
    {
        $this->session->unset_userdata(array('username' => '', 'login' => FALSE));
        $this->session->sess_destroy();
    }
}
/* End of file login_model.php */
/* Location: ./application/models/login_model.php */