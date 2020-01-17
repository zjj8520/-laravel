<?php
$user = 'root';
$pass = 'root';
$dsn = 'mysql:host=127.0.0.1;port=3306;dbname=test';
$pdo = new PDO($dsn, $user, $pass);
$pdo->query("set names utf8");//解码
$name = "select * from `name` ";
$recordset = $pdo->query($name)->rowCount();
print_r($recordset);

if (!empty($_REQUEST['t1']) and !empty($_REQUEST['name'])) {
    $t1 = $_REQUEST['t1'];
    $name = $_REQUEST['name'];
    $date = date('Y-m-d H:i:s');
    $s_t = "select time from `appointment` WHERE  time = '$t1'";
    $recordset = $pdo->query($s_t);
    $result = $recordset->fetchAll();
    if ($result) {
        echo "<script>alert('抱歉$t1 场次已经有人预约，请您预约其他时间段')</script>";
    } else {
        $sql = "insert into `appointment` (`time`,`name`,`date`)values('$t1','$name','$date')";
        $res = $pdo->exec($sql);
        if ($res) {
            echo "<script>alert('预约时间成功！请按时到场')</script>";
        }
    }
}
$s_t2 = "select * from `appointment`";
$box = $pdo->query($s_t2);
$dat = $box->fetchAll();
$t_data = array_column($dat, 'time');
$n_data = array_column($dat, 'name');
if (!empty($_POST['sql'])) {
    $delsql = "$_POST[sql]";
    $results = $pdo->exec($delsql);
    if ($results) {
        echo "<script>alert('执行成功')</script>";
    }
}
?>

<html<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>练车约时间系统</title>
</head>
<body>
<div id="timeShow"></div>
<div>注释：已经被预订的场次会被标注成红色并且不可选取</div>
<form method="post" href="./456.php" onSubmit="return check();" name="testform">
    <table>
        <th>选择</th>
        <th>时间</th>
        <?php
        $array_time = array('6:30-7:00', '7:00-7:30', '7:30-8:00', '8:00-8:30', '8:30-9:00', '9:00-9:30', '9:30-10:00', '10:00-10:30', '10:30-11:00', '11:00-11:30', '11:30-12:00', '13:00-13:30', '13:30-14:00', '14:00-14:30', '14:30-15:00');
        foreach ($array_time as $v) {
            foreach ($t_data as $sk => $sv) {
                if ($sv == $v) {
                    echo "
           <tr>
            <td><input type=\"radio\" name=\"t1\" value=\"$v\" disabled=\"disabled\"></td>
            <td style='color: #ff0013'>$v $n_data[$sk]</td>
           </tr>
           ";
                }
            };
            echo "
           <tr>
            <td><input type=\"radio\" name=\"t1\" value=\"$v\"></td>
            <td >$v</td>
           </tr>
           ";
        };
        ?>
    </table>
    <input type="text" placeholder="请输入您的姓名" name="name">
    <input type="submit" value="提交">
</form>
<form method="post" href="./456.php">
    <input type="text" placeholder="系统管理员sql输入器，此项学员请跳过" name="sql" style="width: 400px">
    <input type="submit" value="执行">
</form>
</body>
</html>
<!--限制提交-->
<script type="text/javascript">
    function check() {
        var myDate = new Date();//获取系统当前时间
        var dateHours = myDate.getHours();
        if (dateHours >= 19) {
            if (testform.t1.value == "") {
                alert('请选择一个时间段~');
                return false;
            }
            if (testform.name.value == "") {
                alert('请填写姓名~姓名不能为空');
                return false;
            }
        } else {
            alert('当前还不到19点，请您19点准时前往这里约时间');
            return false;
        }
    }
</script>
<!--时间自动更新-->
<script type="text/javascript">
    var t = null;

    function time() {
        dt = new Date();
        var y = dt.getFullYear();
        var h = dt.getHours();
        var m = dt.getMinutes();
        var s = dt.getSeconds();
        document.getElementById("timeShow").innerHTML = "当前时间：" + y + "年" + h + "时" + m + "分" + s + "秒";
        t = setTimeout(time, 1000);
    }

    window.onload = function () {
        time()
    }
</script>

