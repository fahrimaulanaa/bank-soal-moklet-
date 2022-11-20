<?php
$form = array(
    'id_mp' => array(
        'name'=>'id_mp',
        'size'=>'30',
        'class'=>'form_field form-control',
        'value'=>set_value('id_mp', isset($form_value['id_mp']) ? $form_value['id_mp'] : '')
    ),
    'mp'    => array(
        'name'=>'mp',
        'size'=>'100',
        'class'=>'form_field form-control',
        'value'=>set_value('mp', isset($form_value['mp']) ? $form_value['mp'] : '')
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
        <div class="panel-heading">Tambah Mata Pelajaran</div>
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
                    <?php //echo form_label('Kode mp', 'id_mp'); ?>
                    <?php //echo form_input($form['id_mp']); ?>
                </p-->
            <?php echo form_error('id_mp', '<p class="field_error">', '</p>');?>

                <p>
                    <?php echo form_label('Nama mp', 'mp'); ?>
                    <?php echo form_input($form['mp']); ?>
                </p>
            <?php echo form_error('mp', '<p class="field_error">', '</p>');?>

                <p>
                    <?php echo form_submit($form['submit']); ?>
                    <?php echo anchor('mp','Batal', array('class' => 'btn btn-danger')) ?>
                </p>
            <?php echo form_close(); ?>
                <!-- form end -->
        </div>
    </div>
</div>

<?php
/* End of file mp_form.php */
/* Location: ./application/views/mp/mp_form.php */
?>