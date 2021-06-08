<main class="b-section__main <?php if($addr=="/") echo 'is--frontpage'; ?>">
	<div class="b-section__bunner">
		<div class="container">
			<jdoc:include type="modules" name="after-search-panel" />
		</div>
	</div>

	<?php if($addr=='/') { ?>
		<jdoc:include type="modules" name="top-slider" />
		<?php
	} else { ?>
		<section class="section">
			<div class="container">
				<jdoc:include type="modules" name="breadcrumbs" />

				<jdoc:include type="modules" name="before_component" />
				<jdoc:include type="component" />
				<jdoc:include type="modules" name="after_component" />

			</div>
		</section>
	<?php } ?>

	<!-- FREE ATTR (ITEMS) -->
	<jdoc:include type="modules" name="free-attr-items" />
	<!-- // FREE ATTR (ITEMS) -->

	<!-- 5blocks -->
	<jdoc:include type="modules" name="blocks-5" />
	<!-- // 5blocks -->

	<!-- LABEL PRODUCTS -->
	<jdoc:include type="modules" name="label-products" />
	<!-- // LABEL PRODUCTS -->


	<!-- LAST NEWS -->
	<jdoc:include type="modules" name="last-news" />
	<!-- // LAST NEWS -->


	<!-- VIDEO -->
	<jdoc:include type="modules" name="video-review" />
	<!-- // VIDEO -->

	<!-- BRANDS -->
	<jdoc:include type="modules" name="brands" />
	<!-- // VIDEO -->

	<!-- consult -->
	<div class="b-delivery-main">
		<jdoc:include type="modules" name="block-consult" />
	</div>
	<!-- // consult -->

	<jdoc:include type="modules" name="block-entry" />
	<jdoc:include type="modules" name="block-parthners" />
	<jdoc:include type="modules" name="block-serts" />
	<jdoc:include type="modules" name="block-last-look" />

	<jdoc:include type="modules" name="block-3d" />
</main>