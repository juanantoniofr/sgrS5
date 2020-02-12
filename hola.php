<?php
echo $_SERVER['TRUSTED_PROXIES'];

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
	
	echo "???";
}
else
echo "pono"
?>