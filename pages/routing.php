<?php
require_once "./backend/adminLogged.php";

if(AdminLogged::AdminLogged()) {echo "<script>location.href = '?adminLogged'</script>";}
