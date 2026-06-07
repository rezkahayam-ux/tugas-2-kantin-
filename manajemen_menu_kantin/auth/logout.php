<?php
session_start();
session_destroy();
header('Location: /manajemen_menu_kantin/auth/login.php?msg=logged_out');
exit;
