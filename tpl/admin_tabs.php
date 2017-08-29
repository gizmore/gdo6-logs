<?php
use GDO\Template\GDT_Bar;
use GDO\UI\GDT_Link;

$navbar = GDT_Bar::make('tabs');
$navbar->addFields(array(
	GDT_Link::make('link_log_view')->href(href('Logs', 'View')),
));
echo $navbar->render();
