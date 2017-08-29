<?php
use GDO\Template\GDT_Box;

echo GDT_Box::make()->title(basename($path))->html(file_get_contents($path))->render();
