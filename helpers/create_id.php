<?php
function createID($ma,$countCol)
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $timeCurrent = date('s');
    $idMaterial = $ma.'_' . $timeCurrent ."_". $countCol + 1;
    return $idMaterial;
}
