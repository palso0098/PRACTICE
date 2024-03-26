<?php
    require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php");													
    require_once(FILE_LIB_DB);
    
    if (REQUEST_METHOD === "POST") {
        try {
            $title = isset($_POST["title"]) ? trim($_POST["title"]) : "";
            $content = isset($_POST["content"]) ? trim($_POST["content"]) : "";

            $arr_err_param = [];
            if($title === "") {
                $arr_err_param[] = "title";
            }
            if($content === "") {
                $arr_err_param[] = "content";
            }
            if(count($arr_err_param) > 0) {
                throw new Exception("Parameter Error : ".implode(",", $arr_err_param));
            }

            $conn = my_db_conn();

            $conn->beginTransaction();

            $arr_err_param = [
                "title" => $title
                ,"content" => $content
            ];
            $result = db_insert_boards($conn, $arr_err_param);

            if($result !== 1) {
                throw new Exception("insert Boards count");
            }

            $conn->commit();

            header("Location: list.php");
            exit;

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
    }   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>인서트 페이지</title>
    <link rel=" stylesheet" href="./css/common.css">
</head>

<body>
    <?php require_once(FILE_HEADER); ?>
    <main>
        <!--form 태그에 입력한 내용을 인덱스페이지에 보내고, 보안을 위해 암호화 방식으로 전달되는 post로 작성함-->
        <form action="./insert.php" method="post">    
            <div class="main_middle">
                <div class="line_item">
                    <label for="title" class="line_item_title">
                        <div>제목</div>
                    </label>
                    <div class="line_item_content">
                        <input type="text" name="title" id="title">
                    </div>
                </div>

                <div class="line_item">
                    <label for="title" class="line_item_title">
                        <div class="line_item_title_content">내용</div>
                    </label>
                    <div class="line_item_content">
                        <textarea name="content" id="content" rows="10"></textarea>
                    </div>
                </div>
            </div>

            <div class="main_bottom">
                <button type="submit" class="bottom_button" id="insert_main_button">작성</button>
                <a href="./index.html" class="bottom_button" id="insert_main_button">취소</a>
            </div>
        </form>
    </main>
</body>
</html>