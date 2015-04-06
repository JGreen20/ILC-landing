<?php echo doctype('html5'); ?>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <!--meta name="viewport" content="width=device-width, initial-scale=1.0" /-->
        <meta name="description" content="<?php echo $this->config->item('cms_site_desc'); ?>" />
        <meta name="author" content="" />
        <!-- <link rel="shortcut icon" href="<?php //echo base_url(); ?>assets/images/ico/favicon.png" /> -->

        <title><?php echo (isset($_title)) ? $_title . ' | ' : ''; ?><?php echo $this->config->item('cms_site_name'); ?></title>

        <!-- Font awesome -->
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />

        <!-- Bootstrap core CSS -->
        <?php echo $_css; ?>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="fb-root"></div>

        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '640313119434682',
                    xfbml      : true,
                    version    : 'v2.1'
                });

                FB.Canvas.setSize({ height: 810 });
            };

            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                 js.src = "//connect.facebook.net/en_US/sdk.js";
                 fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>


        <div id="spin"></div><!-- end #spin -->

        <div class="page-wrapper">
            <?php foreach($_content as $_view): ?>
                <?php include $_view;?>
            <?php endforeach; ?>
        </div> <!-- /.page-wrapper -->

        <!-- Modal Fomulario de Solicitud -->
        <div class="modal fade modal-register" id="frmRegister" tabindex="-1" role="dialog" aria-labelledby="frm_register_label" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-style">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="frm_register_label">Formulario de solicitud</h4>
                    </div> <!-- /modal-header -->
                    <div class="modal-body">
                        <?php echo form_open('', array('id' => 'js-frm-register', 'class' => 'form-horizontal'), array('token' => $_token)); ?>
                        <!-- Name -->
                        <div class="form-group">
                            <label for="customer_name" class="col-xs-4 control-label">Nombres:</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="" />
                            </div> <!--/col-xs-8 -->
                        </div> <!-- /form-group name -->

                        <div class="form-group">
                            <label for="customer_lastname" class="col-xs-4 control-label">Apellidos:</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" name="customer_lastname" id="customer_lastname" placeholder="" />
                            </div> <!--/col-xs-8 -->
                        </div> <!-- /form-group lastname -->
                        <div class="form-group">
                            <label for="customer_dni" class="col-xs-4 control-label">DNI:</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" name="customer_dni" id="customer_dni" placeholder="" />
                            </div> <!--/col-xs-8 -->
                        </div> <!-- /form-group dni-->
                        <div class="form-group">
                            <label for="customer_email" class="col-xs-4 control-label">Email:</label>
                            <div class="col-xs-8">
                                <input type="email" class="form-control" name="customer_email" id="customer_email" placeholder="" />
                            </div> <!--/col-xs-8 -->
                        </div> <!-- /form-group email-->
                        <div class="form-group">
                            <label for="customer_phone" class="col-xs-4 control-label">Teléfono:</label>
                            <div class="col-xs-8">
                                <input type="text" class="form-control" name="customer_phone" id="customer_phone" placeholder="" />
                            </div> <!--/col-xs-8 -->
                        </div> <!-- /form-group tel-->
                        <div class="form-group">
                            <label for="sl-credito" class="col-xs-4 control-label">Crédito de interés:</label>
                            <div class="col-xs-8">
                                <select name="sl-credito" id="sl-credito">
                                    <?php foreach ($_cdtos as $cdto) : ?>
                                        <option value="<?php echo $cdto->id ?>"><?php echo $cdto->name; ?></option>
                                    <?php endforeach; ?>
                                </select> <!-- /select -->
                            </div> <!-- col-xs-8 -->
                        </div> <!--/form-group select credito-->

                        <?php if (isset($_dptos)) : ?>
                                <div class="form-group">
                                    <label for="sl-ciudad" class="col-xs-4 control-label">Departamento:</label>
                                    <div class="col-xs-8">
                                        <select name="sl-ciudad" id="sl-ciudad">
                                            <?php foreach ($_dptos as $dp) : ?>
                                                <option value="<?php echo $dp->id ?>"><?php echo $dp->name; ?></option>
                                            <?php endforeach; ?>
                                        </select> <!-- /select -->
                                    </div> <!-- col-xs-8 -->
                                </div> <!--/form-group select ciudad-->
                        <?php endif; ?>

                        <!-- Submit Button -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Enviar</a>
                        </div>

                        <?php echo form_close(); ?>
                    </div> <!--/modal-body  -->
                </div> <!--/modal-content  -->
            </div> <!--/modal-dialog  -->
        </div> <!-- /modal -->

        <!-- Load Javascript -->
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>assets/scripts/libraries/jquery/jquery-1.11.0.min.js"><\/script>')</script>
		<script> var _root_ = '<?php echo base_url(); ?>'</script>
        <script type="text/javascript" src="https://fgnass.github.io/spin.js/spin.min.js"></script>
        <?php echo $_js; ?>
    </body>
</html>