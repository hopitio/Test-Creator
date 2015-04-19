<?php

$callback = $_GET['callback'];
echo $callback . '(' . json_encode(array()) . ')';