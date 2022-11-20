<?php
$form = array(
    'id_soal' => array(
        'name'=>'id_soal',
        'size'=>'30',
        'class'=>'form_field form-control',
        'value'=>set_value('id_soal', isset($form_value['id_soal']) ? $form_value['id_soal'] : '')
    ),
    'id_indikator' => array(
        'name'=>'id_indikator',
        'size'=>'30',
        'class'=>'form_field form-control',
        'value'=>set_value('id_indikator', isset($form_value['id_indikator']) ? $form_value['id_indikator'] : '')
    ),
    'sulit' => array(
        'name'=>'sulit',
        'size'=>'30',
        'class'=>'form_field form-control',
        'value'=>set_value('sulit', isset($form_value['sulit']) ? $form_value['sulit'] : '')
    ),
    'jawaban' => array(
        'name'=>'jawaban',
        'size'=>'30',
        'class'=>'form_field form-control',
        'value'=>set_value('jawaban', isset($form_value['jawaban']) ? $form_value['jawaban'] : '')
    ),
//    'soal'    => array(
//        'name'=>'soal',
//        'size'=>'1000',
//        'class'=>'form_field',
//        'value'=>set_value('soal', isset($form_value['soal']) ? $form_value['soal'] : '')
//    ),
    'soal' => array(
        'name'        => 'soal',
        'id'          => 'txt_area',
        'value'       => '',
        'rows'        => '15',
        'cols'        => '50',
        'style'       => 'width:70%',
        'class'       => 'form-control',
        'value'=>set_value('soal', isset($form_value['soal']) ? $form_value['soal'] : '')
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
        <div class="panel-heading">Tambah Soal</div>
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
                <p>
                    <?php echo form_label('Indikator', 'id_indikator'); ?>
                    <?php echo form_dropdown('id_indikator', $option_indikator, set_value('id_indikator', isset($form_value['id_indikator']) ? $form_value['id_indikator'] : ''), 'class="form-control"'); ?>
                </p>
                <?php echo form_error('id_indikator', '<p class="field_error">', '</p>');?>

                <p>
                    <?php echo form_label('Tk. Kesulitan', 'sulit'); ?>
                    <?php echo form_dropdown('sulit', $option_sulit, set_value('sulit', isset($form_value['sulit']) ? $form_value['sulit'] : ''),'class="form-control"');?>
                </p>
                <?php echo form_error('sulit', '<p class="field_error">', '</p>');?>

                <p>
                    <?php echo form_label('Jawaban', 'jawaban'); ?>
                    <?php echo form_dropdown('jawaban', $option_jawaban, set_value('jawaban', isset($form_value['jawaban']) ? $form_value['jawaban'] : ''), 'class="form-control"'); ?>
                </p>
                <?php echo form_error('id_indikator', '<p class="field_error">', '</p>');?>

                <p>
                    <?php echo form_label('Soal', 'soal'); ?>
                    <?php echo form_textarea($form['soal']);?>
                </p>
            <?php echo form_error('soal', '<p class="field_error">', '</p>');?>

                <p>
                    <?php echo form_submit($form['submit']); ?>
                    <?php echo anchor('entry','Batal', array('class' => 'btn btn-danger')) ?>
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