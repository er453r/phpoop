<?php

include('../poop.php');

$poop = new poop();
$poop->site_addr = 'http://127.0.0.1:8888/eclipse/POOP/example';
$poop->init();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title><?php echo $poop->title ?></title>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $poop->site_addr ?>/styles/"/>
</head>
<body>
	<ul id="menu">
		<li><a href="<?php echo $poop->site_addr ?>/">Start</a></li>
		<li><a href="<?php echo $poop->site_addr ?>/about">Projects</a></li>
		<li><a href="<?php echo $poop->site_addr ?>/about">Portfolio</a></li>
		<li><a href="<?php echo $poop->site_addr ?>/poop">Poop</a></li>
		<li><a href="<?php echo $poop->site_addr ?>/about">About</a></li>
		<li><a href="<?php echo $poop->site_addr ?>/about">KONTAKTOSSS!!!</a></li>
	</ul>
	<div id="content">
		<?php echo $poop->get_path() ?>
		<?php echo $poop->content ?>
	</div>
	<div id="footer">
	<a href="<?php echo $poop->site_addr ?>/sitemap">Sitemap</a> | &copy; 2010 er453r
	</div>
</body>
</html>