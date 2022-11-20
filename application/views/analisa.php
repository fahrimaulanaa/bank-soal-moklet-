<div class="col-md-12">
    <div class="panel panel-danger">
        <div class="panel-heading">Analisa Hasil Ujian</div>
        <div class="panel-body">
        	<p class="alert alert-warning">Tombol <b>Publish</b> digunakan agar siswa dapat melihat analisis hasil ujian. Pastikan anda klik <b>Publish</b> ketika semua siswa sudah melakukan ujian.</p>
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

			<!-- tabel data start -->
			<?php if (! empty($tabel_data)) : ?>
			    <?php echo $tabel_data; ?>
			<?php endif ?>
			<!-- tabel data end -->

			<!-- tabel data start -->
			<?php if (! empty($pagination)) : ?>
			    <?php echo $pagination; ?>
			<?php endif ?>
			<!-- tabel data end -->

		</div>
	</div>
</div>


<?php
/* End of file kelas.php */
/* Location: ./application/views/kelas/kelas.php */
?>