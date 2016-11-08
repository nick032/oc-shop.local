<?php echo $header; echo $column_left ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-html" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if (isset($error_warning)) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <input type="file" name="file">
                <button class="btn btn-primary load">Загрузить</button>
                <div class="test"></div>
                <div class="preloader"></div>
            </div>
        </div>
    </div>
<script type="text/javascript">

    $(document).ready(function(){

        $(document).on('click', '.select-field', function(){
            var attr = $(this).attr('name');
        });
        var files;
        $('input[type=file]').change(function() {
            files = this.files;
        });
        $('.load').click(function(e){
            e.preventDefault();
            $this = $(this);
            var data = new FormData();
            $.each( files, function( key, value ){
                data.append( key, value );
            });
            $.ajax({
                type: 'POST',
                data: data,
                url: '<?=$ajax_action?>' + '&' + '<?=$token?>',
                /*dataType: 'json',*/
                processData: false,
                contentType: false,
                beforeSend: function(){
                    $this.fadeOut();
                    $('.preloader').html('Идет загрузка...');
                },
                success: function(res){
                    $('.preloader').html('');
                    $this.fadeIn();
                    $('.test').html(res);
                },
                error: function(jqXHR, testStatus, errorThrow){
                    console.log('Ошибка AJAX запроса: ' + testStatus);
                }
            });
        });
    });
</script>
</div>
<?php echo $footer; ?>