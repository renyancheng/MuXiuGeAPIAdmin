<?php
return $db->query("SELECT title,subtitle,description,keywords,favicon,email,qq,url,icp,copyright,theme,accent,qqqrcode,vxqrcode,aliqrcode,post_id,close_site,cc_protect,fire_wall,end_script FROM `mxgapi_config`")->fetch_assoc();
