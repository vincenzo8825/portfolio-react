<?php
// NUCLEAR OPTION: Sovrascrivi completamente l'interceptor
header('Location: public/index.php' . $_SERVER['REQUEST_URI']);
exit;
?> 