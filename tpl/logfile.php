<?php
use GDO\UI\GDT_Panel;

echo GDT_Panel::make()->title(basename($path))->html(file_get_contents($path))->render();
