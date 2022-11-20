<?php
$form = array(
    'id_ujian' => array(
        'name'=>'id_ujian',
        'size'=>'30',
        'class'=>'form_field',
        'value'=>set_value('id_ujian', isset($form_value['id_ujian']) ? $form_value['id_ujian'] : '')
    ),
    'id_mp' => array(
        'name'=>'id_mp',
        'size'=>'30',
        'class'=>'form_field',
        'value'=>set_value('id_mp', isset($form_value['id_mp']) ? $form_value['id_mp'] : '')
    ),
    'ujian'    => array(
        'name'=>'ujian',
        'size'=>'100',
        'class'=>'form_field form-control',
        'placeholder'=>'Nama Ujian',
        'value'=>set_value('ujian', isset($form_value['ujian']) ? $form_value['ujian'] : '')
    ),
    'jumlah_soal'    => array(
        'name'=>'jumlah_soal',
        'size'=>'10',
        'class'=>'form_field form-control',
        'placeholder'=>'0',
        //isset($form_value['jumlah_soal']) ? 'disabled' : ''=> 'disabled',
        'value'=>set_value('jumlah_soal', isset($form_value['jumlah_soal']) ? $form_value['jumlah_soal'] : '')
    ),
    'submit'   => array(
        'name'=>'submit',
        'class'=>'btn btn-primary',
        'value'=>'Simpan'
    )
);
?>

<div class="col-md-12">
    <div class="panel panel-danger">
        <div class="panel-heading">Tambah Ujian</div>
        <div class="panel-body">

                <!-- pesan start -->
            <?php $flash_pesan = $this->session->flashdata('pesan')?>
            <?php if (! empty($flash_pesan)) : ?>
                <div class="alert alert-warning">
                    <?php echo $flash_pesan; ?>
                </div>
            <?php endif ?>
                <!-- pesan end -->

                <!-- form start -->
            <?php echo form_open($form_action); ?>
                <!--p>
                    <?php //echo form_label('Kode ujian', 'id_ujian'); ?>
                    <?php //echo form_input($form['id_ujian']); ?>
                </p-->
                <p>
                    <?php echo form_label('Nama Ujian', 'ujian'); ?>
                    <?php echo form_input($form['ujian']); ?>
                </p>
            <?php echo form_error('ujian', '<p class="field_error">', '</p>');?>

                <p>
                    <?php echo form_label('Jumlah Soal', 'jumlah_soal'); ?>
                    <?php echo form_input($form['jumlah_soal']); ?>
                </p>
            <?php echo form_error('jumlah_soal', '<p class="field_error">', '</p>');?>

                <p>
                    <?php echo form_submit($form['submit']); ?>
                    <?php echo anchor('ujian','Batal', array('class' => 'btn btn-danger')) ?>
                </p>
            <?php echo form_close(); ?>
                <!-- form end -->
        </div>
    </div>
</div>

<?php
/* End of file ujian_form.php */
/* Location: ./application/views/ujian/ujian_form.php */
?>