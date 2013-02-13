<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}
?>

<div id="blog-access">
	<form class="blog-access" action="" method="post">
		<fieldset>
			<legend><?php echo __("Private post"); ?></legend>
			
			<p>
				<strong><?php echo __("Post password"); ?></strong><br />
				<input name="password" type="password" class="input" />				
			</p>
			
			<p>
				<input name="access" type="submit" value="<?php echo __("Access"); ?>" class="submit" />
			</p>
			
			<input name="pwd" type="hidden" value="<?php echo $password; ?>" />
		</fieldset>
	</form>
</div>
