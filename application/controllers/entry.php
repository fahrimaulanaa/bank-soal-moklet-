<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Entry extends CI_Controller {

    public $data = array(
        'modul'         => 'soal',
        'title'         => 'Soal Ujian',
        'breadcrumb'    => '<ul class="breadcrumb">
                                <li><a href="#">Depan</a></li>
                                <li><a href="#">Entry</a></li>
                              </ul>',
        'pesan'         => '',
        'pagination'    => '',
        'tabel_data'    => '',
        'main_view'     => 'entry',
        'form_action'   => '',
        'form_value'    => '',
        'option_indikator'  => '',
        'option_jawaban'  => '',
        'option_sulit'  => '',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('entry_model', 'entry', TRUE);
        $this->load->model('indikator_model', 'indikator', TRUE);
    }

    public function index()
    {
        //session
        if ($this->session->userdata('login') == TRUE){
            // hapus data teentryorary proses update
            $this->session->unset_userdata('id_soal_sekarang', '');
            $this->session->unset_userdata('soal_sekarang', '');

            //sementara nanti diubah

            //echo $this->session->userdata('id_mp_sekarang');
            //$this->id_mp = $this->session->userdata('id_mp_sekarang');
            //echo $this->entry->cari_mp();
            $sem=$this->entry->cari_mp();
            $this->data['breadcrumb'] = "Soal $sem";
            $this->data['title']  = 'Soal '.$sem;
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Entry</a></li>
                                            <li><a href="#">'.$sem.'</a></li>
                                          </ul>';
            // Cari semua data entry
            $entry = $this->entry->cari_semua();

            // data entry ada, tampilkan
            if ($entry)
            {
                // buat tabel
                $tabel = $this->entry->buat_tabel($entry);
                $this->data['tabel_data'] = $tabel;

                $this->load->view('template', $this->data);


            }
            // data entry tidak ada
            else
            {
                $this->data['pesan'] = 'Tidak ada data entry.';
                $this->load->view('template', $this->data);
            }
        }
        else
        {
            redirect('login');
        }

    }

    function get_enum_values( $table, $field )
    {
        $type = $this->db->query( "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'" )->row( 0 )->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);

        return $enum;
    }


    public function tambah()
    {
        //session
        if ($this->session->userdata('login') == TRUE){
            //echo 'tambah;';
            $this->data['title']  = 'Soal Ujian';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Entry</a></li>
                                            <li><a href="#">Tambah</a></li>
                                          </ul>';
            $this->data['main_view']   = 'entry_view';
            $this->data['form_action'] = 'entry/tambah';

            // option kelas, untuk menu dropdown
            $indikator = $this->indikator->cari_semua();

            // data indikator ada
            if($indikator)
            {
                foreach($indikator as $row)
                {
                    $this->data['option_indikator'][$row->id_indikator] = $row->indikator;
                }
            }
            // data kelas tidak ada
            else
            {
                $this->data['option_indikator']['00'] = '-';
                $this->data['pesan'] = 'Indikator tidak tersedia. Silahkan isi dahulu data Indikator.';
                //$this->load->view('template', $this->data);
            }

            //$this->do_upload();
            $this->data['option_sulit']=$this->get_enum_values('soal','sulit');
            $this->data['option_jawaban']=$this->get_enum_values('soal','jawaban');

           // submit
            if($this->input->post('submit'))
            {
                // validasi sukses
                if($this->entry->validasi_tambah())
                {

                    if($this->entry->tambah())
                    {
                        $this->session->set_flashdata('pesan', 'Proses tambah data berhasil.');
                        redirect('entry');
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
        }
        else
        {
            redirect(login);
        }
    }

    function get_sulit_index( $data_sulit )
    {
        if ($data_sulit=='Mudah') $index=0;
        else if ($data_sulit=='Sedang') $index=1;
        else if ($data_sulit=='Sulit') $index=2;

        return $index;
    }

    function get_jawaban_index( $data_jawaban )
    {
        if ($data_jawaban=='a') $index=0;
        else if ($data_jawaban=='b') $index=1;
        else if ($data_jawaban=='c') $index=2;
        else if ($data_jawaban=='d') $index=3;
        else if ($data_jawaban=='e') $index=4;

        return $index;
    }

    public function gambar($id_soal = NULL)
    {
        //session
        if ($this->session->userdata('login') == TRUE){
            //buat session id soal untuk upload gambar
            $this->session->set_userdata('id_soal_sekarang', $id_soal);

            if(!empty($this->session->userdata('id_soal_sekarang'))){
                redirect('upload');
            }
        } else {
            redirect(login);
        }
    }

    public function lagu($id_soal = NULL)
    {
        //session
        if ($this->session->userdata('login') == TRUE){

            //buat session id soal untuk upload gambar
            $this->session->set_userdata('id_soal_sekarang', $id_soal);

            if(!empty($this->session->userdata('id_soal_sekarang'))){
                redirect('upload_lagu');
            }
        } else {
            redirect(login);
        }
    }
    public function edit($id_soal = NULL)
    {
        //session
        if ($this->session->userdata('login') == TRUE){
            $this->data['title']  = 'Edit Soal';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Entry</a></li>
                                            <li><a href="#">Edit</a></li>
                                          </ul>';
            $this->data['main_view']   = 'entry_view';
            $this->data['form_action'] = 'entry/edit/' . $id_soal;

            // option KD
            $indikator = $this->indikator->cari_semua();
            foreach($indikator as $row)
            {
                $this->data['option_indikator'][$row->id_indikator] = $row->indikator;
            }

            $this->data['option_sulit']=$this->get_enum_values('soal','sulit');
            $this->data['option_jawaban']=$this->get_enum_values('soal','jawaban');

            // pastikan id_soal ada
            if( ! empty($id_soal))
            {
                // submit
                if($this->input->post('submit'))
                {
                    // validasi berhasil
                    if($this->entry->validasi_edit() == TRUE)
                    {
                        //update db
                        $this->entry->edit($this->session->userdata('id_soal_sekarang'));
                        $this->session->set_flashdata('pesan', 'Proses update data berhasil.');

                        redirect('entry');
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
                    $entry = $this->entry->cari($id_soal);
                    foreach($entry as $key => $value)
                    {
                        if ($key=='sulit')$this->data['form_value'][$key] = $this->get_sulit_index($value);
                        else if ($key=='jawaban')$this->data['form_value'][$key] = $this->get_jawaban_index($value);
                        else $this->data['form_value'][$key] = $value;
                    }

                    // set teentryorary data for edit
    //                echo $entry->id_soal;
    //                echo ';';
    //                echo $entry->soal;
    //                echo ';';
    //                echo $this->get_sulit_index($entry->sulit);
                     //echo print_r($entry);
                    $this->session->set_userdata('id_soal_sekarang', $entry->id_soal);
                    $this->session->set_userdata('soal_sekarang', $entry->soal);
                    //$this->session->set_userdata('id_soal_sekarang', '2');

                    $this->load->view('template', $this->data);
                }
            }
            // tidak ada parameter id_soal, kembalikan ke halaman entry
            else
            {
                redirect('entry');
            }
        } else {
            redirect(login);
        }
    }

    public function hapus($id_soal = NULL)
    {
        // pastikan id_soal yang akan dihapus
        //session
        if ($this->session->userdata('login') == TRUE){
            if( ! empty($id_soal))
            {
                if($this->entry->hapus($id_soal))
                {
                    $this->session->set_flashdata('pesan', 'Proses hapus data berhasil.');
                    redirect('entry');
                }
                else
                {
                    $this->session->set_flashdata('pesan', 'Proses hapus data gagal.');
                    redirect('entry');
                }
            }
            else
            {
                $this->session->set_flashdata('pesan', 'Proses hapus data gagal.');
                redirect('entry');
            }
        } else {
            redirect(login);
        }
    }

    // callback, apakah id_soal sama? untuk proses edit
    function is_id_soal_exist()
    {
        $id_soal_sekarang 	= $this->session->userdata('id_soal_sekarang');
        $id_soal_baru		= $this->input->post('id_soal');

        // jika id_soal baru dan id_soal yang sedang diedit sama biarkan
        // artinya id_soal tidak diganti
        if ($id_soal_baru === $id_soal_sekarang)
        {
            return TRUE;
        }
        // jika id_soal yang sedang diupdate (di session) dan yang baru (dari form) tidak sama,
        // artinya id_soal mau diganti
        // cek di database apakah id_soal sudah terpakai?
        else
        {
            // cek database untuk id_soal yang sama
            $query = $this->db->get_where('soal', array('id_soal' => $id_soal_baru));

            // id_soal sudah dipakai
            if($query->num_rows() > 0)
            {
                $this->form_validation->set_message('is_id_soal_exist',
                    "Mata Pelajaran dengan kode $id_soal_baru sudah terdaftar");
                return FALSE;
            }
            // id_soal belum dipakai, OK
            else
            {
                return TRUE;
            }
        }
    }

    // callback, apakah nama entry sama? untuk proses edit
    // penjelasan kurang lebih sama dengan is_id_soal_exist
    function is_entry_exist()
    {
        $soal_sekarang 	= $this->session->userdata('soal_sekarang');
        $soal_baru		= $this->input->post('soal');

        if ($soal_baru === $soal_sekarang)
        {
            return TRUE;
        }
        else
        {
            // cek database untuk nama entry yang sama
            $query = $this->db->get_where('soal', array('soal' => $soal_baru));
            if($query->num_rows() > 0)
            {
                $this->form_validation->set_message('is_entry_exist',
                    "Mata Pelajaran dengan nama $soal_baru sudah terdaftar");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
    }

//    public function file_view(){
//        $this->load->view('file_view', array('error' => ' ' ));
//    }
    public function do_upload(){
        echo'upload;';
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 5000;
        $config['max_width']            = 4000;
        $config['max_height']           = 3000;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
            $error = array('error' => $this->upload->display_errors());
            echo'sukses;';
            //$this->load->view('upload_form', $error);
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            echo'gagal;';
            //$this->load->view('upload_success', $data);
        }
    }
}
/* End of file entry.php */
/* Location: ./application/controllers/entry.php */