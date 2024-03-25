<?php

function my_db_conn() {
    $option = [																	
        PDO::ATTR_EMULATE_PREPARES      => FALSE						
        ,PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION						
        ,PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC						
    ];																	
    

    //리턴
    return new PDO(MARIADB_DSN, MARIADB_USER, MARIADB_PASSWORD, $option);
}

function db_select_boards_paging(&$conn, &$array_param) {
    $sql = 
        "SELECT    
            no
            ,title
            ,created_at
        FROM    
            boards
        WHERE   
            deleted_at IS NULL
        ORDER BY    
            no DESC
        LIMIT :list_cnt OFFSET :offset "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();
    
    return $result;
}

function db_select_boards_cnt(&$conn) {
    $sql =
        " SELECT "						
	    ." count(no) as cnt "
        ." FROM "		
        ." boards "	
        ." WHERE "	
        ." deleted_at IS NULL "		
    ; 						

    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll();

    return (int)$result[0]["cnt"];
}