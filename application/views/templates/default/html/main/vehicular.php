<aside class="bar-options">
	<ul>
		<li class="text-center"><a href="main/getMaps" class="bar-options__link bar-options__link--agencia js-links">Agencias</a></li>
		<li class="text-center"><a href="#" class="bar-options__link bar-options__link--share js-share-fb" data-post="post-3">Compartir</a></li>
		<li class="text-center"><a href="#" class="bar-options__link bar-options__link--solicitar" data-toggle="modal" data-target="#frmRegister">Solicitar</a></li>
	</ul>
</aside><!-- end bar-options -->

<section class="page-section">
	<aside class="topbar text-right">
		<a href="main/getIndex" class="topbar__link text-hide js-links">top</a>

		<ul class="page-section__nav__items list-inline text-right">
			<li>
				<a class="js-links page-section__nav__item bg-circle-yellow text-center js-links" href="main/getJoyas" data-line="yellow">
					<img src="<?php echo base_url(); ?>assets/images/joya.png" alt="" class="main-nav__producto__img  img-responsive" />
					Garantía de <span class="text-bold  text-uppercase">artículos</span>
				</a>
			</li>
			<li>
				<a class="js-links page-section__nav__item bg-circle-skyblue text-center js-links" href="main/getPrendatodo" data-line="skyblue">
					<img src="<?php echo base_url(); ?>assets/images/prendatodo.png" alt="" class="main-nav__producto__img  img-responsive" />
					Garantía de <span class="text-bold  text-uppercase">artículos</span>
				</a>
			</li>
		</ul> <!-- /page-section__nav__items -->
	</aside><!-- end topbar -->

	<main class="content">
		<figure class="content__icon content__icon--vehicular text-hide">Vehicular</figure>
		<h2 class="content__title text-center">CRÉDITOS CON <span>GARANTÍA VEHICULAR</span></h2>

		<section class="content__desc">
			<div class="content__desc__text">
				<p>Inversiones La Cruz le brinda la oportunidad de acceder a un crédito seguro dejando en garantía su vehículo. En menos de 48 horas te brindamos el crédito que necesitas.</p>
			</div><!-- end content__desc__text -->
			<figure class="content__desc__video">
				<img class="img-responsive" src="<?php echo base_url(); ?>assets/images/bg-video.png" />
			</figure><!-- end content__desc__video -->
		</section><!-- end content__desc -->

		<section class="content__info">
			<h3 class="content__info__title text-center text-uppercase">¿Como lo hago?</h3>

			<ul class="content__info__list list-inline text-center">
				<li>
					<a class="content__info__list__link" href="#" data-toggle="tooltip" data-placement="bottom" title="Vén con tu Auto a nuestra agencia"><img src="<?php echo base_url(); ?>assets/images/hago-vehiculo.png" class="img-responsive" /></a>
				</li>
				<li>
					<a class="content__info__list__link" href="#" data-toggle="tooltip" data-placement="bottom" title="Lo evaluaremos y tasaremos"><img src="<?php echo base_url(); ?>assets/images/hago-auto-checklist.png" class="img-responsive" /></a>
				</li>
				<li>
					<a class="content__info__list__link" href="#" data-toggle="tooltip" data-placement="bottom" title="Te damos el dinero de forma inmediata"><img src="<?php echo base_url(); ?>assets/images/hago-bolsa-dinero.png" class="img-responsive" /></a>
				</li>
				<li>
					<a class="content__info__list__link" href="#" data-toggle="tooltip" data-placement="bottom" title="Tu auto quedará seguro en nuestros estacionamientos con vigilancia permanente"><img src="<?php echo base_url(); ?>assets/images/hago-camara.png" class="img-responsive" /></a>
				</li>
			</ul><!-- end content__info__list -->

		</section><!-- end content__info -->
	</main><!-- end content -->

	<script type="text/javascript">
		//ToolTip
	    j('[data-toggle="tooltip"]').tooltip({
	      trigger : 'hover focus',
	      delay   : { "show": 50, "hide": 50 }
	    });
	</script>
</section> <!-- / page section -->

<ul class="bar-color">
	<li class="bar-color__item bar-color__item--joyas text-hide">Joyas</li>
	<li class="bar-color__item bar-color__item--prendatodo text-hide">Prendatodo</li>
	<li class="bar-color__item bar-color__item--vehicular text-hide active">Vehicular</li>
</ul><!-- end bar-color -->