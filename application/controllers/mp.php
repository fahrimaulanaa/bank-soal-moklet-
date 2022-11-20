<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MP extends CI_Controller {

    public $data = array(
        'modul'         => 'mp',
        'title'         => 'Mata Pelajaran',
        'breadcrumb'    => '<ul class="breadcrumb">
                                <li><a href="#">Depan</a></li>
                                <li><a href="#">Mata Pelajaran</a></li>
                              </ul>',
        'pesan'         => '',
        'pagination'    => '',
        'tabel_data'    => '',
        'main_view'     => 'mp',
        'form_action'   => '',
        'form_value'    => '',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('MP_model', 'mp', TRUE);
    }

    public function index()
    {
        if ($this->session->userdata('login') == TRUE){
            // hapus data temporary proses update
            $this->session->unset_userdata('id_mp_sekarang', '');
            $this->session->unset_userdata('mp_sekarang', '');

            // Cari semua data mp
            $mp = $this->mp->cari_semua();
            $this->data['title']  = 'Mata Pelajaran';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Mata Pelajaran</a></li>
                                          </ul>';

            // data mp ada, tampilkan
            if ($mp)
            {
                // buat tabel
                $tabel = $this->mp->buat_tabel($mp);
                $this->data['tabel_data'] = $tabel;
                $this->load->view('template', $this->data);

            }
            // data mp tidak ada
            else
            {
                $this->data['pesan'] = 'Tidak ada data MP.';
                $this->load->view('template', $this->data);
            }
        } else {
            redirect('login');
        }
    }

    public function tambah()
    {
        if ($this->session->userdata('login') == TRUE){
            $this->data['title']  = 'Tambah Mata Pelajaran';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Mata Pelajaran</a></li>
                                            <li><a href="#">Tambah</a></li>
                                          </ul>';
            $this->data['main_view']   = 'mp_view';
            $this->data['form_action'] = 'mp/tambah';

            // submit
            if($this->input->post('submit'))
            {
                // validasi sukses
                if($this->mp->validasi_tambah())
                {
                    if($this->mp->tambah())
                    {
                        $this->session->set_flashdata('pesan', 'Proses tambah data berhasil.');
                        redirect('mp');
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

    public function edit($id_mp = NULL)
    {
        if ($this->session->userdata('login') == TRUE){
            $this->data['title']  = 'Edit Mata Pelajaran';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Mata Pelajaran</a></li>
                                            <li><a href="#">Tambah</a></li>
                                          </ul>';
            $this->data['main_view']   = 'mp_view';
            $this->data['form_action'] = 'mp/edit/' . $id_mp;

            // pastikan id_mp ada
            if( ! empty($id_mp))
            {
                // submit
                if($this->input->post('submit'))
                {
                    // validasi berhasil
                    if($this->mp->validasi_edit() === TRUE)
                    {
                        //update db
                        $this->mp->edit($this->session->userdata('id_mp_sekarang'));
                        $this->session->set_flashdata('pesan', 'Proses update data berhasil.');

                        redirect('mp');
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
                    $mp = $this->mp->cari($id_mp);
                    foreach($mp as $key => $value)
                    {
                        $this->data['form_value'][$key] = $value;
                    }

                    // set temporary data for edit
                    //echo print_r($mp->id_mp);
                   // echo print_r($mp);
                    $this->session->set_userdata('id_mp_sekarang', $mp->id_mp);
                    $this->session->set_userdata('mp_sekarang', $mp->mp);
                    //$this->session->set_userdata('id_mp_sekarang', '2');

                    $this->load->view('template', $this->data);
                }
            }
            // tidak ada parameter id_mp, kembalikan ke halaman mp
            else
            {
                redirect('mp');
            }
        } else {
            redirect('login');
        }
    }

    public function hapus($id_mp = NULL)
    {
        if ($this->session->userdata('login') == TRUE){
            // pastikan id_mp yang akan dihapus
            if( ! empty($id_mp))
            {
                if($this->mp->hapus($id_mp))
                {
                    $this->session->set_flashdata('pesan', 'Proses hapus data berhasil.');
                    redirect('mp');
                }
                else
                {
                    $this->session->set_flashdata('pesan', 'Proses hapus data gagal.');
                    redirect('mp');
                }
            }
            else
            {
                $this->session->set_flashdata('pesan', 'Proses hapus data gagal.');
                redirect('mp');
            }
        } else {
            redirect('login');
        }
    }

    // callback, apakah id_mp sama? untuk proses edit
    function is_id_mp_exist()
    {
        $id_mp_sekarang 	= $this->session->userdata('id_mp_sekarang');
        $id_mp_baru		= $this->input->post('id_mp');

        // jika id_mp baru dan id_mp yang sedang diedit sama biarkan
        // artinya id_mp tidak diganti
        if ($id_mp_baru === $id_mp_sekarang)
        {
            return TRUE;
        }
        // jika id_mp yang sedang diupdate (di session) dan yang baru (dari form) tidak sama,
        // artinya id_mp mau diganti
        // cek di database apakah id_mp sudah terpakai?
        else
        {
            // cek database untuk id_mp yang sama
            $query = $this->db->get_where('mp', array('id_mp' => $id_mp_baru));

            // id_mp sudah dipakai
            if($query->num_rows() > 0)
            {
                $this->form_validation->set_message('is_id_mp_exist',
                    "Mata Pelajaran dengan kode $id_mp_baru sudah terdaftar");
                return FALSE;
            }
            // id_mp belum dipakai, OK
            else
            {
                return TRUE;
            }
        }
    }

    // callback, apakah nama mp sama? untuk proses edit
    // penjelasan kurang lebih sama dengan is_id_mp_exist
    function is_mp_exist()
    {
        $mp_sekarang 	= $this->session->userdata('mp_sekarang');
        $mp_baru		= $this->input->post('mp');

        if ($mp_baru === $mp_sekarang)
        {
            return TRUE;
        }
        else
        {
            // cek database untuk nama mp yang sama
            $query = $this->db->get_where('mp', array('mp' => $mp_baru));
            if($query->num_rows() > 0)
            {
                $this->form_validation->set_message('is_mp_exist',
                    "Mata Pelajaran dengan nama $mp_baru sudah terdaftar");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
    }
}
/* End of file mp.php */
/* Location: ./application/controllers/mp.php */