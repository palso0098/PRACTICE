<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출																		
require_once(FILE_LIB_DB); // DB관련 라이브러리																		

try {
    $conn = my_db_conn();

    if(REQUEST_METHOD === "GET") {
        $no = isset($_GET["no"]) ? $_GET["no"] : "";
        $page = isset($_GET["page"]) ? $_GET["page"] : "";
        $arr_err_param = [];
        if($no === "") {
            $arr_err_param[] = "no";
        }
        if(count($arr_err_param) > 0) {
            throw new Exception("Parameter Error : ".implode(", ", $arr_err_param));
        }
        $arr_param = [
            "no" => $no
        ];
        $result = db_select_boards_no($conn, $arr_param);
        if(count($result) !== 1) {
            throw new Exception("Select Boards no count");
        }

        $conn->beginTransaction();
        $arr_param = [
            "no" => $no
        ];
        $result = dba_delete_boards_no($conn, $arr_param);

        if($result !== 1) {
            throw new Exception("Delete Boards no count")
        }

        $conn->comit();

        header("Location: list.php");
        exit;
        // 아이템 셋팅
        $item = $result[0];
    }   
    else if (REQUEST_METHOD === "POST"){
        $no = isset($_POST["no"]) ? $_POST["no"] : "";
    }
    
} catch (\Throwable $e) {
    if(!empty($conn)) {
        $conn->rollback();
    }
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
    <title>딜리트 페이지</title>
    <link rel=" stylesheet" href="./css/common.css">
</head>

<body>
    <!-- 헤더 호출 -->
    <?php require_once(FILE_HEADER); ?>
    <main>   
        <div class="main_middle">
            <div class="main_middle_message">
                <p class="main_middle_message_blink">
                    삭제하면 영구적으로 복구 할 수 없습니다.
                    <br>
                    정말로 삭제 하시겠습니까?
                </p>
            </div>

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
        <form acction="./delete.php" method="post">
            <div class="main_bottom">
                <input type="hidden" name="no" value="<?php echo $no; ?>">
                <button type="submit" class="bottom_button">동의</button>
                <a href="./detail.php?no=<?php echo $no; ?>&page=<?php echo $page; ?>" class="bottom_button" id="insert_main_button">취소</a>
            </div>
        </form>
    </main>
</body>
</html>