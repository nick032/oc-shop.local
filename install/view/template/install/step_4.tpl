<?php echo $header; ?>
<div class="container">
  <header>
    <div class="row">
      <div class="col-sm-6">
        <h1 class="pull-left">4<small>/4</small></h1>
        <h3><?php echo $heading_title; ?><br>
          <small><?php echo $text_step_4; ?></small></h3>
      </div>
      <div class="col-sm-6">
        <div id="logo" class="pull-right hidden-xs"><img src="view/image/logo.png" alt="OpenCart" title="OpenCart" /></div>
      </div>
    </div>
  </header>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $text_success; ?></div>
  <?php } ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <div class="visit">
    <div class="row">
      <div class="col-sm-5 col-sm-offset-1 text-center">
        <p><i class="fa fa-shopping-cart fa-5x"></i></p>
        <a href="../" class="btn btn-secondary"><?php echo $text_catalog; ?></a></div>
      <div class="col-sm-5 text-center">
        <p><i class="fa fa-cog fa-5x white"></i></p>
        <a href="../admin/" class="btn btn-secondary"><?php echo $text_admin; ?></a></div>
    </div>
  </div>



  <div class="support text-center">
    <div class="row">
      <div class="col-sm-4"><a href="http://opencart-russia.ru" class="icon transition"><i class="fa fa-cloud fa-4x"></i></a>
        <h3><?php echo $text_facebook; ?></h3>
        <p><?php echo $text_facebook_description; ?></p>
        <a href="http://opencart-russia.ru"><?php echo $text_facebook_visit; ?></a></div>
      <div class="col-sm-4"><a href="http://forum.opencart-russia.ru" class="icon transition"><i class="fa fa-comments fa-4x"></i></a>
        <h3><?php echo $text_forum; ?></h3>
        <p><?php echo $text_forum_description; ?></p>
        <a href="http://forum.opencart-russia.ru"><?php echo $text_forum_visit; ?></a></div>
      <div class="col-sm-4"><a href="http://forum.opencart-russia.ru/forums/poisk-ispolnitelei/" class="icon transition"><i class="fa fa-user fa-4x"></i></a>
        <h3><?php echo $text_commercial; ?></h3>
        <p><?php echo $text_commercial_description; ?></p>
        <a href="http://forum.opencart-russia.ru/forums/poisk-ispolnitelei/" target="_BLANK"><?php echo $text_commercial_visit; ?></a></div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 
