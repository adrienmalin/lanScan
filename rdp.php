<?php

header('Content-Disposition: attachment; filename='.str_replace(':', '_', $_GET['v']).'.rdp');
header('Content-Type: application/rdp');
echo "full address:s:${_GET['v']}\n";
exit();
