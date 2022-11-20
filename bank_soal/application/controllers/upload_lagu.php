<?php

class Upload_lagu extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('entry_model', 'entry', TRUE);
    }

    public function index()
    {
        if ($this->session->userdata('login') == TRUE){
            $this->data['title']  = 'Upload Suara Soal';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Entry</a></li>
                                            <li><a href="#">ID Soal '.$this->entry->nilai_buf().'</a></li>
                                            <li><a href="#">Upload Suara</a></li>
                                          </ul>';
            $this->data['main_view']   = 'upload_lagu_form';
            $this->data['form_action'] = 'upload_lagu/do_upload';

            $this->data['error']   = '';
            $this->load->view('template', $this->data);
        } else {
            redirect('login');
        }
    }


    public function do_upload()
    {
        $this->data['title']  = 'Upload Suara Soal';
        $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                        <li><a href="#">Depan</a></li>
                                        <li><a href="#">Entry</a></li>
                                        <li><a href="#">ID Soal '.$this->entry->nilai_buf().'</a></li>
                                        <li><a href="#">Upload Suara</a></li>
                                      </ul>';
        $this->data['main_view']   = 'upload_lagu_form';
        $this->data['form_action'] = 'upload_lagu/do_upload';

        $config['file_name'] = $this->session->userdata('id_soal_sekarang').'.mp3';
        $config['overwrite'] = TRUE;
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'mp3';
        $config['max_size']             = 5000;


        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
            $this->data['error'] = $this->upload->display_errors();
            $this->load->view('template', $this->data);

        }
        else
        {
            if ($this->entry->edit_lagu($this->session->userdata('id_soal_sekarang')))
            {
                $this->session->set_flashdata('pesan', 'Proses upload suara berhasil.');
                redirect('entry');
            }

        }
    }
}
?>