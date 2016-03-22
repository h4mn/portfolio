<?php
$sign = "187.183.35.121";
$hash = "Hadston_E_Kelly";
$key = $hash . ':' . $sign;
echo md5($key);