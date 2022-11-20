<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class KD extends CI_Controller {


    public $data = array(
        'modul'         => 'kd',
        'title'         => 'Kompetensi Dasar',
        'breadcrumb'    => '<ul class="breadcrumb">
                                <li><a href="#">Depan</a></li>
                                <li><a href="#">Kompetensi Dasar</a></li>
                              </ul>',
        'pesan'         => '',
        'pagination'    => '',
        'tabel_data'    => '',
        'main_view'     => 'kd',
        'form_action'   => '',
        'form_value'    => '',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('kd_model', 'kd', TRUE);
    }

    public function index()
    {
        //session
        if ($this->session->userdata('login') == TRUE){
            // hapus data tekdorary proses update
            $this->session->unset_userdata('id_kd_sekarang', '');
            $this->session->unset_userdata('kd_sekarang', '');

            //sementara nanti diubah

            //echo $this->session->userdata('id_mp_sekarang');
            //$this->id_mp = $this->session->userdata('id_mp_sekarang');
            //echo $this->kd->cari_mp();
            $sem=$this->kd->cari_mp();
            $this->data['title']  = 'Kompetensi Dasar '.$sem;
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Kompetensi Dasar</a></li>
                                            <li><a href="#">'.$sem.'</a></li>
                                          </ul>';
            // Cari semua data kd
            $kd = $this->kd->cari_semua();

            // data kd ada, takdilkan
            if ($kd)
            {
                // buat tabel
                $tabel = $this->kd->buat_tabel($kd);
                $this->data['tabel_data'] = $tabel;
                $this->load->view('template', $this->data);
            }
            // data kd tidak ada
            else
            {
                $this->data['pesan'] = 'Tidak ada data kd.';
                $this->load->view('template', $this->data);
            }
        } else {
            redirect('login');
        }
    }

    public function tambah()
    {
        if ($this->session->userdata('login') == TRUE){
            $this->data['title']  = 'Tambah Kompetensi Dasar';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Kompetensi Dasar</a></li>
                                            <li><a href="#">Tambah</a></li>
                                          </ul>';
            $this->data['main_view']   = 'kd_view';
            $this->data['form_action'] = 'kd/tambah';

            // submit
            if($this->input->post('submit'))
            {
                // validasi sukses
                if($this->kd->validasi_tambah())
                {
                    if($this->kd->tambah())
                    {
                        $this->session->set_flashdata('pesan', 'Proses tambah data berhasil.');
                        redirect('kd');
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

    public function edit($id_kd = NULL)
    {
        if ($this->session->userdata('login') == TRUE){
            $this->data['title']  = 'Edit Kompetensi Dasar';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Kompetensi Dasar</a></li>
                                            <li><a href="#">Edit</a></li>
                                          </ul>';
            $this->data['main_view']   = 'kd_view';
            $this->data['form_action'] = 'kd/edit/' . $id_kd;

            // pastikan id_kd ada
            if( ! empty($id_kd))
            {
                // submit
                if($this->input->post('submit'))
                {
                    // validasi berhasil
                    if($this->kd->validasi_edit() === TRUE)
                    {
                        //update db
                        $this->kd->edit($this->session->userdata('id_kd_sekarang'));
                        $this->session->set_flashdata('pesan', 'Proses update data berhasil.');

                        redirect('kd');
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
                    $kd = $this->kd->cari($id_kd);
                    foreach($kd as $key => $value)
                    {
                        $this->data['form_value'][$key] = $value;
                    }

                    // set tekdorary data for edit
                    //echo print_r($kd->id_kd);
                   // echo print_r($kd);
                    $this->session->set_userdata('id_kd_sekarang', $kd->id_kd);
                    $this->session->set_userdata('kd_sekarang', $kd->kd);
                    //$this->session->set_userdata('id_kd_sekarang', '2');

                    $this->load->view('template', $this->data);
                }
            }
            // tidak ada parameter id_kd, kembalikan ke halaman kd
            else
            {
                redirect('kd');
            }
        }else {
            redirect('login');
        }
    }

    public function hapus($id_kd = NULL)
    {
        if ($this->session->userdata('login') == TRUE){
            // pastikan id_kd yang akan dihapus
            if( ! empty($id_kd))
            {
                if($this->kd->hapus($id_kd))
                {
                    $this->session->set_flashdata('pesan', 'Proses hapus data berhasil.');
                    redirect('kd');
                }
                else
                {
                    $this->session->set_flashdata('pesan', 'Proses hapus data gagal.');
                    redirect('kd');
                }
            }
            else
            {
                $this->session->set_flashdata('pesan', 'Proses hapus data gagal.');
                redirect('kd');
            }
        } else {
            redirect('login');
        }
    }

    // callback, apakah id_kd sama? untuk proses edit
    function is_id_kd_exist()
    {
        $id_kd_sekarang 	= $this->session->userdata('id_kd_sekarang');
        $id_kd_baru		= $this->input->post('id_kd');

        // jika id_kd baru dan id_kd yang sedang diedit sama biarkan
        // artinya id_kd tidak diganti
        if ($id_kd_baru === $id_kd_sekarang)
        {
            return TRUE;
        }
        // jika id_kd yang sedang diupdate (di session) dan yang baru (dari form) tidak sama,
        // artinya id_kd mau diganti
        // cek di database apakah id_kd sudah terpakai?
        else
        {
            // cek database untuk id_kd yang sama
            $query = $this->db->get_where('kd', array('id_kd' => $id_kd_baru));

            // id_kd sudah dipakai
            if($query->num_rows() > 0)
            {
                $this->form_validation->set_message('is_id_kd_exist',
                    "Mata Pelajaran dengan kode $id_kd_baru sudah terdaftar");
                return FALSE;
            }
            // id_kd belum dipakai, OK
            else
            {
                return TRUE;
            }
        }
    }

    // callback, apakah nama kd sama? untuk proses edit
    // penjelasan kurang lebih sama dengan is_id_kd_exist
    function is_kd_exist()
    {
        $kd_sekarang 	= $this->session->userdata('kd_sekarang');
        $kd_baru		= $this->input->post('kd');

        if ($kd_baru === $kd_sekarang)
        {
            return TRUE;
        }
        else
        {
            // cek database untuk nama kd yang sama
            $query = $this->db->get_where('kd', array('kd' => $kd_baru));
            if($query->num_rows() > 0)
            {
                $this->form_validation->set_message('is_kd_exist',
                    "Mata Pelajaran dengan nama $kd_baru sudah terdaftar");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
    }
}
/* End of file kd.php */
/* Location: ./application/controllers/kd.php */