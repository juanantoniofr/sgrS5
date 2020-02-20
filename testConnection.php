<?php
//..
$connectionParams = array(
    'url' => 'mysql://reservasfgh:cpsy3fLB@localhost:3306/reservasfgh?serverVersion=5.7',
);
$conn = vendor\doctrine\dbal\lib\Doctrine\DBAL\DriverManager::getConnection($connectionParams);

echo 'test connections';