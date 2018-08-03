
<?php
// подключаемся к базе
$handle = mysqli_connect('localhost', 'root', '', 'info');
$handle->set_charset("utf8");
if (mysqli_connect_errno()) {echo "Подключение невозможно: ".mysqli_connect_error();}
?>