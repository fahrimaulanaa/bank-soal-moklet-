<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Analisa extends CI_Controller {

    public $data = array(
        'modul'         => 'analisa',
        'title'         => 'Analisa Ujian',
        'pesan'         => '',
        'pagination'    => '',
        'tabel_data'    => '',
        'main_view'     => 'analisa',
        'form_action'   => '',
        'form_value'    => '',
        'option_indikator'  => '',
        'option_jawaban'  => '',
        'option_sulit'  => '',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('analisa_model', 'analisa', TRUE);
        $this->load->model('entry_model', 'entry', TRUE);
        $this->load->model('indikator_model', 'indikator', TRUE);
        $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
    }

    public function index()
    {
        //session
        if ($this->session->userdata('login') == TRUE){
            // hapus data teujianorary proses update
            $this->session->unset_userdata('id_ujian_sekarang', '');
            $this->session->unset_userdata('ujian_sekarang', '');
            $this->data['title']  = 'Analisa Hasil Ujian';

            // Cari semua data ujian
            $ujian = $this->analisa->cari_semua();

            // data ujian ada, tampilkan
            if ($ujian)
            {
                // buat tabel
               /* $tabel = $this->analisa->buat_tabel($ujian);
                $this->data['tabel_data'] = $tabel;*/

                //        load library pagination
                $this->load->library('pagination');
                
                //        configurasi pagination
                $config['base_url'] = base_url().'analisa/index';
                $config['total_rows'] = $this->analisa->total_record();
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
                $rows = $this->analisa->ujian_limit($config['per_page'],$start)->result();
         
                $this->data['rows'] = $rows; 
                $this->data['pagination'] = $this->pagination->create_links(); 
                $this->data['start'] = $start; 

                $tabel = $this->analisa->buat_tabel($rows,$start);
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

    public function publish($id_ujian = NULL){
        if ($this->session->userdata('login') == TRUE){
            if($this->analisa->publish_ujian($id_ujian)){
                $this->session->set_flashdata('pesan', 'Publish ujian berhasil!');
                redirect('analisa');
            } else {
                $this->session->set_flashdata('pesan', 'Publish ujian gagal!');
                redirect('analisa');
            }
        } else {
            redirect('login');
        }
    }

    public function unpublish($id_ujian = NULL){
        if ($this->session->userdata('login') == TRUE){
            if($this->analisa->unpublish_ujian($id_ujian)){
                $this->session->set_flashdata('pesan', 'Unpublish ujian berhasil!');
                redirect('analisa');
            } else {
                $this->session->set_flashdata('pesan', 'Unpublish ujian gagal!');
                redirect('analisa');
            }
        } else {
            redirect('login');
        }
    }

    public function lihat($id_ujian = NULL)
    {
        if ($this->session->userdata('login') == TRUE){

            $this->data['title']  = 'Analisa Hasil Ujian';
            $this->data['main_view']   = 'analisa_view';

            // pastikan id_ujian ada
            if(!empty($id_ujian))
            {
                $ujian = $this->analisa->cari($id_ujian);
                $this->session->set_userdata('id_ujian_sekarang', $ujian->id_ujian);
                $this->session->set_userdata('ujian_sekarang', $ujian->ujian);

                //get kelas
                $this->data['kelas'] = $this->analisa->get_kelas($id_ujian);

                if ($this->session->userdata('id_ujian_sekarang') != NULL){
                    //get jml soal
                    $this->data['jumlah_soal'] = $this->analisa->jumlah_soal($id_ujian);

                    //get data jawaban
                    if($this->uri->segment(4) != NULL){
                        $data_jawaban = $this->analisa->get_data_jawaban_by_kelas($id_ujian,$this->uri->segment(4));
                    } else {
                        $data_jawaban = $this->analisa->get_data_jawaban($id_ujian);
                    }

                    // buat tabel
                    $tabel = $this->analisa->buat_tabel_analisa($data_jawaban,$this->data['jumlah_soal'],$ujian->list_soal);
                    $this->data['tabel_data'] = $tabel;

                    $this->load->view('template', $this->data);

                } else {
                    redirect('analisa');
                }

            }
            // tidak ada parameter id_ujian, kembalikan ke halaman ujian
            else {
                redirect('analisa');
            }
        } else {
            redirect('login');
        }
    }

    public function detil_soal($id_soal = NULL){
        if ($this->session->userdata('login') == TRUE){

            // pastikan id_ujian ada
            if(!empty($id_soal)){

                $detil_soal = $this->analisa->get_data_soal($id_soal);
                $this->detil['kd_soal'] = $detil_soal->kd;
                $this->detil['gambar_soal'] = $detil_soal->gambar;
                $this->detil['suara_soal'] = $detil_soal->suara;
                $this->detil['indikator_soal'] = $detil_soal->indikator;
                $this->detil['soal'] = htmlentities($detil_soal->soal);
                $this->detil['jawabs'] = strtoupper($detil_soal->jawaban);

                echo $this->json($this->detil);

            } else {
                redirect('analisa');
            }
        } else {
            redirect('login');
        }
    }

    public function ekspor($id_ujian = NULL){
        if ($this->session->userdata('login') == TRUE){
            //get list soal
            $ujian = $this->analisa->cari($id_ujian);

            //get data jawaban
            if($this->uri->segment(4) != NULL){
                $data_jawaban = $this->analisa->get_data_jawaban_by_kelas($id_ujian,$this->uri->segment(4));
            } else {
                $data_jawaban = $this->analisa->get_data_jawaban($id_ujian);
            }

            $jumlah_soal = $this->analisa->jumlah_soal($id_ujian);
            $ambildata = $this->analisa->ekspor($data_jawaban,$jumlah_soal,$ujian->list_soal);
             
            if(count($ambildata)>0){
                $objPHPExcel = new PHPExcel();
                // Set properties
                $objPHPExcel->getProperties()->setCreator("SMK Telkom Malang")->setTitle("Analisa Hasil Ujian");

                for($sheet=0;$sheet<2;$sheet++){
                    //mengisi konten di sheet ke 1
                    if($sheet == 0){
                        $objset = $objPHPExcel->setActiveSheetIndex(0); //inisiasi set object
                        $objget = $objPHPExcel->getActiveSheet();  //inisiasi get object
             
                        $objget->setTitle('Hasil Analisa'); //sheet title
             
                        //table header
                        $_col = 'A';
                        $cols = array();
                        for($i=0;$i<$jumlah_soal+6;$i++){
                            $cols[$i] = $_col;
                            $_col++;
                        }

                        $objget->getStyle('A1:'.$_col.'1')->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => '92d050')
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                )
                            )
                        );
                        
                        $val = array();
                        $val[0] = "NO";
                        $val[1] = "NAMA SISWA";
                        $val[2] = "KELAS";
                        for($y=0;$y<$jumlah_soal;$y++){
                            $val[$y+3] = $y+1;
                        }
                        $val[$jumlah_soal+3] = "BENAR";
                        $val[$jumlah_soal+4] = "SALAH";
                        $val[$jumlah_soal+5] = "NILAI";
                         
                        for ($a=0;$a<$jumlah_soal+6; $a++) 
                        {
                            $objset->setCellValue($cols[$a].'1', $val[$a]);
                             
                            //Setting lebar cell
                            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Nomor
                            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25); // Nama
                            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10); // Kelas

                            $col_ = 'D';
                            for($x=0;$x<$jumlah_soal;$x++){
                                $objPHPExcel->getActiveSheet()->getColumnDimension($col_)->setWidth(3); // jawaban
                                $col_++;
                            }

                            $col__ = $col_; //membuat kolom baru dengan urutan kolom terakhir col_
                            for($x=0;$x<3;$x++){
                                $col__++;
                                $objPHPExcel->getActiveSheet()->getColumnDimension($col__)->setWidth(5); // jumlah benar, salah, dan nilai
                            }
                         
                            $style = array(
                                'alignment' => array(
                                    'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                )
                            );
                            $objPHPExcel->getActiveSheet()->getStyle($cols[$a].'1')->applyFromArray($style);
                        }
                        
                        //pengisian data di baris ke 2 dan seterusnya
                        $baris  = 2;
                        foreach ($ambildata as $frow){
                            $column = 'A';
                            for($z=0;$z<$jumlah_soal+6;$z++){
                                //memberikan formula menghitung jumlah benar
                                if($z == $jumlah_soal+3){
                                    //mencari ujung kolom butir soal
                                    $column_ = 'C';
                                    for($x=0;$x<$jumlah_soal;$x++){
                                        $column_++;
                                    }
                                    $objset->setCellValue($column.$baris, '=SUM(D'.$baris.':'.$column_.$baris.')');
                                    //Set number value
                                    $objPHPExcel->getActiveSheet()->getStyle($column.$baris)->getNumberFormat()->setFormatCode('0');
                                }
                                //memberikan formula menghitung jumlah salah
                                else if($z == $jumlah_soal+4){
                                    //mencari ujung kolom butir soal
                                    $column_ = 'C';
                                    for($x=0;$x<$jumlah_soal;$x++){
                                        $column_++;
                                    }
                                    $objset->setCellValue($column.$baris, '=COUNT(D'.$baris.':'.$column_.$baris.')-SUM(D'.$baris.':'.$column_.$baris.')');
                                    //Set number value
                                    $objPHPExcel->getActiveSheet()->getStyle($column.$baris)->getNumberFormat()->setFormatCode('0');
                                }
                                //memberikan formula menghitung nilai
                                else if($z == $jumlah_soal+5){
                                    //mencari ujung kolom butir soal
                                    $column_ = 'C';
                                    for($x=0;$x<$jumlah_soal;$x++){
                                        $column_++;
                                    }
                                    $objset->setCellValue($column.$baris, '=(SUM(D'.$baris.':'.$column_.$baris.')/'.$jumlah_soal.')*100');
                                    //Set number value
                                    $objPHPExcel->getActiveSheet()->getStyle($column.$baris)->getNumberFormat()->setFormatCode('0');
                                } else {
                                    $objset->setCellValue($column.$baris, $frow[$z]);
                                }
                                $column++;
                            }

                            $baris++;
                        }

                        //buat baris baru untuk menghitung jumlah benar per butir soal
                        $column = 'D';
                        //merge cell
                        $objset->setCellValue('A'.$baris, 'JUMLAH BENAR PER BUTIR SOAL');
                        $objset = $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$baris.':C'.$baris);
                        for($z=0;$z<$jumlah_soal;$z++){

                            $objset->setCellValue($column.$baris, '=SUM('.$column.'2:'.$column.($baris-1).')');
                            //Set number value
                            $objPHPExcel->getActiveSheet()->getStyle($column.$baris)->getNumberFormat()->setFormatCode('0');

                            $column++;
                        }
                        $objget->getStyle('A'.$baris.':'.$column.$baris)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'e5e5e5')
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                ),
                                'alignment' => array(
                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                                )
                            )
                        );
                        
                        if($this->uri->segment(4) != NULL){
                            $objPHPExcel->getActiveSheet()->setTitle('Hasil Ujian ('.$this->uri->segment(4).')'); //nama sheet
                        } else {
                            $objPHPExcel->getActiveSheet()->setTitle('Hasil Ujian (Semua Kelas)'); //nama sheet
                        }
                    }
                    //mengisi konten di sheet ke 2
                    else if($sheet == 1){

                        $objPHPExcel->createSheet();
                        $objset = $objPHPExcel->setActiveSheetIndex($sheet); //inisiasi set object
                        $objget = $objPHPExcel->getActiveSheet();  //inisiasi get object
             
                        $objget->setTitle('Detil KD dan Indikator Soal'); //sheet title

                        //table header
                        $cols = array("A","B","C","D");
                        $val = array("BUTIR SOAL","ID SOAL","KOMPETENSI DASAR","INDIKATOR"); 
                        for ($a=0;$a<4; $a++){
                            $objset->setCellValue($cols[$a].'1', $val[$a]);
                             
                            //Setting lebar cell
                            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10); // Butir Soal
                            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10); // ID Soal
                            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(60); // KD
                            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(60); // Indikator
                         
                            $style = array(
                                'alignment' => array(
                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                ),
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => '92d050')
                                ),
                                'font' => array(
                                    'color' => array('rgb' => '000000')
                                )
                            );
                            $objPHPExcel->getActiveSheet()->getStyle($cols[$a].'1')->applyFromArray($style);
                        }

                        //mengisi konten di baris ke 2 dan seterusnya
                        $list_soal = $ujian->list_soal;
                        //get id soal
                        $id_soal = array();
                        $data_soal  = explode(",", $list_soal);
                        for ($i = 0; $i < count($data_soal); $i++) {
                            $pecah_soal = explode(":",$data_soal[$i]);
                            $id_soal[$i] = $pecah_soal[1];
                        }
                        //sort id soal
                        for($z=0;$z<count($id_soal);$z++){
                            for($x=$z+1;$x<count($id_soal);$x++){
                                if($id_soal[$z] > $id_soal[$x]){
                                    $temp = $id_soal[$x];
                                    $id_soal[$x] = $id_soal[$z];
                                    $id_soal[$z] = $temp;
                                }
                            }
                        }

                        //mencari detil soal
                        $baris = 2;
                        for($e=0;$e<count($id_soal);$e++){
                            $detil_soal = $this->analisa->get_data_soal($id_soal[$e]);

                            $objset->setCellValue('A'.$baris, $e+1);
                            $objset->setCellValue('B'.$baris, $id_soal[$e]);
                            $objset->setCellValue('C'.$baris, $detil_soal->kd);
                            $objset->setCellValue('D'.$baris, $detil_soal->indikator);

                            $baris++;
                        }

                    }

                }
                

                $nama_ujian = str_replace(' ', '_', $this->session->userdata('ujian_sekarang'));
                if($this->uri->segment(4) != NULL){
                    $filename = 'HASIL_'.$nama_ujian.'_(KELAS-'.$this->uri->segment(4).').xls'; //nama file
                } else {
                    $filename = 'HASIL_'.$nama_ujian.'_(SEMUA-KELAS).xls'; //nama file
                }

                $objPHPExcel->setActiveSheetIndex(0);

                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
     
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');                
                $objWriter->save('php://output');
            }else{
                redirect('analisa');
            }
        } else {
            redirect('login');
        }
    }

    public function reset_ujian(){
        if ($this->session->userdata('login') == TRUE){

            if($this->analisa->reset_ujian($this->uri->segment(4)) == TRUE){
                $this->session->set_flashdata('pesan', 'Reset ujian berhasil!');
                redirect(base_url('analisa/lihat/'.$this->uri->segment(3)));
            } else {
                redirect(base_url('analisa/lihat/'.$this->uri->segment(3)));
            }
        } else {
            redirect('login');
        }
    }

    public function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

}
/* End of file ujian.php */
/* Location: ./application/controllers/analisa.php */