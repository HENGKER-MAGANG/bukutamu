<?php
session_start();
session_destroy();
header("Location: tamu.php?status=logout_berhasil");
exit;
