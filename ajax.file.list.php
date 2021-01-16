<?php
include_once('../../../../../common.php');

if(!$member['mb_no']) {
    header("Content-Type: application/json");
    echo json_encode([
        'success'=>false,
        'bf_no'=>0,
        'image'=>false,
        'path'=> '',
        'msg'=> '파일업로드를 위해서는 로그인이 필요합니다.'
    ]);
    exit;
}

$table = $g5['board_file_table'];

$result = sql_query("
 select bf_source as name, bf_file, bf_no, bf_filesize as size, bf_width as width, bf_height as height
 from {$table} where
                  `bo_table` = '{$bo_table}' and
                  (
                  `wr_id` = {$wr_id} or
                  (`wr_id` = -1 and `bf_download` = {$member['mb_no']})
                  )
");

$list = [];
while ($row = sql_fetch_array($result)) {
    $row['path'] = '/data/file/'.$bo_table.'/'.$row['bf_file'];
    $row['image'] = true;
    if(is_file(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file'])) {
        $row['mime'] = mime_content_type(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
        if(strpos($row['mime'],'image') === false) {
            $ext = pathinfo($row['name'],PATHINFO_EXTENSION);
            $row['thumb'] = $board_skin_url.'/img/extensions/'.$ext.'.svg';
            $row['image'] = false;
        }
    }
    $list[] = $row;
}

header("Content-Type: application/json");
echo json_encode([
    'count'=>sizeof($list),
    'list'=>$list
]);

