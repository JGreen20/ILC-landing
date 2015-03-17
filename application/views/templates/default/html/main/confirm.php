<section class="wrapper-login text-right">
	<a href="#">Iniciar sesión</a>
</section><!-- end wrapper-login -->

<section id="first-page" class="section-page">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1 class="logo text-center" title="<?php echo $this->config->item('cms_site_name') ?>">
					<img class="img-responsive" src="<?php echo base_url(); ?>assets/images/logo-forget.png" alt="For-Get" />
				</h1><!-- end logo -->
				<p class="text-center">Ingresa tus gustos e intereses</p>
				<h2 class="text-center">Recuerda tus fechas importantes</h2>
				<p class="text-center">cumpleaños, aniversarios, tu primer ascenso y más. Regístrate y tendrás ese plan "B" que siempre te hace falta para cualquiera de tus fechas importantes.</p>

				<p class="text-center">
					<button type="button" class="btn btn-forget text-uppercase" data-toggle="modal" data-target="#modal-register-user">Crear mi cuenta</button>
				</p>

				<a href="#second-page" class="page-scroll link-scroll text-hide">Scroll second page</a>
			</div>
		</div><!-- end row -->
	</div><!-- end container -->
</section><!-- end section-page -->

<!-- Modal Register Password User-->
<div class="modal fade modal-register-password" id="modal-register-password" tabindex="-1" role="dialog" aria-labelledby="modal-register-password-label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
        	<h4 class="modal-title text-center" id="modal-register-password-label">Bienvenido a <strong>For - get</strong></h4>
      	</div>
      	<div class="modal-body">
        	<p>Enhorabuena ahora perteneces a <strong>For-Get</strong>. Ingresa tu nueva contraseña.</p>

			<?php echo form_open('', array('class' => 'register_password', 'role' => 'form'), array('token' => $_token)); ?>
    			<!-- Password -->
				<div class="form-group">
					<label for="user_password" class="sr-only">Nueva contraseña</label>
					<input type="password" class="form-control" name="user_password" id="user_password" placeholder="Nueva contraseña" required />
				</div><!-- end form-group -->

				<!-- Re-Password -->
				<div class="form-group">
					<label for="user_repassword" class="sr-only">Confirma tu contraseña</label>
					<input type="password" class="form-control" name="user_repassword" id="user_repassword" placeholder="Confirma tu contraseña" required />
				</div><!-- end form-group -->

				<input type="hidden" name="user_id" id="user_id" value="<?php echo $_id; ?>">

				<button type="submit" content="Unirme" id="btn_register_password">Guardar</button>
    		<?php echo form_close(); ?>
      	</div>
    </div>
  </div>
</div>