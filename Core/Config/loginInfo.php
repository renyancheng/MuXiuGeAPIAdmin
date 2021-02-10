<?php
return $db->query("SELECT username,password FROM `mxgapi_config`")->fetch_assoc();
