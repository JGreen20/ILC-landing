	<aside class="topbar">
	</aside>

	<section class="page-section">
		<header class="main-header">
			<h1 class="main-header__logo text-center">
				<a href="index.php">
					<img src="<?php echo base_url(); ?>assets/images/logo.png" class="img-responsive" alt="Inversiones La Cruz">
				</a>
			</h1> <!-- /.main-header__logo -->

			<h2 class="main-header__title text-center text-uppercase">
				modalidades <span class="text-bold"> de créditos</span>
			</h2> <!-- /.main-header__title -->

			<nav class="main-nav row text-center">
				<ul class="list-inline">
					<li class="col-xs-4">
						<a class="bg-circle-yellow  main-nav__producto js-links" href="main/getJoyas" data-line="yellow" data-index="1">
							Créditos con Garantía en<span class="text-uppercase main-nav__producto--textmedium  block">joyas de oro</span>
							<img src="<?php echo base_url(); ?>assets/images/joya.png" alt="" class="main-nav__producto__img  img-responsive" />
						</a><!-- /main-nav__producto -->
					</li><!-- /col-xs-4 -->
					<li class="col-xs-4">
						<a class="bg-circle-skyblue  main-nav__producto js-links" href="main/getPrendatodo" data-line="skyblue" data-index="2">
							<img src="<?php echo base_url(); ?>assets/images/prendatodo.png" alt="" class="main-nav__producto__img  img-responsive" />
							Créditos con Garantía en<span class="text-uppercase main-nav__producto--textmedium  block">artículos</span>
						</a><!-- /main-nav__producto -->
					</li><!-- /col-xs-4 -->
					<li class="col-xs-4">
						<a class="bg-circle-purple  main-nav__producto js-links" href="main/getVehicular" data-line="purple" data-index="3">
							<img src="<?php echo base_url(); ?>assets/images/vehicular.png" alt="" class="main-nav__producto__img  img-responsive" />
							Créditos con Garantía<span class="text-uppercase main-nav__producto--textmedium  block">vehicular</span>
						</a> <!-- /main-nav__producto -->
					</li><!-- /col-xs-4 -->
				</ul> <!-- / ul-->
			</nav> <!-- /.main-nav -->
		</header> <!-- /.main-header -->
	</section> <!-- / page section -->

	<ul class="bar-color">
		<li class="bar-color__item bar-color__item--joyas text-hide">Joyas</li>
		<li class="bar-color__item bar-color__item--prendatodo text-hide">Prendatodo</li>
		<li class="bar-color__item bar-color__item--vehicular text-hide">Vehicular</li>
	</ul><!-- end bar-color -->

	<!-- Modal Inicio de aplicacion -->
	<div class="modal fade modal-like" id="js-likeModal" tabindex="-1" role="dialog" aria-labelledby="modalLikeLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">[cerrar]</span></button>
			        <h4 class="modal-title text-center" id="modalLikeLabel">
						<img class="img-responsive" src="<?php echo base_url(); ?>assets/images/logo.png" alt="Inversiones La Cruz" />
					</h4>
	      		</div><!-- end modal-header -->
				<div class="modal-body">
					<p class="text-center">
						<span class="strong">Estás a un clic de conocer nuestros servicios.</span> Si deseas conocernos más y enterarte de nuevas formas de conseguir el préstamo que necesitas síguenos en nuestro fanpage y da clic en el botón <span class="underline">"Me gusta"</span>.
					</p>
				</div> <!--/modal-body  -->
			</div> <!--/modal-content  -->
		</div> <!--/modal-dialog  -->
	</div> <!-- /modal -->