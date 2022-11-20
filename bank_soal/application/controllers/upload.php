<?php

class Upload extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('entry_model', 'entry', TRUE);
    }

    public function index()
    {
        if ($this->session->userdata('login') == TRUE){
            
            $this->data['title']  = 'Upload Gambar Soal';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Entry</a></li>
                                            <li><a href="#">ID Soal '.$this->entry->nilai_buf().'</a></li>
                                            <li><a href="#">Upload Gambar</a></li>
                                          </ul>';
            $this->data['main_view']   = 'upload_form';
            $this->data['form_action'] = 'upload/do_upload';

            $this->data['error']   = '';
            $this->load->view('template', $this->data);
        } else {
            redirect('login');
        }
    }


    public function do_upload()
    {
        $this->data['title']  = 'Upload Gambar Soal';
        $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                        <li><a href="#">Depan</a></li>
                                        <li><a href="#">Entry</a></li>
                                        <li><a href="#">ID Soal '.$this->entry->nilai_buf().'</a></li>
                                        <li><a href="#">Upload Gambar</a></li>
                                      </ul>';
        $this->data['main_view']   = 'upload_form';
        $this->data['form_action'] = 'upload/do_upload';

        $config['file_name'] = $this->session->userdata('id_soal_sekarang').'.jpg';
        $config['overwrite'] = TRUE;
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'jpg';
        $config['max_size']             = 5000;
        $config['max_width']            = 4000;
        $config['max_height']           = 3000;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
            $this->data['error'] = $this->upload->display_errors();
            $this->load->view('template', $this->data);
        }
        else
        {
            if ($this->entry->edit_gambar($this->session->userdata('id_soal_sekarang')))
            {
                $this->session->set_flashdata('pesan', 'Proses upload gambar berhasil.');
                redirect('entry');
            }
        }
    }
}
?>