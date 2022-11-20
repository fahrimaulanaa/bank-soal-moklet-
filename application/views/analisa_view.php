<div class="col-md-12">
    <div class="panel panel-danger">
        <div class="panel-heading">Analisa Hasil Ujian - <?php echo $this->session->userdata('ujian_sekarang'); ?>
        	<div class="tombol-kanan">
	            <a id="link_ekspor" class="btn btn-success btn-sm tombol-kanan" target="_blank"><i class="glyphicon glyphicon-save"></i> &nbsp;&nbsp;Ekspor Ke Excel (
	            	<?php if($this->uri->segment(4) != NULL){
						echo $this->uri->segment(4);
					} else {
						echo 'Semua Kelas';
					} 
				?> )</a>
            </div>
        </div>
        <div class="panel-body">
        	
			<!-- pesan flash message start -->
			<?php $flash_pesan = $this->session->flashdata('pesan')?>
			<?php if (!empty($flash_pesan)) : ?>
			    <div class="alert alert-success">
			        <?php echo $flash_pesan; ?>
			    </div>
			<?php endif ?>
			<!-- pesan flash message end -->

			<!-- pesan start -->
			<?php if (! empty($pesan)) : ?>
			    <div class="alert alert-success">
			        <?php echo $pesan; ?>
			    </div>
			<?php endif ?>
			<!-- pesan end -->
			<?php
				//prefill dropdown
				if($this->uri->segment(4) != NULL){
					$select_option = $this->uri->segment(4);
				} else {
					$select_option = '';
				}
	        	echo form_dropdown("pilih_kelas", $kelas, $select_option, "id='pilih_kelas' class='form-control col-md-2'")."<br><br>";
	        ?>
			<div style="overflow: auto;">
				<!-- tabel data start -->
				<?php if (! empty($tabel_data)) : ?>
				    <?php echo $tabel_data; ?>
				<?php endif ?>
				<!-- tabel data end -->
				
			</div>
		</div>
	</div>
</div>

<!-- Modal Detil Soal -->
<div class="modal fade" id="modaldetilsoal" role="dialog">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content">
            <div class="panel-default">
                <div class="panel-heading">
                    <h1 class="panel-title page-label">Detil Butir Soal <span id="no-soal"></span></h1>
                </div>
                <div class="panel-body" style="overflow: scroll;height: 450px;">
                    <div class="inner-content">
                        <div class="wysiwyg-content">
                            <h2>Kompetensi Dasar</h2>
                            <p id="kd-soal" style="font-size: 13px"></p>

                            <h2>Indikator</h2>
                            <p id="indikator-soal" style="font-size: 13px"></p>

                            <h2>Soal</h2>
                            <div id="gambar-soal"></div>
                            <div id="suara-soal"></div>
                            <textarea disabled id="soal" style="background: #fff;font-size: 13px;width:100%;height: 200px;overflow: scroll;padding: 5px 10px;">sdsdssdfdsfds</textarea>
                            <p>Kunci Jawaban : <span id="jawabs" class="btn btn-sm btn-success"></span></p>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="text-align: right;margin: 0 -15px;">
                            <button type="submit" id="btnBatal" class="btn btn-danger" data-dismiss="modal">Tutup</button>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	//pilih kelas
	$("#pilih_kelas").change(function() {
		var id_kelas = this.value;
		window.location.assign("<?php echo base_url(); ?>analisa/lihat/<?php echo $this->uri->segment(3); ?>/"+id_kelas);
	});
	//show detil soal
	function detil_soal(id_soal,no_soal){
		$("#no-soal").html('');
		$("#kd-soal").html('');
        $("#indikator-soal").html('');
        $("#gambar-soal").html('');
        $("#suara-soal").html('');
        $("#soal").html('');
        $("#jawabs").html('');
		$.getJSON('<?php echo base_url()?>analisa/detil_soal/'+id_soal, function(obj) {
            $("#no-soal").html(no_soal);
            $("#kd-soal").html(obj.kd_soal);
            $("#indikator-soal").html(obj.indikator_soal);
            if(obj.gambar_soal == 'ada'){
            	$("#gambar-soal").html("<img style='width: 400px;margin-bottom: 10px' src='<?php echo base_url() ?>uploads/"+id_soal+".jpg'>");
            }
            if(obj.suara_soal == 'ada'){
            	$("#suara-soal").html("<audio style='width: 400px;margin-bottom: 10px' controls><source src='<?php echo base_url() ?>uploads/"+id_soal+".mp3' type='audio/mpeg'></audio>");
            }
            $("#soal").html(obj.soal);
            $("#jawabs").html(obj.jawabs);
        });
	    $('#modaldetilsoal').modal('show');
	}

	$(document).ready(function() {
		var param = "<?php if($this->uri->segment(4) != NULL){echo $this->uri->segment(4);} else {echo '';} ?>";
		if(param != ''){
			$("#link_ekspor").attr("href","<?php echo base_url(); ?>analisa/ekspor/<?php echo $this->uri->segment(3); ?>/"+param);
		} else {
			$("#link_ekspor").attr("href","<?php echo base_url(); ?>analisa/ekspor/<?php echo $this->uri->segment(3); ?>/");
		}
		
	});
	
</script>


<?php
/* End of file kelas.php */
/* Location: ./application/views/kelas/kelas.php */
?>