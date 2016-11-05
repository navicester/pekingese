<?php
    /*
    * SAE_MYSQL_USER:用户名 
    * SAE_MYSQL_PASS：密码： 
    * SAE_MYSQL_HOST_M：主库域名
    * SAE_MYSQL_HOST_S：从库域名 
    * SAE_MYSQL_PORT：端口： 
    * SAE_MYSQL_DB数据库名
    * 
    * 详细说明：页面的编码要和数据库的编码一样，不然会出现乱码
    * 或者在连接数据库时设置mysql_set_charset()
    * 
    
    $link = mysql_connect ( SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS );
    if ($link) {
        mysql_select_db ( SAE_MYSQL_DB, $link );
        mysql_set_charset("gbk");
        $sql = "select UID,UNAME from Base_User";
        $result = mysql_query ( $sql );
        while ( $row = mysql_fetch_array ( $result, MYSQL_NUM ) ) {
            echo ("<td>$row[0]</td><td>$row[1]</td>");
        }
        mysql_free_result ( $result );
    } else {
        echo "数据库连接KO";
    }
   */
    #echo set_appmode('5');
    #echo get_appmode();

    define("LN", __LINE__);//行号  
    define("FL", __FILE__);//当前文件  
    define("DEBUG", 0);//调试开关 
    $db_name = "upload.db";  
    function init_sqlite()
    {
        //Create db
        if (!file_exists($db_name)) {  
            if (!($fp = fopen($db_name, "w+"))) {  
                exit(error_code(-1, LN));  
            }  
            fclose($fp);  
        }  
        
        //Open db file
        if (!($db = sqlite_open($db_name))) {  
            exit(error_code(-2, LN));  
        }

        //Create table
        if (!sqlite_query($db, "DROP TABLE uploads")) {  
            exit(error_code(-3, LN));  
        }  
        if (!sqlite_query($db, "CREATE TABLE uploads (id integer primary key, app_mode varchar(200) UNIQUE, app_key integer)")) {  
            exit(error_code(-3, LN));  
        }  

        //Insert date
        if (!sqlite_query($db, " INSERT INTO uploads (app_mode, app_key) VALUES ('default','0') ")) {  
            exit(error_code(-4, LN));  
        }  


    }

    function get_appmode_sqlite()
    {
        //Open db file
        if (!($db = sqlite_open($db_name))) {  
            exit(error_code(-2, LN));  
        }

        //retrieve data
        if (!($result = sqlite_query($db, "SELECT * FROM uploads"))) {  
            exit(error_code(-5, LN));  
        }

        //Get data and display
        /*
        while ($array = sqlite_fetch_array($result)) {  
            echo "ID: ". $array[file_name] ."<br>: ". $array[make_time] ;  
        }  
        */
        $array = sqlite_fetch_array($result);
        sqlite_close($db_name);
        return $array[app_mode];

        //Close db file
        /*
        if (!($db = sqlite_close($db_name))) {  
            exit(error_code(-2, LN));  
        }
        */

    }

    function set_appmode_sqlite($mode)
    {

        //Open db file
        if (!($db = sqlite_open($db_name))) {  
            exit(error_code(-2, LN));  
        }

        //retrieve data
        if (!($result = sqlite_query($db, "UPDATE uploads set app_mode = '$mode' where app_key=0 "))) {  
            exit(error_code(-5, LN));  
        }        

    }

    function get_appmode()
    {
        return "get_appmode"
        $link = mysql_connect( SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS );
        if ($link) {
            mysql_select_db ( SAE_MYSQL_DB, $link );
            mysql_set_charset("gbk");
            $sql = "SELECT * from app_mode";
            $result = mysql_query($sql, $link);
            $row = mysql_fetch_object($result);
            $value = $row->name;
            mysql_close($link);
            return $value;
        } else {
            return "数据库连接KO";
        }
    }

# http://www.w3school.com.cn/php/php_mysql_update.asp

    function set_appmode($mode)
    {
        return;
        
        $link = mysql_connect ( SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS );
        if ($link) {
            mysql_select_db ( SAE_MYSQL_DB, $link );
            mysql_set_charset("gbk");
            #echo ($mode);
            $sql = "update app_mode set name = '$mode' where id = 1"; #item can't be key
            $result = mysql_query($sql,$link);
            mysql_close($link);
        } else {
            echo "数据库连接KO";
        }
    }

    function error_code($code, $line_num, $debug=DEBUG)  
    {  
        if ($code<-6 || $code>-1) {  
            return false;  
        }  
        switch($code) {  
            case -1: $errmsg = "Create database file error.";  
                break;  
            case -2: $errmsg = "Open sqlite database file failed.";  
                break;  
            case -3: $errmsg = "Create table failed, table already exist.";  
                break;  
            case -4: $errmsg = "Insert data failed.";  
                break;  
            case -5: $errmsg = "Query database data failed.";  
                break;  
            case -6: $errmsg = "Fetch data failed.";  
                break;  
            case -7: $errmsg = "";  
                break;  
            default: $errmsg = "Unknown error.";  
        }  
        $m    = "<b>[ Error ]</b><br>File: ". basename(FL) ." <br>Line: ". LN ."<br>Mesg: ". $errmsg ."";  
        if (!$debug) {  
            ($m = $errmsg);  
        }  
        return $m;  
    } 
?>