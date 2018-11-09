<?php
defined('BASEPATH') OR exit('No direct script access allowed');


//原生函数  （已完成）
function array_original()
{
    /*
    //创建与调用
    $cars=array("Volvo","BMW","SAAB"); $cars[1]="BMW";                      //索引数组
    foreach($index as $cars)            //遍历
    $age=array("Peter"=>"35","Ben"=>"37","Joe"=>"43"); $age['Ben']="37";    //关联数组
    foreach($age as $x=>$x_value)       //遍历
    array(0=>"red",1=>"green",2=>"blue");                                   //数字索引(一般改动后自动重建索引)
                                                                            //重建后: Array ( [0] => "red" [1] => "green" [2] => "blue" )
    $cars = array                       //二维数组
        (
        array("Volvo",22,18),
        array("BMW",15,13),
        array("Land Rover",17,15)
        );

    $number = range(0,50,10);                       //Array ( [0] => 0 [1] => 10 [2] => 20 [3] => 30 [4] => 40 [5] => 50 )
    list($a, $b, $c) = array("Dog","Cat","Horse");  //$a="Dog", $b="Cat", $c="Horse"

    compact($key_1, $key_2)       创建包含变量名和它们的值的数组。





    //计数
    count($cars, 0);                                            //返回元素数 别名sizeof
                 1: 计算多维数组中的所有元素
    array_count_values( array("A","Cat","Dog","A","Dog") );     //对数组中的所有值进行计数 Array ( [A] => 2 [Cat] => 1 [Dog] => 2 )
    array_product( array(5,5,2,10) );                           //计算并返回数组的乘积 500 (PHP 5.3.6 起，空数组的乘积为 1, 前 0)
    array_sum( array("a"=>52.2,"b"=>13.7,"c"=>0.9) );           //返回数组中所有值的和 66.8

    //大小写
    array_change_key_case($age,CASE_UPPER);     //将数组的所有的键都转换为大写字母或小写字母
                                CASE_LOWER

    //分割
    array_chunk($cars,2[,true]);    //数组分割为带有两个元素的二维数组
                        true - 保留原始数组中的键名
                        false - 默认。每个结果数组使用从零开始的新数组索引。

    //提取
    array_column($cars, 'last_name'                     [,index_key]);   //返回输入数组中某个单一列的值
                        索引数组的列的整数索引         返回数组的索引/键的列。
                        关联数组的列的字符串键值

    array_keys( array(10,20,30,"10")[, "10"][, true]);      //返回包含数组中所有键名的一个新数组 Array ( [0] => 3 )
                                      限制   全等比较
    array_values( array )                                   //返回数组的所有值

    array_slice( array("red","green","blue","yellow","brown"), 1[, 3][,true]) );    //从第2个元素开始, 取3个，并返回。保留键名
    array_slice( array("red","green","blue","yellow","brown"), -4[, -2][,false]) );    //从倒数第4个元素开始, 取至倒数第2个，并返回。重置键名(默认)

    extract($my_array)   从数组中将变量导入到当前的符号表。

    //查找
    array_key_exists(0,$array)===FALSE;     //检查键名是否存在于数组中
    in_array(search, array, type) === true  //检查值是否存在于数组中
                            true: 区分大小写

    array_search(5, array("a"=>"5","b"=>5,"c"=>"5"), true); //搜索某个键值，并返回第一次对应的键名(PHP 4.2.0 后,失败返false,前返null)
                                                    类型检查    //b  若参数无效，函数返回 NULL

    //合并
    array_combine($array_fname,$array_age);  //通过合并两个数组来创建一个新数组, 如果两个数组的元素个数不匹配，则返回 FALSE。
                        键名      键值
    array_merge(array1,array2,array3);              //合并为一个数组, 相同键名后元素覆盖
    array_merge_recursive(array1,array2,array3);    //合并为一个数组, 相同键名递归建成组
    array_merge(array(3=>"red",4=>"green")));   //或用array_merge_recursive 特例: 键名是整数且只有一个数组  Array ( [0] => red [1] => green )

    //过滤
    array_filter()  //备用
    array_unique( array("a"=>"red","b"=>"green","c"=>"red") [,sortingtype] );  //移除数组中第一个外的重复的值，并返回结果数组。
                                                            SORT_STRING - 默认。把项目作为字符串来比较。
                                                            SORT_REGULAR - 把每一项按常规顺序排列（Standard ASCII，不改变类型）。
                                                            SORT_NUMERIC - 把每一项作为数字来处理。
                                                            SORT_LOCALE_STRING - 把每一项作为字符串来处理，基于当前区域设置（可通过 setlocale() 进行更改）。

    //差集(包括了所有在基数组中，但是不在任何其他参数数组中的元素)
    {
    $a1=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");   //基数组
    $a2=array("e"=>"red","f"=>"black","g"=>"blue");
    $a3=array("a"=>"red","b"=>"black","h"=>"yellow");
    $result=array_diff($a1,$a2,$a3);                    //仅有值用于比较。
    print_r($result);                                   //Array ( [b] => green [c] => blue )
    }
    array_diff_key()        //只比较键名
    array_udiff()           //用自定义函数比较键值
    array_diff_assoc()      //同时满足键值对才算相同
    array_udiff_assoc()     //同上, 自定义比较函数
    array_diff_ukey(array1,array2,array3...,myfunction);    //备用, 比键名(自定义比较函数)
    array_diff_uassoc(array1,array2,array3...,myfunction);  //备用, 比键名和值(自定义比较函数)
    array_udiff_uassoc(array1,array2,array3...,myfunction);  //同上, 自定义比较函数
    //交集(似差集)
    array_intersect()           //只比较值(键于基数组为准)
    array_uintersect()          //同上, 用自定义函数
    array_intersect_key()       //只比较键(值于基数组为准) 也可以是索引
    array_intersect_assoc()     //比较键与值
    array_uintersect_assoc()    //同上, 用自定义函数
    array_intersect_ukey()      //备用 比较两个数组的键名（使用用户自定义函数比较键名）
    array_intersect_uassoc()    //备用 比较两个数组的键名和键值（使用用户自定义函数比较键名）
    array_uintersect_uassoc()   //同上, 用自定义函数


    //排序(值可根据字母与数字)
    array_multisort($array_1, $array_2)   //对数组排序, 直接影响原数组

    sort() - 以升序对数组排序   - usort
    rsort() - 以降序对数组排序
    asort() - 根据值，以升序对关联数组进行排序  - uasort
    ksort() - 根据键，以升序对关联数组进行排序  - uksort
    arsort() - 根据值，以降序对关联数组进行排序
    krsort() - 根据键，以降序对关联数组进行排序
    array_reverse( array("a"=>"Volvo","b"=>"BMW","c"=>"Toyota")[,true] );   //元素顺序翻转，创建新的数组并返回。
                                                                true: 元素的键名保持不变
                                                                false: 元素的键名将丢失(重新索引)

    natcasesort(array)  //用"自然排序"算法对数组进行排序。键值保留它们原始的键名。对大小写不敏感。
    natsort(array)      //同上,对大小写敏感。

    shuffle(array)      //数组中的元素按随机顺序重新排序

    //填充 扩展 删除 替换
    array_fill(3,4,"blue");     //用值 Array ( [3] => blue [4] => blue [5] => blue [6] => blue )
    array_fill_keys(array("a","b","c","d"),"blue");     //键和值   Array ( [a] => blue [b] => blue [c] => blue [d] => blue )

    array_pad(array("red","green"),5,"blue"));       //Array ( [0] => red [1] => green [2] => blue [3] => blue [4] => blue )
                                  负数: 从数组头扩展
    array_pop(array);                   //删除数组中的最后一个元素
                                          返回数组的最后一个值。如果数组是空的，或者非数组，将返回 NULL。
    array_push(array,value1,value2);    //向数组尾部添加一个或多个元素, 添加的元素始终是数字键
                                          返回新数组的元素个数。如果第一个参数不是数组，array_push() 将发出一条警告.
    $array[] = $value;                  //向数组尾部添加一个元素
                                          如果第一个参数不是数组，将新建一个数组。
    array_shift( array("a"=>"red","b"=>"green","c"=>"blue") );  //red 删除数组中的第一个元素(如果键名是数字则重索引)
                                                                  返回被删除元素的值, 数组为空则返回 NULL。
    array_unshift( array, value1, value2, value3);              //向数组开头插入新元素, 返新数组长度。
                            保留顺序
    array_splice( array, start [,length] [,array] )             //移除元素, 返回被移除元素的数组
                                       要带入替换的数组

    array_replace(array1,array2,array3...)              //使用后面数组的值替换第一个数组的值。
    array_replace_recursive(array1,array2,array3...)    //似上, 但是针对的是二维数组(如果没有为每个数组指定一个键, 则等同)
    //$a1=array("a"=>array("red"),"b"=>array("green","blue"),);
    //$a2=array("a"=>array("yellow"),"b"=>array("black"));
    //$result=array_replace_recursive($a1,$a2);



    //反转 键与值 (失败返NULL)
    array_flip( array("a"=>"red","b"=>"green","c"=>"green","d"=>"yellow") );    //Array ( [red] => a [green] => c [yellow] => d )

    //逐个处理
    //array_map()                       将函数作用到数组中的每个值上
    --------------------------------------------------------------
    function myfunction($v)
    {
      return($v*$v);
    }
    $a=array(1,2,3,4,5);
    print_r(array_map("myfunction",$a));    //Array ( [0] => 1 [1] => 4 [2] => 9 [3] => 16 [4] => 25 )
    --------------------------------------------------------------
    function myfunction($v1,$v2)
    {
    if ($v1===$v2)
      {
      return "same";
      }
    return "different";
    }
    $a1=array("Horse","Dog","Cat");
    $a2=array("Cow","Dog","Rat");
    print_r(array_map("myfunction",$a1,$a2));   //Array ( [0] => different [1] => same [2] => different )
    --------------------------------------------------------------
    $a1=array("Dog","Cat");
    $a2=array("Puppy","Kitten");
    print_r(array_map(null,$a1,$a2));           //Array ( [0] => Array ( [0] => Dog [1] => Puppy ) [1] => Array ( [0] => Cat [1] => Kitten ) )

    //array_reduce($a,"myfunction"[,initial])     向用户自定义函数发送数组中的值，并返回一个字符串。如果数组是空的且未传递初始值 initial 参数，该函数返回 NULL。
    -------------------------------------------------------------
    function myfunction($v1,$v2)
    {
    return $v1 . "-" . $v2;
    }
    $a=array("Dog","Cat","Horse");
    print_r(array_reduce($a,"myfunction",5));   // 5-Dog-Cat-Ho
    --------------------------------------------------------------
    function myfunction($v1,$v2)
    {
    return $v1+$v2;
    }
    $a=array(10,15,20);
    print_r(array_reduce($a,"myfunction",5));   // 50
    --------------------------------------------------------------

    //array_walk(array,myfunction,userdata...)  //对一维数组中的每个元素应用自定义函数(可通过引用 & 修改元素)
    //array_walk_recursive(array,myfunction,parameter...)   //似上, 可递归操作多维数组
    -------------------------------------------------------------
    function myfunction( &$value,$key)
    {
    echo "The key $key has the value $value<br>";
    }
    $a=array("a"=>"red","b"=>"green","c"=>"blue");
    array_walk($a,"myfunction");


    //抽取
    array_rand($a, 2);  从数组中随机选出一个或多个元素，并返回。如果选出的元素不止一个，则返回包含随机键名的数组，否则返回该元素的键名。
    srand(mktime()); echo(rand());  //srand()播下随机数发生器种子。 已淘汰


    //遍历操作函数
    current()   - 输出数组中的当前元素的值,   别名: pos()
    key()       - 返回数组内部指针当前指向元素的键名
    end()       - 将内部指针指向数组中的最后一个元素，并输出
    next()      - 将内部指针指向数组中的下一个元素，并输出
    prev()      - 将内部指针指向数组中的上一个元素，并输出
    reset()     - 将内部指针指向数组中的第一个元素，并输出
    each()      - 返回当前元素的键名和键值，并将内部指针向前移动

    */
}

