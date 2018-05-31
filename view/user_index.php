<?php if (!empty($files)) : ?>
	<?php foreach ($files as $file) : ?>
		<div class="object">
			<div class="item">
				<?php $extension = pathinfo($file->path,PATHINFO_EXTENSION);
				switch ($extension) {
					case 'png':
					echo '<div class="item-type"><img src="/'.$file->path.'" width="100"></div>';
					break;

					case 'jpg':
					echo '<div class="item-type"><img src="/'.$file->path.'" width="100"></div>';
					break;

					case '' :
					//folder
					$name = $file->name;
					echo '<div class="item-type"><a href="/user/Folder?name='.$name.'"> FOLDER </a></div>';
					break;

					default:
					echo '<div class="item-type">.'.$extension.'</div>';
					break;
				}
				?>
				<div class="item-desc">
					<div class="item-desc-name"><?= $file->name ?></div>
					<div class="item-desc-icon"><a class="props glyphicon glyphicon-cog" href="/user/fileprops?id=<?= $file->id ?>"></a></div>
				</div>
			</div>
			<?php if (!empty($file->tags)) : ?>
				<div class="tag">
					<div class="tag-text"><?= $file->tags ?></div>
					<div class="tag-icon"><a href="/user/delTag?id=<?= $file->id ?>">&#10005;</a></div>
				</div>
			<?php endif ?>
		</div>
	<?php endforeach ?>
<?php endif ?>
<div class="object">
	<?php
		if (isset($folderName)) {
	  	echo "<div class='plus' onclick='location=\"/user/upload?folderName=$folderName\"'>+</div>";
		} else {
			echo "<div class='plus' onclick='location=\"/user/upload\"'>+</div>";
		} ?>
</div>
