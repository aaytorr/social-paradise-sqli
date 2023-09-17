<?php
	include_once "Header.php";
?>
		<?php
		
		//get blog posts
		
		$getBlogs = mysql_query("SELECT * FROM BlogPosts ORDER BY ID DESC");
		
			while ($gB = mysql_fetch_object($getBlogs)) {
				
				echo "
					<div id='BlogPost'>
					<a name='".$gB->ID."'></a>
							<div id='Title'>
								".$gB->Title."
							</div>
							<br />
							<div id='Text'>
								".nl2br($gB->Body)."
							</div>
					</div>
					<br />
				";
			}
		?>
<?
	include_once "Footer.php";
?>