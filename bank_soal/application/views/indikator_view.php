<?php
$form = array(
    'id_indikator' => array(
        'name'=>'id_indikator',
        'size'=>'30',
        'class'=>'form_field form-control',
        'value'=>set_value('id_indikator', isset($form_value['id_indikator']) ? $form_value['id_indikator'] : '')
    ),
    'id_mp' => array(
        'name'=>'id_mp',
        'size'=>'30',
        'class'=>'form_field form-control',
        'value'=>set_value('id_mp', isset($form_value['id_mp']) ? $form_value['id_mp'] : '')
    ),
    'indikator'    => array(
        'name'=>'indikator',
        'size'=>'100',
        'class'=>'form_field form-control',
        'value'=>set_value('indikator', isset($form_value['indikator']) ? $form_value['indikator'] : '')
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
        <div class="panel-heading">Tambah Indikator</div>
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
                    <?php //echo form_label('Kode indikator', 'id_indikator'); ?>
                    <?php //echo form_input($form['id_indikator']); ?>
                </p-->
                <p>
                    <?php echo form_label('Kompetensi Dasar', 'id_kd'); ?>
                    <?php echo form_dropdown('id_kd', $option_kd, set_value('id_kd', isset($form_value['id_kd']) ? $form_value['id_kd'] : ''),'class="form-control"'); ?>
                </p>
            <?php echo form_error('id_kd', '<p class="field_error">', '</p>');?>

                <p>
                    <?php echo form_label('Nama indikator', 'indikator'); ?>
                    <?php echo form_input($form['indikator']); ?>
                </p>
            <?php echo form_error('indikator', '<p class="field_error">', '</p>');?>

                <p>
                    <?php echo form_submit($form['submit']); ?>
                    <?php echo anchor('indikator','Batal', array('class' => 'btn btn-danger')) ?>
                </p>
            <?php echo form_close(); ?>
                <!-- form end -->
        </div>
    </div>
</div>

<?php
/* End of file indikator_form.php */
/* Location: ./application/views/indikator/indikator_form.php */
?>