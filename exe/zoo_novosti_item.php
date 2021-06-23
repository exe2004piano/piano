<div class="item">
	<a href="<?=$link;?>" class="news-card news-card--second">
		<div class="news-card__top">
			<h2><?=$name;?></h2>
		</div>

		<div class="news-card__middle">
			<img src="<?php echo $img;?>" alt="" role="presentation">
		</div>

		<div class="news-card__bottom">
			<div class="news-card__wrap">
				<div class="news-card__icon">
					<svg class="eye-icon">
						<use class="eye-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#eye"></use>
					</svg>
				</div>

				<span><?=$hits;?></span>
			</div>

			<div class="news-card__wrap">
				<div class="news-card__icon">
					<svg class="time-icon">
						<use class="time-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#time"></use>
					</svg>
				</div>

				<span><?=$time_to_read;?> мин</span>
			</div>
		</div>
	</a>
</div>