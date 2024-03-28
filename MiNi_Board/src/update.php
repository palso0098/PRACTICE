<?php
    require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php") // 설정 파일 호출																		
    require_once(FILE_LIB_DB); // DB관련 라이브러리    

    try {
        $conn = my_db_conn();

        if(REQUEST_METHOD === "GET") {

        } else if(REQUEST_METHOD === "POST") {
            $no = isset($POST["no"]) ? $_POST["no"] : "";
            $page = isset($POST["page"]) ? $_POST["page"] : "";
            $title = isset($POST["title"]) ? $_POST["title"] : "";
            $content = isset($POST["content"]) ? $_POST["content"] : "";
        }

        $arr_err_param = [];
        if($no === "") {
            $arr_err_param[] = "no";
        }

        $arr_err_param = [];
        if($no === "") {
            $arr_err_param[] = "page";
        }

        $arr_err_param = [];
        if($no === "") {
            $arr_err_param[] = "title";
        }

        $arr_err_param = [];
        if($no === "") {
            $arr_err_param[] = "content";
        }
        if(count($arr_err_param) > 0) {
            throw new Exception("Parameter Error : ", implode(", ", $arr_err_param));
        }

        $conn->beginTransaction();

        $arr_param = [
            "no" => $no
            ,"title" => $title
            ,"content" => $content
        ];
        $result = db_update_boards_no($conn, $arr_err_param);

        if($result !==1) {
            throw new Exception("Update Boards no count");
        }

        $conn->comit();

        header("Location: detail.php?no=".$no."&page=".$page);
        exit;

    } catch (\Throwable $e) {
        if(!empty($conn) && $conn-> inTransation()){
            $conn->rollBack();
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

<body>
<?php require_once(FILE_HEADER); ?>
    <!--눈 내리는 효과-->
    <div class="snowflakes" aria-hidden="true">
        <div class="snowflake">
          <div class="inner">❅</div>
        </div>
        <div class="snowflake">
          <div class="inner">❅</div>
        </div>
        <div class="snowflake">
          <div class="inner">❅</div>
        </div>
        <div class="snowflake">
          <div class="inner">❅</div>
        </div>
        <div class="snowflake">
          <div class="inner">❅</div>
        </div>
        <div class="snowflake">
          <div class="inner">❅</div>
        </div>
        <div class="snowflake">
          <div class="inner">❅</div>
        </div>
        <div class="snowflake">
          <div class="inner">❅</div>
        </div>
        <div class="snowflake">
          <div class="inner">❅</div>
        </div>
        <div class="snowflake">
          <div class="inner">❅</div>
        </div>
        <div class="snowflake">
          <div class="inner">❅</div>
        </div>
        <div class="snowflake">
          <div class="inner">❅</div>
        </div>
    </div>

    <header>
        <div class="header_title">
            <h1><a href="./index.html">MiNi Board</a></h1>
        </div>    
    </header>   
    
    <main>
        <!--form 태그에 입력한 내용을 인덱스페이지에 보내고, 보안을 위해 암호화 방식으로 전달되는 post로 작성함-->
        <form action="./update.php" method="post">    
            <input type="hidden" name="no" value="<?php echo $item["title"]; ?>">
            <input type="hidden" name="page" value="<?php echo $page; ?>">
            <div class="main_middle">
                <div class="line_item">
                    <div class="line_item_title">게시글 번호</div>
                    <div class="line_item_content_2"><?php echo $item["no"]; ?></div>
                </div>

                <div class="line_item">
                    <label for="title" class="line_item_title">
                        <div>제목</div>
                    </label>
                    <div class="line_item_content">
                        <input type="text" name="title" id="title" value="<?php echo $item["title"] ?>">
                    </div>
                </div>

                <div class="line_item">
                    <label for="title" class="line_item_title">
                        <div class="line_item_title_content">내용</div>
                    </label>
                    <div class="line_item_content">
                        <textarea name="content" id="content" rows="10"><?php echo $item["content"] ?></textarea>
                    </div>
                </div>
            </div>

            <div class="main_bottom">
                <button type="submit" class="bottom_button" id="insert_main_button">완료</button>
                <a href="./detail.php?no=<?php echo $no ?>&page=<?php echo $page ?>" class="bottom_button" id="insert_main_button">취소</a>
            </div>
        </form>
    </main>
</body>
</html>