<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public $data = array(
        'modul'         => 'Dashboard',
        'title'         => 'Dashboard',
        'main_view'     => 'entry',
    );

    public function index()
    {
        //session
        if ($this->session->userdata('login') == TRUE){
        
            $data['sess_user'] = $this->session->userdata('username');
            $data['sess_mp'] = $this->session->userdata('mapel');
            
            $data['main_view']         = "dashboard_view";
            
            $this->load->view('template', $data);
        }
        else
        {
            redirect('login');
        }

    }

}
/* End of file entry.php */
/* Location: ./application/controllers/entry.php */