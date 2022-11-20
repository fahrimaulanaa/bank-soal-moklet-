<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="shortcut icon" href="<?php echo base_url('asset/images/favicon.jpg');?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('asset/css/reset.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('asset/css/style.css');?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('asset/css/calendar.css');?>" />
    <script type="text/javascript" src="<?php echo base_url('asset/js/calendar.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url()?>asset/js/jquery-1.12.3.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>asset/js/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url('asset/css/bootstrap.min.css'); ?>" />

    <title><?php echo isset($title) ? $title : ''; ?></title>
</head>

<body id="<?php echo isset($modul) ? $modul : ''; ?>">

    <div id="header">
        <?php $this->load->view('header'); ?>
    </div>

    <?php
        $uri1 = $this->uri->segment(1);
        $uri2 = $this->uri->segment(2);
        $menu = array(
            array("icon"=>"dashboard", "url"=>"welcome", "text"=>"Dashboard"),
            array("icon"=>"star", "url"=>"kd", "text"=>"KD"),
            array("icon"=>"heart", "url"=>"indikator", "text"=>"Indikator"),
            array("icon"=>"list-alt", "url"=>"entry", "text"=>"Soal"),
            array("icon"=>"pencil", "url"=>"ujian", "text"=>"Ujian"),
          );
    ?>
    <div class="container" style="padding-top: 70px;width: 80%;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body navigasi" style="background: #fbfbfb;">
                        <?php 
                        foreach ($menu as $m) {
                            if ($uri1 == $m['url']) {
                              echo '<a href="'.base_url().$m['url'].'" class="btn btn-sq btn-dangerku"><i class="glyphicon glyphicon-'.$m['icon'].' g3x"></i><br> '.$m['text'].' </a>';
                            } else {
                              echo '<a href="'.base_url().$m['url'].'" class="btn btn-sq btn-default"><i class="glyphicon glyphicon-'.$m['icon'].' g3x"></i><br> '.$m['text'].' </a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div id="main" class="col-md-12">
                <?php $this->load->view($main_view);?>
            </div>

        </div>
    </div>

    <div id="footer">
        Copyright &copy;2016 Bank Soal - SMK Telkom Malang
    </div>
    
</body>
</html>