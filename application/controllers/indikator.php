<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Indikator extends CI_Controller {


    public $data = array(
        'modul'         => 'indikator',
        'title'         => 'Indikator',
        'breadcrumb'    => '<ul class="breadcrumb">
                                <li><a href="#">Depan</a></li>
                                <li><a href="#">Indikator</a></li>
                              </ul>',
        'pesan'         => '',
        'pagination'    => '',
        'tabel_data'    => '',
        'main_view'     => 'indikator',
        'form_action'   => '',
        'form_value'    => '',
        'option_kd'  => '',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('indikator_model', 'indikator', TRUE);
        $this->load->model('kd_model', 'kd', TRUE);
    }

    public function index()
    {
        if ($this->session->userdata('login') == TRUE){
            // hapus data teindikatororary proses update
            $this->session->unset_userdata('id_indikator_sekarang', '');
            $this->session->unset_userdata('indikator_sekarang', '');

            //sementara nanti diubah

            //echo $this->session->userdata('id_mp_sekarang');
            //$this->id_mp = $this->session->userdata('id_mp_sekarang');
            //echo $this->indikator->cari_mp();
            $sem=$this->indikator->cari_mp();
            $this->data['title']  = 'Indikator '.$sem;
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Indikator</a></li>
                                            <li><a href="#">'.$sem.'</a></li>
                                          </ul>';
            // Cari semua data indikator
            $indikator = $this->indikator->cari_semua();

            // data indikator ada, taindikatorilkan
            if ($indikator)
            {
                // buat tabel
                $tabel = $this->indikator->buat_tabel($indikator);
                $this->data['tabel_data'] = $tabel;
                $this->load->view('template', $this->data);

            }
            // data indikator tidak ada
            else
            {
                $this->data['pesan'] = 'Tidak ada data indikator.';
                $this->load->view('template', $this->data);
            }
        } else {
            redirect('login');
        }
    }

    public function tambah()
    {
        if ($this->session->userdata('login') == TRUE){
            $this->data['title']  = 'Tambah Indikator';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Indikator</a></li>
                                            <li><a href="#">Tambah</a></li>
                                          </ul>';
            $this->data['main_view']   = 'indikator_view';
            $this->data['form_action'] = 'indikator/tambah';

            // option kelas, untuk menu dropdown
            $kd = $this->kd->cari_semua();

            // data kelas ada
            if($kd)
            {
                foreach($kd as $row)
                {
                    $this->data['option_kd'][$row->id_kd] = $row->kd;
                }
            }
            // data kelas tidak ada
            else
            {
                $this->data['option_kd']['00'] = '-';
                $this->data['pesan'] = 'Data KD tidak tersedia. Silahkan isi dahulu data kelas.';
                //$this->load->view('template', $this->data);
            }
            // submit
            if($this->input->post('submit'))
            {
                // validasi sukses
                if($this->indikator->validasi_tambah())
                {
                    if($this->indikator->tambah())
                    {
                        $this->session->set_flashdata('pesan', 'Proses tambah data berhasil.');
                        redirect('indikator');
                    }
                    else
                    {
                        $this->data['pesan'] = 'Proses tambah data gagal.';
                        $this->load->view('template', $this->data);
                    }
                }
                // validasi gagal
                else
                {
                    $this->load->view('template', $this->data);
                }
            }
            // no submit
            else
            {
                $this->load->view('template', $this->data);
            }
        } else {
            redirect('login');
        }
    }

    public function edit($id_indikator = NULL)
    {
        if ($this->session->userdata('login') == TRUE){
            $this->data['title']  = 'Edit Indikator';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Indikator</a></li>
                                            <li><a href="#">Edit</a></li>
                                          </ul>';
            $this->data['main_view']   = 'indikator_view';
            $this->data['form_action'] = 'indikator/edit/' . $id_indikator;

            // option KD
            $kd = $this->kd->cari_semua();
            foreach($kd as $row)
            {
                $this->data['option_kd'][$row->id_kd] = $row->kd;
            }

            // pastikan id_indikator ada
            if( ! empty($id_indikator))
            {
                // submit
                if($this->input->post('submit'))
                {
                    // validasi berhasil
                    if($this->indikator->validasi_edit() === TRUE)
                    {
                        //update db
                        $this->indikator->edit($this->session->userdata('id_indikator_sekarang'));
                        $this->session->set_flashdata('pesan', 'Proses update data berhasil.');

                        redirect('indikator');
                    }
                    // validasi gagal
                    else
                    {
                        $this->load->view('template', $this->data);
                    }
                }
                // tidak disubmit, form pertama kali dimuat
                else
                {
                    // ambil data dari database, $form_value sebagai nilai dafault form
                    $indikator = $this->indikator->cari($id_indikator);
                    foreach($indikator as $key => $value)
                    {
                        $this->data['form_value'][$key] = $value;
                    }

                    // set teindikatororary data for edit
                    //echo print_r($indikator->id_indikator);
                    // echo print_r($indikator);
                    $this->session->set_userdata('id_indikator_sekarang', $indikator->id_indikator);
                    $this->session->set_userdata('indikator_sekarang', $indikator->indikator);
                    //$this->session->set_userdata('id_indikator_sekarang', '2');

                    $this->load->view('template', $this->data);
                }
            }
            // tidak ada parameter id_indikator, kembalikan ke halaman indikator
            else
            {
                redirect('indikator');
            }
        } else {
            redirect('login');
        }
    }

    public function hapus($id_indikator = NULL)
    {
        if ($this->session->userdata('login') == TRUE){
            // pastikan id_indikator yang akan dihapus
            if( ! empty($id_indikator))
            {
                if($this->indikator->hapus($id_indikator))
                {
                    $this->session->set_flashdata('pesan', 'Proses hapus data berhasil.');
                    redirect('indikator');
                }
                else
                {
                    $this->session->set_flashdata('pesan', 'Proses hapus data gagal.');
                    redirect('indikator');
                }
            }
            else
            {
                $this->session->set_flashdata('pesan', 'Proses hapus data gagal.');
                redirect('indikator');
            }
        } else {
            redirect('login');
        }
    }

    // callback, apakah id_indikator sama? untuk proses edit
    function is_id_indikator_exist()
    {
        $id_indikator_sekarang 	= $this->session->userdata('id_indikator_sekarang');
        $id_indikator_baru		= $this->input->post('id_indikator');

        // jika id_indikator baru dan id_indikator yang sedang diedit sama biarkan
        // artinya id_indikator tidak diganti
        if ($id_indikator_baru === $id_indikator_sekarang)
        {
            return TRUE;
        }
        // jika id_indikator yang sedang diupdate (di session) dan yang baru (dari form) tidak sama,
        // artinya id_indikator mau diganti
        // cek di database apakah id_indikator sudah terpakai?
        else
        {
            // cek database untuk id_indikator yang sama
            $query = $this->db->get_where('indikator', array('id_indikator' => $id_indikator_baru));

            // id_indikator sudah dipakai
            if($query->num_rows() > 0)
            {
                $this->form_validation->set_message('is_id_indikator_exist',
                    "Mata Pelajaran dengan kode $id_indikator_baru sudah terdaftar");
                return FALSE;
            }
            // id_indikator belum dipakai, OK
            else
            {
                return TRUE;
            }
        }
    }

    // callback, apakah nama indikator sama? untuk proses edit
    // penjelasan kurang lebih sama dengan is_id_indikator_exist
    function is_indikator_exist()
    {
        $indikator_sekarang 	= $this->session->userdata('indikator_sekarang');
        $indikator_baru		= $this->input->post('indikator');

        if ($indikator_baru === $indikator_sekarang)
        {
            return TRUE;
        }
        else
        {
            // cek database untuk nama indikator yang sama
            $query = $this->db->get_where('indikator', array('indikator' => $indikator_baru));
            if($query->num_rows() > 0)
            {
                $this->form_validation->set_message('is_indikator_exist',
                    "Mata Pelajaran dengan nama $indikator_baru sudah terdaftar");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
    }
}
/* End of file indikator.php */
/* Location: ./application/controllers/indikator.php */