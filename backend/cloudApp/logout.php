<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Start the session only if it's not already started
}
session_destroy();

echo "<script>location.href = '../../?uvod'</script>";