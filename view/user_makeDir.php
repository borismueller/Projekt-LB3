<div class="align-form">
	<?php
	if (isset($folderName)) {
		echo "<form class=\"center-form\" action=\"/user/doMakeDir?folderName=$folderName\" enctype=\"multipart/form-data\" method=\"post\">";
	} else {
		echo "<form class=\"center-form\" action=\"/user/doMakeDir\" enctype=\"multipart/form-data\" method=\"post\">";
	} ?>
		<form class="center-form" action="/user/DoMakeDir" enctype="multipart/form-data" method="post">
      <div>
      <!--TODO LOGO -->
      <div>
				<label class="label-help">?</label><br>
				<input id="" class="input-form" name="name" type="text" placeholder="name">
			</div>
      <div class="submit-form-div">
				<input id="" class="submit-form" name="Submit" type="submit" value="Create">
			</div>
      </div>
			<div class="form-switch-container">
				<?php
				if (isset($folderName)) {
					echo "<a href=\"/user/upload?folderName=$folderName\" class=\"form-switch glyphicon glyphicon-cloud-upload\"></a>";
				} else {
					echo '<a href="/user/upload" class="form-switch glyphicon glyphicon-cloud-upload"></a>';
				} ?>
				<a href="/user" class="form-switch glyphicon glyphicon-arrow-left"></a>
			</div>
	</form>
</div>
