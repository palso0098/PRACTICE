<?php
require_once("./config.php");
require_once(FILE_LIB_DB);
$list_cnt = 5;
$page_num = 1;

try {
    $conn = my_db_conn();
    
    $page_num = isset($_GET["page"]) ? $_GET["page"] : $page_num;

    $result_board_cnt = db_select_boards_cnt($conn);

    $max_page_num = ceil($result_board_cnt / $list_cnt);
    $offset = $list_cnt * ($page_num - 1);
    
    $prev_page_num = ($page_num - 1) < 1 ? 1 : ($page_num - 1);
    $next_page_num = ($page_num + 1) > $max_page_num ? $max_page_num : ($page_num + 1);

    $arr_param =[
        "list_cnt"  =>  $list_cnt
        ,"offset"   =>  $offset
    ];
    
    $result = db_select_boards_paging($conn, $arr_param);

} catch (\Throwable $e) {
    echo $e->getMessage();
    exit;
} finally {
    if(!empty($conn)) {
        $conn = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>인덱스 페이지</title>
    <link rel=" stylesheet" href="./css/common.css">
</head>

<body>
    <?php
    require_once(FILE_HEADER);
    ?>

    <main>
        <div class="main_top">
            <a href="./insert.php" class="insert_button">글 작성</a>
        </div>

        <div class="main_middle">
            <div class="item_list_head">
                <div class="head_no">게시글 번호</div>
                <div class="head_title">게시글 제목</div>
                <div class="head_time">작성일자</div>
            </div>

            <?php
            foreach($result as $item) {
            ?>
                <div class="item">
                    <div class="item_no"><?php echo $item["no"]?></div>
                    <div class="item_title"><a href="./detail.php?no=<?php echo $item["no"] ?>&page=<?php echo $page_num ?>"><?php echo $item["title"] ?></a></div>
                    <div class="item_time"><?php echo $item["created_at"] ?></div>
                </div>
            <?php
            }
            ?>
            
        </div>

        <div class="main_bottom">
            <a href="./list.php?page=<?php echo $prev_page_num ?>" class="bottom_button">이전</a>
            <?php
            for($num =1; $num <= $max_page_num; $num++){
            ?>
                <a href="./list.php?page=<?php echo $num ?>" class="bottom_button"><?php echo $num ?></a>
            <?php
            }
            ?>
            <a href="./list.php?page=<?php echo $next_page_num ?>" class="bottom_button">다음</a>
        </div>
    </main>
</body>
</html>