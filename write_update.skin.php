<?php if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

/*
$sql = " SELECT max(bf_no) as max_bf FROM {$g5['board_file_table']}
WHERE `bo_table`='{$bo_table}' and `wr_id` = {$wr_id}
";
$bf = sql_fetch($sql);
$max_bf = $bf['max_bf']+1;
//*/

$sql = " UPDATE {$g5['board_file_table']}
 SET `wr_id` = {$wr_id}, `bf_download`=0
WHERE `bo_table`='{$bo_table}' and `wr_id` = -1 and `bf_download` = '{$member['mb_no']}'
";
sql_query($sql);

$sql = " SELECT count(*) as cnt FROM {$g5['board_file_table']}
WHERE `bo_table`='{$bo_table}' and `wr_id` = {$wr_id}
";
$row = sql_fetch($sql);
if($row['cnt']) {
    $sql = " UPDATE {$write_table}
SET `wr_file` = 1
WHERE `wr_id` = {$wr_id}
LIMIT 1
";
    sql_query($sql);
}


