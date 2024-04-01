<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리

// GET
// if (REQUEST_METHOD === "GET") {
//     // GET 처리, 현재 코드에서는 불필요한 분기
// }

// POST 처리
if (REQUEST_METHOD === "POST") {
    try {
        // 파라미터 획득
        $title = isset($_POST["title"]) ? trim($_POST["title"]) : ""; // title 획득
        $content = isset($_POST["content"]) ? trim($_POST["content"]) : ""; //content 획득
        
        // 파라미터 에러 체크
        $arr_err_param = [];
        if($title === ""){
            $arr_err_param[] = "title";
        }
        if($content === ""){
            $arr_err_param[] = "content";
        }
        if(count($arr_err_param) > 0){
            // 예외 처리
            throw new Exception("Parameter Error : ".implode(", ", $arr_err_param));
        }

        // DB Connect
        $conn = my_db_conn(); // PDO 인스턴스

        // Transaction 시작
        $conn->beginTransaction();

        // 게시글 작성 처리
        $arr_param = [
            "title" => $title
            ,"content" => $content
        ];
        $result = db_insert_boards($conn, $arr_param); // TODO : 주석해제 까먹지마

        // 글 작성 예외 처리 (로직을 잘 작성했다면 에러안남)
        if($result !== 1){
            throw new Exception("Insert Boards count");
        }

        // 커밋
        $conn->commit();

        // 리스트 페이지로 이동
        header("Location: list.php");
        exit;

    } catch (\Throwable $e) {
        if(!empty($conn) && $conn->inTransaction()){
            $conn->rollBack();
        }
        echo $e->getMessage();
        exit;

    } finally {
        if(!empty($conn)){
            $conn = null;
        }
    }   
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mini Board</title>
    <link rel="stylesheet" href="./css/common.css">
</head>
<body>
    <?php require_once(FILE_HEADER); ?>
    <main>
        <form action="./insert.php" method="post">
            <div class="main-middle">
                <div class="line-item">
                    <label class="line-title" for="title">
                        <div>제목</div>
                    </label>
                    <div class="line-content">
                        <input type="text" name="title" id="title">
                    </div>
                </div>
                <div class="line-item">
                    <label class="line-title" for="content">
                        <div class="line-title-textarea">내용</div>
                    </label>
                    <div class="line-content">
                        <textarea name="content" id="content" rows="10"></textarea>
                    </div>
                </div>
            </div>
            <div class="main-bottom">
                <button type="submit" class="a-button small-button">작성</button>
                <button>
                    <a href="./insert.php" class="a-button small-button">취소</a>
                </button>
            </div>
        </form>
    </main>
</body>
</html>