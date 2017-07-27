<?php

namespace ant\core;

class HtmlHelper
{
    public static function arrayToTable(array $dataArray, array $headerArray=array())
    {
        foreach ($headerArray as $index=>$value){
            $headerArray[$index] = "<td>{$value}</td>";
        }
        foreach ($dataArray as $line=>$rowArray){
            foreach ($rowArray as $index=>$value){
                $dataArray[$line][$index] = "<td>{$value}</td>";
            }
            $dataArray[$line] = "<tr>".implode("", $dataArray[$line])."</tr>";
        }
        if(empty($headerArray)){
            return "<table>".implode("", $dataArray)."</table>";
        }else{
            return "<table>".implode("", $headerArray).implode("", $dataArray)."</table>";
        }
    }

    public static function simpleHtml($title, $content)
    {
        return <<<EOF
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$title}</title>
</head>
<body>
{$content}
</body>
</html>
EOF;
    }
}