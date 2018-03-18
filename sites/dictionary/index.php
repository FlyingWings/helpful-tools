<?php
$value = $_POST["input"];
if(empty($value)) $input = "";
else{
    $input = $value;
    $dict = file_get_contents("../texts/dict_ordered.txt");
    preg_match_all("/\n.*?".$value.".*?\n/", $dict, $result);
}
?>
<html>
<head>
    <title>成语大全</title>
    <meta charset='utf-8'/>
</head>

<body>
    <form action="index.php" method="POST">
        <input type="text" name="input" value="<?php !empty($_POST) ? $input:''?>"/>
        <input type="submit" value="提交"/>
        <table border=1>
        <tr><td style="min-width: 200px">成语</td><td>解释</td></tr>
        <?php if(!empty($_POST)){
            foreach($result[0] as $res){
                $res = explode("\t", $res);?>
            <tr><td><?=$res[0]?></td><td><?=$res[1]?></td></tr>
            <?php }
        }else{}
        ?>
        </table>
    </form>
</body>