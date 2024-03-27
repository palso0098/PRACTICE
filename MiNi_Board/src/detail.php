<?php
// 설정 정보
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리

try {
    $conn = my_db_conn();

    $no = isset($_GET["no"]) ? $_GET["no"] : "";
    $page = isset($_GET["page"]) ? $_GET["page"] : "";

    $arr_err_param = [];
    if($no === "") {
        $arr_err_param[] = "no";
    }
    if($page === "") {
        $arr_err_param[] = "page";
    }
    if(count($arr_err_param) > 0) {
        throw new Exception("Parameter Erroe : ".implode(",", $arr_err_param));
    }

    $arr_param = [
        "no" => $no
    ];
    $result = db_select_boards_no($conn, $arr_param);
    if(count($result) !== 1) {
        throw new Exception("Select Boards no count");
    }

    $item = $result[0];

}catch (\throwable $e) {
    echo $e->getMessage();
    exit;
}finally {
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
    <title>상세 페이지</title>
    <link rel="stylesheet" href="./css/common.css">
</head>
<body>
    <!-- 헤더 호출 -->
    <?php require_once(FILE_HEADER); ?>
    <main>   
        <div class="main_middle">
            <div class="line_item">
                <div class="line_item_title">게시글 번호</div>
                <div class="line_item_content_2"><?php echo $item["no"] ?></div>
            </div>
            <div class="line_item">
              <div class="line_item_title">제목</div>
              <div class="line_item_content_2"><?php echo $item["title"] ?></div>
          </div>
          <div class="line_item">
            <div class="line_item_title">내용</div>
            <div class="line_item_content_2"><?php echo $item["content"] ?></div>
        </div>
        <div class="line_item">
          <div class="line_item_title">작성일자</div>
          <div class="line_item_content_2"><?php echo $item["created_at"] ?></div>
        </div>

        </div>
            <div class="main_bottom">
                <a href="./update.php?no=<?php echo $no ?>&page=<?php echo $page ?>" class="bottom_button" id="insert_main_button">수정</a>
                <a href="./list.php?page=<?php echo $page ?>" class="bottom_button" id="insert_main_button">취소</a>
                <a href="./delete.php?no=<?php echo $no ?>&page=<?php echo $page ?>" class="bottom_button" id="insert_main_button">삭제</a>
            </div>
        </form>
    </main>
</body>
</html>
