<?php
	$username = $_SESSION['username'];
?>
<img id="loading"/>
<div class="dummy" align="center">
	<div class="outer-wrapper-box">
		<div class="header">
			<div class="user-details">Instagram data of <b><?php echo $username; ?></b></div>
			<div class="select-all" onclick="change();"></div>
			<div class="download" onclick="getData();">1</div>
		</div>
		<div class="data-content">
			<div class="documentation">Select media to backup.</div>
			<div class="inner-content">
				<ul>
					<?php
						$currentUser->loadThumbnails();
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
<div>
	<a id="trigger-download" href="downloads/<?php echo $username; ?>/backup.zip"></a>
</div>
<script src="js/functions.js" type="text/javascript"></script>