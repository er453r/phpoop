<?php

include('../poop.php');

$poop = new poop();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>
	<?php
		$string = '';
		
		foreach($poop->get_title() as $n => $part){
			if($n)
				$string = $part.' | '.$string;
			else
				$string = $part;
		}
		
		echo($string);
	?>
	</title>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $poop->prefix() ?>/styles/"/>
	<script type="text/javascript" src="<?php echo $poop->prefix() ?>/scripts/"></script>
</head>
<body>
	<ul id="menu">
		<li><a href="<?php echo $poop->prefix() ?>/">Start</a></li>
		<li><a href="<?php echo $poop->prefix() ?>/projects">Projects</a></li>
		<li><a href="<?php echo $poop->prefix() ?>/portfolio">Portfolio</a></li>
		<li><a href="<?php echo $poop->prefix() ?>/poop">Poop</a></li>
		<li><a href="<?php echo $poop->prefix() ?>/about">About</a></li>
	</ul>
	<div id="content">
		<?php 
			$string = '<ul id="path">';
			
			foreach($poop->get_path() as $part)
					$string .= '<li><span>&raquo;</span><a href="'.$part['link'].'" title="'.$part['title'].'">'.$part['name'].'</a></li>';
	
			$string .= '</ul>';
			
			echo($string);
		?>
		<?php echo $poop->get_content() ?>
	</div>
	<div id="footer">
		<a href="<?php echo $poop->prefix() ?>/sitemap">Sitemap</a> | &copy; 2010 er453r | <?php echo $poop->get_running_time()?> s.
	</div>
</body>
</html>