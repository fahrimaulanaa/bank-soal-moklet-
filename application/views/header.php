<nav class="navbar navbar-findcond navbar-fixed-top">
    <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand"><span id="header-title">Bank Soal</span> | <span style="font-weight: 300;">SMK TELKOM Malang</span></a>
    </div>
    <div class="collapse navbar-collapse" id="navbar">
      <ul class="nav navbar-nav navbar-right">
        <li class="user"><i class="glyphicon glyphicon-user"></i> <?php echo strtoupper($this->session->userdata('username')); ?> | <?php echo $this->session->userdata('mapel'); ?></li>
        <li>
          <?php echo anchor('login/logout', '<i class="glyphicon glyphicon-log-out"></i> Logout', array('onclick' => "return confirm('Anda yakin akan logout?')"));?>
        </li>
      </ul>
    </div>
  </div>
</nav>