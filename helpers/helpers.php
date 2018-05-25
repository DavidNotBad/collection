<?php

/**
 * 获取最后执行的sql
 * @param int $line     获取sql的条数
 * @return array|string
 */
function get_last_sql($line = 1)
{
    $basePath = storage_path('logs'.DIRECTORY_SEPARATOR.'sql');
    $filePath = collect(glob($basePath.'/*.log'))->sort()->last();
    $sqlStr = rtrim(preg_replace('/\[.*?\]\ /', '', get_last_lines($filePath, $line)), PHP_EOL);
    return ($line == 1) ? $sqlStr : explode(PHP_EOL, $sqlStr);
}


/**
 * 获取文件的最后几行字符
 * 备注:
 *      1. 获取的行是倒序排列的
 *      2. 要求文件中的行结束符要一致
 * @param $file             读取的文件
 * @param int $line         从文件中抽取的行数
 * @param bool $isFilter    是否过滤空行
 * @return string
 */
function get_last_lines($file,$line = 1, $isFilter = true){
    //读取第一行
    $fp=fopen($file,'r');

    //获取文件的行分割符
    $firstLine = fgets($fp);
    $delimitar = strpos($firstLine, "\r\n") ? "\r\n" : (strpos($firstLine, "\n") ? "\n" : "\r");
    $delimitarLength = strlen($delimitar);

    $pos = -$delimitarLength;      //偏移量
    $eof = " ";     //行尾标识
    $data = "";

    while ($line > 0){//逐行遍历
        while ($eof != $delimitar){ //不是行尾
            fseek($fp, $pos, SEEK_END);//fseek成功返回0，失败返回-1
            $eof = fgetc($fp);//读取一个字符并赋给行尾标识
            ($delimitarLength==2) && $eof .= fgetc($fp);
            $pos -= $delimitarLength;//向前偏移
        }

        $eof = " "; //重置行尾标识
        $getLineStr = fgets($fp); //读取一行数据
        //过滤数据
        if($isFilter && !$getLineStr){
            continue;
        }

        $data .= $getLineStr;
        $line--;
    }

    fclose($fp);
    return $data;
}