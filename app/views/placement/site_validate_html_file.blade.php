<?php
header("Content-type: text/html; name='html'; charset=utf-8");
header("Content-Disposition: attachment; filename=\"".Session::get('platform.brand')."_validate_site.html\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");

echo '<html><head>';
echo '<meta name="'.Session::get('platform.brand').'-tag" content="' . $id_site . '" />';
echo '</head><body></body></html>';