<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ujian extends CI_Controller {

    public $data = array(
        'modul'         => 'ujian',
        'title'         => 'ujian Ujian',
        'breadcrumb'    => '<ul class="breadcrumb">
                                <li><a href="#">Depan</a></li>
                                <li><a href="#">Ujian</a></li>
                              </ul>',
        'pesan'         => '',
        'pagination'    => '',
        'tabel_data'    => '',
        'main_view'     => 'ujian',
        'form_action'   => '',
        'form_value'    => '',
        'option_indikator'  => '',
        'option_jawaban'  => '',
        'option_sulit'  => '',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ujian_model', 'ujian', TRUE);
        $this->load->model('entry_model', 'entry', TRUE);
        $this->load->model('indikator_model', 'indikator', TRUE);
    }

    public function index()
    {
        //session
        if ($this->session->userdata('login') == TRUE){
            // hapus data teujianorary proses update
            $this->session->unset_userdata('id_ujian_sekarang', '');
            $this->session->unset_userdata('ujian_sekarang', '');

            //sementara nanti diubah

            //echo $this->session->userdata('id_mp_sekarang');
            //$this->id_mp = $this->session->userdata('id_mp_sekarang');
            //echo $this->ujian->cari_mp();
            $sem=$this->ujian->cari_mp();
            $this->data['breadcrumb'] = "ujian $sem";
            $this->data['title']  = 'ujian '.$sem;
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Ujian</a></li>
                                            <li><a href="#">'.$sem.'</a></li>
                                          </ul>';
            // Cari semua data ujian
            $ujian = $this->ujian->cari_semua();

            // data ujian ada, tampilkan
            if ($ujian)
            {
                // buat tabel
               /* $tabel = $this->ujian->buat_tabel($ujian);
                $this->data['tabel_data'] = $tabel;*/

                //        load library pagination
                $this->load->library('pagination');
                
                //        configurasi pagination
                $config['base_url'] = base_url().'ujian/index';
                $config['total_rows'] = $this->ujian->total_record();
                $config['per_page'] = 10;
                $config['uri_segment'] = 3;

                $config['full_tag_open'] = "<ul class='pagination'>";
                $config['full_tag_close'] ="</ul>";
                $config['num_tag_open'] = '<li>';
                $config['num_tag_close'] = '</li>';
                $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
                $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
                $config['next_tag_open'] = "<li>";
                $config['next_tagl_close'] = "</li>";
                $config['prev_tag_open'] = "<li>";
                $config['prev_tagl_close'] = "</li>";
                $config['first_tag_open'] = "<li>";
                $config['first_tagl_close'] = "</li>";
                $config['last_tag_open'] = "<li>";
                $config['last_tagl_close'] = "</li>";

                $this->pagination->initialize($config); 
                
                //        menentukan offset record dari uri segment
                $start = $this->uri->segment(3, 0);
                //        ubah data menjadi tampilan per limit
                $rows = $this->ujian->ujian_limit($config['per_page'],$start)->result();
         
                $this->data['rows'] = $rows; 
                $this->data['pagination'] = $this->pagination->create_links(); 
                $this->data['start'] = $start; 

                $tabel = $this->ujian->buat_tabel($rows,$start);
                $this->data['tabel_data'] = $tabel;

                $this->load->view('template', $this->data);


            }
            // data ujian tidak ada
            else
            {
                $this->data['pesan'] = 'Tidak ada data ujian.';
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
        if ($this->session->userdata('login') == TRUE){
            $this->data['title']  = 'Tambah Ujian';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Ujian</a></li>
                                            <li><a href="#">Tambah</a></li>
                                          </ul>';
            $this->data['main_view']   = 'ujian_view';
            $this->data['form_action'] = 'ujian/tambah';

            // submit
            if($this->input->post('submit'))
            {
                // validasi sukses
                if($this->ujian->validasi_tambah())
                {
                    if($this->ujian->tambah())
                    {
                        $this->session->set_flashdata('pesan', 'Proses tambah data berhasil.');
                        redirect('ujian');
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

    public function edit($id_ujian = NULL)
    {
        if ($this->session->userdata('login') == TRUE){
            $this->data['title']  = 'Edit Ujian';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Ujian</a></li>
                                            <li><a href="#">Edit</a></li>
                                          </ul>';
            $this->data['main_view']   = 'ujian_view';
            $this->data['form_action'] = 'ujian/edit/' . $id_ujian;

            // pastikan id_ujian ada
            if(!empty($id_ujian))
            {
                // submit
                if($this->input->post('submit'))
                {
                    // validasi berhasil
                    if($this->ujian->validasi_edit() == TRUE)
                    {
                        //update db
                        $this->ujian->edit($this->session->userdata('id_ujian_sekarang'));
                        $this->session->set_flashdata('pesan', 'Proses update data berhasil.');

                        redirect('ujian');
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
                    $ujian = $this->ujian->cari($id_ujian);
                    foreach($ujian as $key => $value)
                    {
                        $this->data['form_value'][$key] = $value;
                    }

                    // set temporary data for edit
                    //echo print_r($ujian->id_ujian);
                    // echo print_r($ujian);
                    $this->session->set_userdata('id_ujian_sekarang', $ujian->id_ujian);
                    $this->session->set_userdata('ujian_sekarang', $ujian->ujian);
                    //$this->session->set_userdata('id_ujian_sekarang', '2');

                    $this->load->view('template', $this->data);
                }
            }
            // tidak ada parameter id_ujian, kembalikan ke halaman ujian
            else
            {
                redirect('ujian');
            }
        } else {
            redirect('login');
        }
    }

    public function hapus($id_ujian = NULL)
    {
        // pastikan id_ujian yang akan dihapus
        //session
        if ($this->session->userdata('login') == TRUE){
            if( ! empty($id_ujian))
            {
                if($this->ujian->hapus($id_ujian))
                {
                    $this->session->set_flashdata('pesan', 'Proses hapus data berhasil.');
                    redirect('ujian');
                }
                else
                {
                    $this->session->set_flashdata('pesan', 'Proses hapus data gagal.');
                    redirect('ujian');
                }
            }
            else
            {
                $this->session->set_flashdata('pesan', 'Proses hapus data gagal.');
                redirect('ujian');
            }
        } else {
            redirect('login');
        }
    }

    public function aktifkan($id_ujian = NULL)
    {
        // pastikan id_ujian yang akan dihapus
        //session
        if ($this->session->userdata('login') == TRUE){
            if( ! empty($id_ujian))
            {
                if($this->ujian->aktifkan($id_ujian))
                {
                    $this->session->set_flashdata('pesan', 'Aktivasi ujian berhasil.');
                    redirect('ujian');
                }
                else
                {
                    $this->session->set_flashdata('pesan', 'Aktivasi ujian gagal.');
                    redirect('ujian');
                }
            }
            else
            {
                $this->session->set_flashdata('pesan', 'Aktivasi ujian gagal.');
                redirect('ujian');
            }
        } else {
            redirect('login');
        }
    }

    public function nonaktifkan($id_ujian = NULL)
    {
        // pastikan id_ujian yang akan dihapus
        //session
        if ($this->session->userdata('login') == TRUE){
            if( ! empty($id_ujian))
            {
                if($this->ujian->nonaktifkan($id_ujian))
                {
                    $this->session->set_flashdata('pesan', 'Ujian berhasil dinonaktifkan.');
                    redirect('ujian');
                }
                else
                {
                    $this->session->set_flashdata('pesan', 'Ujian gagal dinonaktifkan.');
                    redirect('ujian');
                }
            }
            else
            {
                $this->session->set_flashdata('pesan', 'Ujian gagal dinonaktifkan.');
                redirect('ujian');
            }
        } else {
            redirect('login');
        }
    }

    public function pilih_soal($id_ujian = NULL)
    {
        if ($this->session->userdata('login') == TRUE){
            $nama_ujian = $this->ujian->cari($id_ujian); //cetak nama ujian

            $this->data['title']  = 'Pilih Soal Ujian';
            $this->data['breadcrumb'] = '<ul class="breadcrumb">
                                            <li><a href="#">Depan</a></li>
                                            <li><a href="#">Ujian</a></li>
                                            <li><a href="#">'.$nama_ujian->ujian.'</a></li>
                                            <li><a href="#">Pilih Soal</a></li>
                                          </ul>';
            $this->data['main_view']   = 'pilih_soal_view';
            $this->data['form_action'] = 'ujian/pilih_soal/'. $id_ujian;

            //jml soal
            $this->data['jumlah_soal'] = $this->ujian->jumlah_soal($id_ujian);

            // pastikan id_ujian ada
            if(!empty($id_ujian))
            {
                // submit
                if($this->input->post('submit'))
                {
                    $ujian = $this->ujian->cari($id_ujian);
                    $this->session->set_userdata('id_ujian_sekarang', $ujian->id_ujian);
                    $this->session->set_userdata('ujian_sekarang', $ujian->ujian);
                    // validasi berhasil
                    if($this->ujian->validasi_pilih_soal($this->ujian->jumlah_soal($id_ujian)) == TRUE){
                        //update db
                        $this->ujian->simpan_soal_ujian($this->session->userdata('id_ujian_sekarang'),$this->ujian->jumlah_soal($id_ujian));
                        $this->session->set_flashdata('pesan', 'Proses update data berhasil.');

                        redirect('ujian');
                    } else {

                        //$this->data['pesan'] = '<strong>Peringatan!</strong> Pastikan jumlah soal yang harus dipilih sebanyak <strong>'.$this->ujian->jumlah_soal($id_ujian).' soal</strong>.';
                        echo '<script>alert("Pastikan jumlah soal yang harus dipilih sebanyak '.$this->ujian->jumlah_soal($id_ujian).' soal.")</script>';
                        // Cari semua data ujian
                        $soal = $this->ujian->cari_semua_soal();

                        // buat tabel
                        $tabel = $this->ujian->buat_tabel_pilih_soal($soal);
                        $this->data['tabel_data'] = $tabel;

                        //jml soal tercentang
                        if(!empty($this->input->post('id_soal_terpilih'))){
                            $this->data['jml_soal_terpilih'] = count($this->input->post('id_soal_terpilih'));
                        } else { //jika form kosong
                            $this->data['jml_soal_terpilih'] = 0;
                        }
                        

                        $this->load->view('template', $this->data);

                    }
                }
                // tidak disubmit, form pertama kali dimuat
                else
                {
                    $ujian = $this->ujian->cari($id_ujian);
                    $this->session->set_userdata('id_ujian_sekarang', $ujian->id_ujian);
                    $this->session->set_userdata('ujian_sekarang', $ujian->ujian);

                    //jumlah soal terpilih
                    $soal_terpilih = $this->ujian->get_list_soal($this->uri->segment(3));
                    if(!empty($soal_terpilih)){
                        $array_list_soal    = explode(',', $soal_terpilih);
                        $this->data['jml_soal_terpilih'] = count($array_list_soal);
                    } else {
                        $this->data['jml_soal_terpilih'] = 0;
                    }
                    
                
                    // Cari semua data ujian
                    $soal = $this->ujian->cari_semua_soal();

                    // buat tabel
                    $tabel = $this->ujian->buat_tabel_pilih_soal($soal);
                    $this->data['tabel_data'] = $tabel;
                    $this->load->view('template', $this->data);

                }
            }
            // tidak ada parameter id_ujian, kembalikan ke halaman ujian
            else {
                redirect('ujian');
            }
        } else {
            redirect('login');
        }
    }

    // callback, apakah id_ujian sama? untuk proses edit
    function is_id_ujian_exist()
    {
        $id_ujian_sekarang 	= $this->session->userdata('id_ujian_sekarang');
        $id_ujian_baru		= $this->input->post('id_ujian');

        // jika id_ujian baru dan id_ujian yang sedang diedit sama biarkan
        // artinya id_ujian tidak diganti
        if ($id_ujian_baru === $id_ujian_sekarang)
        {
            return TRUE;
        }
        // jika id_ujian yang sedang diupdate (di session) dan yang baru (dari form) tidak sama,
        // artinya id_ujian mau diganti
        // cek di database apakah id_ujian sudah terpakai?
        else {
            // cek database untuk id_ujian yang sama
            $query = $this->db->get_where('ujian', array('id_ujian' => $id_ujian_baru));

            // id_ujian sudah dipakai
            if($query->num_rows() > 0)
            {
                $this->form_validation->set_message('is_id_ujian_exist',
                    "Mata Pelajaran dengan kode $id_ujian_baru sudah terdaftar");
                return FALSE;
            }
            // id_ujian belum dipakai, OK
            else
            {
                return TRUE;
            }
        }
    }

    // callback, apakah nama ujian sama? untuk proses edit
    // penjelasan kurang lebih sama dengan is_id_ujian_exist
    function is_ujian_exist()
    {
        $ujian_sekarang 	= $this->session->userdata('ujian_sekarang');
        $ujian_baru		= $this->input->post('ujian');

        if ($ujian_baru === $ujian_sekarang)
        {
            return TRUE;
        }
        else
        {
            // cek database untuk nama ujian yang sama
            $query = $this->db->get_where('ujian', array('ujian' => $ujian_baru));
            if($query->num_rows() > 0)
            {
                $this->form_validation->set_message('is_ujian_exist',
                    "Mata Pelajaran dengan nama $ujian_baru sudah terdaftar");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
    }

    public function expor_pdf($id_ujian){
        $data['info_ujian'] = $this->ujian->get_ujian($id_ujian);
        $filename = str_replace(" ","_", str_replace('/','_',$data['info_ujian']->ujian));

        if(file_exists("kartu_soal/$filename.pdf")){
            unlink("kartu_soal/$filename.pdf");    
        }
        $pdfFilePath = "kartu_soal/$filename.pdf";
        $data['kartu_soal'] = $this->ujian->get_kartu_soal($id_ujian);

        if (file_exists($pdfFilePath) == FALSE)
        {
            $html = $this->load->view('kartu_soal_view', $data, true); 
            ini_set('memory_limit','32M');
            $this->load->library('m_pdf');
            
            $pdf = $this->m_pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output($pdfFilePath, "I");

        }
    }
        public function expor_tampil($id_ujian){
        $data['info_ujian'] = $this->ujian->get_ujian($id_ujian);
        $filename = str_replace(" ","_", str_replace('/','_',$data['info_ujian']->ujian));

        if(file_exists("kartu_soal/$filename.pdf")){
            unlink("kartu_soal/$filename.pdf");    
        }
        $pdfFilePath = "kartu_soal/$filename.pdf";

        $data['kartu_soal'] = $this->ujian->get_kartu_soal($id_ujian);
        //var_dump($data);
        $this->load->view('kartu_soal_view', $data);

        /*if (file_exists($pdfFilePath) == FALSE)
        {
            $html = $this->load->view('kartu_soal_view', $data, true); 
            ini_set('memory_limit','32M');
            $this->load->library('m_pdf');
            
            $pdf = $this->m_pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output($pdfFilePath, "I");

        }*/
    }

}
/* End of file ujian.php */
/* Location: ./application/controllers/ujian.php */