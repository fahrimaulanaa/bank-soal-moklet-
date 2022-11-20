<?php
$form = array(
    'id_kd' => array(
        'name'=>'id_kd',
        'size'=>'30',
        'class'=>'form_field form-control',
        'value'=>set_value('id_kd', isset($form_value['id_kd']) ? $form_value['id_kd'] : '')
    ),
    'id_mp' => array(
        'name'=>'id_mp',
        'size'=>'30',
        'class'=>'form_field form-control',
        'value'=>set_value('id_mp', isset($form_value['id_mp']) ? $form_value['id_mp'] : '')
    ),
    'kd'    => array(
        'name'=>'kd',
        'size'=>'100',
        'class'=>'form_field form-control',
        'value'=>set_value('kd', isset($form_value['kd']) ? $form_value['kd'] : '')
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
        <div class="panel-heading">Tambah Kompetensi Dasar (KD)</div>
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
                    <?php //echo form_label('Kode kd', 'id_kd'); ?>
                    <?php //echo form_input($form['id_kd']); ?>
                </p-->
            <?php echo form_error('id_kd', '<p class="field_error">', '</p>');?>

                <p>
                    <?php echo form_label('Nama KD', 'kd'); ?>
                    <?php echo form_input($form['kd']); ?>
                </p>
            <?php echo form_error('kd', '<p class="field_error">', '</p>');?>

                <p>
                    <?php echo form_submit($form['submit']); ?>
                    <?php echo anchor('kd','Batal', array('class' => 'btn btn-danger')) ?>
                </p>
            <?php echo form_close(); ?>
                <!-- form end -->
        </div>
    </div>
</div>

<?php
/* End of file kd_form.php */
/* Location: ./application/views/kd/kd_form.php */
?>