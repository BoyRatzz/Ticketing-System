<?php
$password = 'admin';
$password_hash = '$2y$10$W.KX7KUQghZCwDwqt2mq8uf.rwptLG8Llw662I6gexQwYS1ZCJw5G';

var_dump($user);

if (password_verify($password, $password_hash)) {
    echo 'Password is correctly hashed!';
} else {
    echo 'WRONG';
}
exit;
?>