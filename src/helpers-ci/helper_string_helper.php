<?php
defined('BASEPATH') OR exit('No direct script access allowed');


//原生函数	（常用函数笔记已完成）
function string_original()
{
	/*
    //提取与解析
    substr(string,start,length)
    parse_str("name=Bill&age=60",$myArray);     //$myArray = ( [name] => Bill [age] => 60 );
    strip_tags("Hello <b>world!</b>");          //Hello world!

    $str = "age:30 weight:60kg";
    sscanf($str,"age:%d weight:%dkg",$age,$weight);
    var_dump($age,$weight);                     //int(30) int(60)


    //查找
    stripos("You love php, I love php too!","PHP")      //9  查找 "php" 在字符串中第一次出现的位置（不区分大小写） 没找到返false
    strpos(string, find, start)                         //同上,区分大小写
    strripos("You love php, I love php too!","PHP");    //21 查找 "php" 在字符串中最后一次出现的位置（不区分大小写）
    strrpos(string, find, start)                        //同上,区分大小写

    strchr("Hello world!","world");             //world!    查找 "world" 在 "Hello world!" 中的第一次出现，并返回此字符串的其余部分, 区大小
    strpbrk("I love Shanghai!","Sh")            //Shanghai! 区大小
    strrchr();                                  //同上, 从后开始
    strstr(string,search,before_search);        //别名: strchr
                            true:   并返回此字符串前部分(不包含)
    stristr(string,search,before_search)        //同上, 不区分大小写

    //数组相关
    str_split("Shanghai",3);                                            //把字符串分割到数组中。Array ( [0] => Sha [1] => ngh [2] => ai )
    $array = explode(' ', "Hello world. I love Shanghai!", 5)           //打散成数组, 包含空串
    implode(" ", array('Hello','World!','I','love','Shanghai!') )       //数组合成字符串, 别名:join()

    /替换
    str_replace(find,replace,string,count)  //替换
    str_ireplace(find,replace,string,count) //不区分大小写
    substr_replace(string,replacement,start,length)     //把字符串的一部分替换为另一个字符串。
                                              0: 插入而非替换
    <?php
        $replace = array("1: AAA","2: AAA","3: AAA");
        echo implode("<br>",substr_replace($replace,'BB',3,3));
    //1: BB
    //2: BB
    //3: BB
    ?>

    //比较
    strcasecmp("shanghai","SHANGHAI");      //不区分大小写
    strcmp(string1,string2)
    strncmp("I love China!","I love Shanghai!",6)   //0
    strncasecmp(string1,string2,length)             //同上, 不区分大小写
    substr_compare(string1,string2,startpos,length,case)    //从指定的开始位置比较两个字符串。

    //移除
    trim(string,charlist)                   //移除字符串两侧的字符,亦可移除指定字符串
    ltrim(string,charlist)                  //同上,左侧
    rtrim(string,charlist)                  //同上,右侧; 别名:chop(string,charlist)

    //分割
    <?php
        $string = "Hello world. Beautiful day today.";
        $token = strtok($string, " ");      //逐一分割字符串

        while ($token !== false)
        {
        echo "$token<br>";
        $token = strtok(" ");
        }
    ?>

    //格式
    number_format("5000000",2)              //5,000,000.00

    //计数
    strlen("Shanghai")                      //8
    str_word_count(string, return, char)
    str_word_count("I love Shanghai!")      //3
    substr_count("I love Shanghai. Shanghai is the biggest city in china.","Shanghai")  //2

    //转换
    strtolower(string)          //把字符串转换为小写。
    strtoupper(string)

    //输出
    printf("在%s有 %u 百万辆自行车。",$str,$number);     //输出格式化的字符串

    //加密
    md5(string,FALSE)
    md5_file(file,FALSE)

    //查询
    similar_text("Hello World","Hello Shanghai",$percent); echo $percent . "%";     //48% 计算两个字符串的相似度

    */
}

// 多语言处理字符串
function get_string_by_lang($strEn, $strZh)
{
    return $_SESSION['lang']=='en'? $strEn : $strZh;
}

function echo_by_lang($strEn, $strZh)
{
    echo $_SESSION['lang']==='en'? $strEn : $strZh;
}

function show_by_lang($strLang)
{
    if( $strLang==='en' )
        echo $_SESSION['lang']==='en' ? '' : 'hidden';
    else if( $strLang==='cn' )
        echo $_SESSION['lang']==='cn' ? '' : 'hidden';
    else
        echo 'hidden';
}

/* url */
function str_url_whole()
{
    return trim($_SERVER['REDIRECT_URL'], '/');
}

function str_uri_except_domain()
{
    return trim($_SERVER['REQUEST_URI'], '/');
}

function str_url_latter()
{
    return trim($_SERVER['PATH_INFO'], '/');
}

function array_url_latter()
{
    return explode( '/', trim($_SERVER['PATH_INFO'], '/') );
}

function str_url_last()
{
    $arrayPart = explode( '/', trim($_SERVER['PATH_INFO'], '/') );
    return end( $arrayPart );
}