<?php
include_once('../../../../../common.php');

if(!$wr_id) {
    header("Content-Type: application/json");
    echo json_encode(['success'=>false]);
    exit;
}
$bf_no = $_GET['bf_no'];

$success = false;
if($bf_no) {
    $row = sql_fetch("
 select *
 from {$g5['board_file_table']}
 where `bo_table` = '{$bo_table}'
   and wr_id = '{$wr_id}'
   and bf_no = '{$bf_no}'
LIMIT 1
");

    $delete_file = run_replace('delete_file_path', G5_DATA_PATH.'/file/'.$bo_table.'/'.str_replace('../', '', $row['bf_file']), $row);
    if( file_exists($delete_file) ){
        @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
    }
// 이미지파일이면 썸네일삭제
    if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['bf_file'])) {
        delete_board_thumbnail($bo_table, $row['bf_file']);
    }

    sql_query("
 delete from {$g5['board_file_table']}
 where bo_table = '{$bo_table}'
   and wr_id = '{$wr_id}'
   and bf_no = '{$bf_no}'
");
    $success = true;
}


header("Content-Type: application/json");
echo json_encode(['success'=>$success]);

