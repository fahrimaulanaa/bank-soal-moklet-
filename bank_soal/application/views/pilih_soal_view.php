<?php 
$form = array(
    'submit'   => array(
        'name'=>'submit',
        'class'=>'btn btn-primary submit',
        'value'=>'Simpan'
    )
);
?>

<div class="col-md-12">
    <div class="panel panel-danger">
        <div class="panel-heading">Pilih Soal Ujian</div>
        <div class="panel-body">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <div class="alert alert-info">
                        <p>
                            <strong>Petunjuk Pengisian !</strong><br> 
                            <ol style="list-style: initial;padding: 0 30px;list-style-type: decimal;">
                                <li>Berikan tanda centang ( <i class="glyphicon glyphicon-ok"></i> ) pada soal yang akan diujikan</li>
                                <li>Pastikan jumlah soal yang tercentang sama dengan jumlah soal yang akan diujikan</li>
                                <li><strong>Simpan</strong></li>
                                <br>
                            </ol>
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="alert alert-warning" style="text-align: center">
                    <strong>JUMLAH SOAL TERPILIH :</strong><br>
                    <span id="jml-soal"><?php if(isset($jml_soal_terpilih)){ echo $jml_soal_terpilih;}else{echo '0';} ?></span><br>
                    <span id="soal-maks">*) Jumlah soal yang dipilih harus <?php echo $jumlah_soal; ?> soal</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php if (! empty($pesan)) : ?>

                <div class="alert alert-danger">
                    <?php echo $pesan; ?>
                </div>
            <?php endif ?>
            </div>

            <!-- tabel data start -->
            <?php echo form_open($form_action, 'id="form-soal"')?>
                <?php if (!empty($tabel_data)) : ?>
                    <?php echo $tabel_data; ?>
                <?php endif ?>
                <?php //echo $pagination; ?>
                
                <div class="col-md-6 col-lg-6 col-xs-6 col-sm-6" style="text-align: right;padding: 0 !important;">
                    <?php echo form_submit($form['submit']); ?>
                    <?php echo anchor('ujian','Batal', array('class' => 'btn btn-danger')) ?>
                </div>
            <?php echo form_close(); ?>
            <!-- tabel data end -->
        </div>
    </div>
</div>

<script>  
 $(document).ready(function(){  
    $('.submit').click(function(){            
       $.ajax({  
            url:"ujian/pilih_soal/<?php echo $this->uri->segment(3); ?>",
            type:"POST",
            data:$('#form-soal').serialize(),
            cache: false,
            success:function(data)  
            {  
                window.location.href('ujian/');
                $('#form-soal')[0].reset();  
            }  
       });  
    });

    var $checkboxes = $('#form-soal input[type="checkbox"]');
    
    $checkboxes.change(function(){
        var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
        $('#jml-soal').text(countCheckedCheckboxes);
    });
 });  
</script>
<script type="text/javascript" src="<?php echo base_url('asset/js/paging_soal.js'); ?>"></script> 
<script type="text/javascript">
    $(document).ready(function() {
        $('.datatable').paging({limit:10});
    });
</script>