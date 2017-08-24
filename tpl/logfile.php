<?php
use GDO\Template\GDO_Box;

echo GDO_Box::make()->title(basename($path))->html(file_get_contents($path))->render();
