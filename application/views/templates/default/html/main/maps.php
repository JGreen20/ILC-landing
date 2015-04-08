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
				<li>
					<a class="js-links page-section__nav__item bg-circle-purple text-center js-links" href="main/getVehicular" data-line="purple">
						<img src="<?php echo base_url(); ?>assets/images/vehicular.png" alt="" class="main-nav__producto__img  img-responsive" />
						Garantía de <span class="text-bold  text-uppercase">vehicular</span>
					</a>
				</li>
			</ul> <!-- /page-section__nav__items -->
		</aside><!-- end topbar -->

		<main class="content">
			<div class="form-group frm_group">
				<label for="agencia" class="sr-only frm_label">Agencia</label>
				<select class="form-control frm_select" name="agencia" id="agencia">
					<option value="0">-- Seleccione un departamento o ciudad --</option>
				<?php foreach ($ciudades as $ciudad) : ?>
					<?php
						$selected = ($city === $ciudad->id) ? 'selected="selected"' : '';
					?>
					<option value="<?php echo $ciudad->id ?>" <?php echo $selected; ?>><?php echo $ciudad->ciudad; ?></option>
				<?php endforeach; ?>
				</select>
			</div>

			<section class="content__agencias">
			<?php if (isset($agencias)) : ?>

				<h2 class="text-center content__agencias__title text-uppercase">Agencias de <?php echo $nameCity->ciudad; ?></h2>

				<?php foreach ($agencias as $agencia) : ?>
					<article class="content__agencias__item">
						<span class="content__agencias__item__icon text-hide">Icono</span>
						<h3 class="text-center content__agencias__item__title"><?php echo $agencia->name; ?></h3>
						<p class="text-center content__agencias__item__content"><?php echo $agencia->address; ?></p>
						<p class="text-center content__agencias__item__content">Central Telefónica: <?php echo $agencia->phone; ?></p>
					</article><!-- end content__agencias__item -->
				<?php endforeach; ?>

				<nav class="content__pagination text-center">
				<?php if (isset($_pagination) && strlen($_pagination)) : ?>
					<?php echo $_pagination; ?>
				<?php endif; ?>
				</nav><!-- end main__grid__footer__pagination -->

			<?php else : ?>

				<p class="content__intro text-center">Estamos ubicados estrategicamente en varias ciudades a nivel nacional, con la finalidad de estar cerca cuando nos necesites, contando así con más de 70 agencias y puntos de atención. Selecciona un departamento o ciudad y  podrás ver las agencias que tenemos en esa localidad. Inversiones La Cruz siempre a tu servicio.</p>

			<?php endif; ?>
			</section><!-- end content__agencias -->

			<h1 class="content__logo text-center">
				<img class="img-responsive" src="<?php echo base_url(); ?>assets/images/logo.png" alt="Inversiones La Cruz" />
			</h1>
		</main><!-- end content -->
	</section> <!-- / page section -->

	<ul class="bar-color">
		<li class="bar-color__item bar-color__item--joyas text-hide">Joyas</li>
		<li class="bar-color__item bar-color__item--prendatodo text-hide">Prendatodo</li>
		<li class="bar-color__item bar-color__item--vehicular text-hide">Vehicular</li>
	</ul><!-- end bar-color -->