//移除数组中指定值
function array_remove_from_value( & $arrayRes, $value )
{
    $key = array_search($value, $arrayRes);
    if( $key === false )
    {
        return $arrayRes;
    }
    else
    {
        array_splice($arrayRes, $key, 1);
        array_remove_from_value($arrayRes, $value);
    }
}

//产生一个一维测试数组
function array_make_a_test_array( $v_int_way=NULL )    //NULL:没有键  0:数字为键  1:字符串为键
{
    switch( $v_int_way )
    {
        case( NULL ):
            return array( 101, 102, 103, 104, 105, 107, 106, 108, 105, 110 );

        case( 0 ):
            return array( '0'=>101, '1'=>102, '2'=>103, '3'=>104, '4'=>105, '5'=>107, '6'=>106, '8'=>108, '7'=>105, '10'=>110 );

        case( 1 ):
            return array( '零'=>101, '一'=>102, '二'=>103, '三'=>104, '四'=>105, '五'=>107, '六'=>106, '八'=>108, '七'=>105, '十'=>110 );

        default:
            return array();
    }
}

//产生一个二维测试数组
function array_2_make_a_test_array()
{
    $r_array_2 = array();

    $r_array_2['first']     = array( 000, 001, 002, 003, 004, 005, 006, 007);
    $r_array_2['second']    = array( 100, 101, 102, 103, 104, 105, 106, 107);
    $r_array_2['third']     = array( 200, 201, 202, 203, 204, 205, 206, 207);

    return $r_array_2;
}

