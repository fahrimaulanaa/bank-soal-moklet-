<div class="col-md-12">
    <div class="panel panel-danger">
        <div class="panel-heading">Data Ujian
          <div class="tombol-kanan">
            <a class="btn btn-success btn-sm tombol-kanan" href="ujian/tambah/"><i class="glyphicon glyphicon-plus"></i> &nbsp;&nbsp;Tambah</a>
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

			<!-- tabel data start -->
			<?php if (! empty($tabel_data)) : ?>
			    <?php echo $tabel_data; ?>
			<?php endif ?>
			<!-- tabel data end -->

		</div>
	</div>
</div>


<?php
/* End of file kelas.php */
/* Location: ./application/views/kelas/kelas.php */
?>