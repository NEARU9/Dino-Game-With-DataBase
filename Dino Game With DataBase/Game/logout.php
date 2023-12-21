<?php
  session_start();
  session_destroy();
  // echo "Anda sudah logout";
  echo "<META HTTP-EQUIV='Refresh' Content='1; url=?p=home'>";
?>