//从规整的二维数组(索引作为数组键)中, 提取键名作为数组索引来重建二维数组
function arrays_flip($arraysOld)
{
    $arraysNew = array();
    $arrayKeys = array_keys($arraysOld[0]);

    //建二维数组框架
    foreach($arrayKeys as $strKey)
        $arraysNew[$strKey] = array();

    //逐个赋值
    foreach($arraysOld as $arrayRow)
        foreach($arrayRow as $key=>$value)
            array_push($arraysNew[$key], $value);

    return $arraysNew;
}

//二维数组取出一维(原生函数 array_olumn() 扩展)
function array_2v_take_1v( $v_array_original, $v_original_key_name=NULL )    //arrya, str
{
    $r_array = array();

    if( $v_original_key_name !== NULL )
        foreach( $v_array_original as $each_array )
        {
            array_push( $r_array, $each_array[$v_original_key_name] );
        }
    else
        foreach( $v_array_original as $each_array )
        {
            foreach( $each_array as $each_data )
            {
                array_push( $r_array, $each_data );
            }
        }

    return $r_array;
}

//在数组中找出含有指定串的元素
function array_take_element_from_array( $v_array_original, $v_sub_str )
{
    $r_array = array();
    foreach( $v_array_original as $each_str )
    {
        if( strpos($each_str, $v_sub_str) !== false )
            array_push($r_array, $each_str);
    }
    return $r_array;
}

//对数组中每个元素进行提取后返回
function array_operation_each_element( $v_array_original, $v_index_begin=0, $v_length=NULL )
{
    $r_array = array();

    if( $v_length === NULL )
    {
        foreach( $v_array_original as $each_str )
            array_push( $r_array, substr( $each_str, $v_index_begin ) );
    }
    else
    {
        foreach( $v_array_original as $each_str )
            array_push( $r_array, substr( $each_str, $v_index_begin, $v_length ) );
    }

    return $r_array;
}