<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

$URL = path("blog/". $post["Year"] ."/". $post["Month"] ."/". $post["Day"] ."/". $post["Slug"]);		
$in  = ($post["Tags"] !== "") ? __("in") : null;
?>
<div class="post">
	<div class="post-title">
		<a href="<?php echo $URL; ?>" title="<?php echo stripslashes($post["Title"]); ?>">
			<?php echo stripslashes($post["Title"]); ?>
		</a>
	</div>
	

	<div class="post-left">
		<?php echo __("Published") ." ". howLong($post["Start_Date"]) ." $in ". exploding($post["Tags"], "blog/tag/") ." " . __("by") . ' <a href="'. path("user/". $post["Author"]) .'">'. $post["Author"] .'</a>'; ?>
	</div>
	
	<div class="post-right">
		<?php
			if ($post["Enable_Comments"]) {
            	echo fbComments($URL, true);
			}
		?>
	</div>
	
	<div class="clear"></div>
		
	<div class="post-content">
		<?php
			echo display(social($URL, $post["Title"], false), 4); 
			echo showContent($post["Content"]); 

			if ($post["Display_Bio"]) {

				if ($author["Twitter"]) {
					$social[] = a("Twitter", "https://twitter.com/". $author["Twitter"], true, array("rel" => "nofollow"));
				}

				if ($author["Facebook"]) {
					$social[] = a("Facebook", "http://facebook.com/". $author["Facebook"], true, array("rel" => "nofollow"));
				}

				if ($author["Linkedin"]) {
					$social[] = a("LinkedIn", "http://linkedin.com/in/". $author["Linkedin"], true, array("rel" => "nofollow"));
				}

				if ($author["Google"]) {
					$social[] = a("Google+", "https://profiles.google.com/". $author["Google"], true, array("rel" => "nofollow"));
				}

				if ($author["Viadeo"]) {
					$social[] = a("Viadeo", "http://viadeo.com/en/profile/". $author["Viadeo"], true, array("rel" => "nofollow"));
				}

				$social[] = a(__("View more publications by this author"), path("user/". $author["Username"] . "/"));
		?>
		
		<br />

		<div class="bio">
			<table class="bio">
				<tr>
					<td>
						<?php echo getAvatar($author["Avatar"], $author["Username"]); ?>
					</td>
					<td>
						<p class="author-details">
							<span class="author-name"><?php echo $author["Name"]; ?></span>
							<span class="author-username"><?php echo $author["Username"]; ?></span>
						</p>

						<?php if ($author["Country"] or ($author["Website"] and $author["Website"] !== "http://")) { ?>
						<p class="author-location">
							<?php if ($author["Country"]) {
								echo getFlag($author["Country"]); ?>&nbsp;<?php echo __($author["Country"]); ?>
							<?php } ?>

							<?php if ($author["Website"] and $author["Website"] !== "http://") {
								echo "&#xb7; ". a($author["Website"], $author["Website"], true, array("rel" => "nofollow"));
							} ?>
						</p>
						<?php } ?>

						<?php if ($author["Country"]) { ?>
						<p class="author-country-mobile">
							<?php echo getFlag($author["Country"]); ?>&nbsp;<?php echo __($author["Country"]); ?>
						</p>
						<?php } ?>

						<?php if ($author["Website"] and $author["Website"] !== "http://") { ?>
						<p class="author-website-mobile">
							<?php echo a($author["Website"], $author["Website"], true, array("rel" => "nofollow")); ?>
						</p>
						<?php } ?>

						<p class="author-social">
							<?php echo implode(" | ", $social); ?> 
						</p>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="author-social-mobile">
						<?php
							$more = array_pop($social);
							if (count($social) > 0) {
								echo implode(" | ", $social) . "<br />";
							}

							echo $more;
						?>
					</td>
				</tr>
			</table>
		</div>
		
		<?php
			}
		?>

		<br /><br />

		<?php 
			echo display('<p>'. getAd("728px") .'</p>', 4);
		?>
	</div>

</div>
<br /></br />
<?php
	if ($post["Enable_Comments"]) {
		echo fbComments($URL);
	}
?>
