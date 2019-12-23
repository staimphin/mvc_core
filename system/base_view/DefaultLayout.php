<?php /* Basic layout*/
$navigation = $this->template(VIEW_COMMON_PATH."navigation.php");
if ($navigation) {
    include_once $navigation;
}

$side = $this->template(VIEW_COMMON_PATH."side.php");
if ($side) {
    include_once $side;
}
$page = $this->checkTemplate($folder, $file_name);

if ($page) {
    include_once $page;
}

include_once VIEW_COMMON_PATH.'footer.php';