<?php
$user = "root";
$password = "12345678";
$host = "localhost:3306";
$db = "robotest";
$link = mysqli_connect("$host", "$user", "$password", "$db") or die("Ошибка подключения");

$option = [
        "all" => "SELECT user.id,  user.last_name, user.first_name, user.middle_name, position.name as position, position.salary as salary, department.description, user_position.created_at as date_admission, dismission_reason.description as dismission_reason, user_dismission.created_at as date_dissmission
        FROM user
            JOIN department ON department.id = (SELECT department_id FROM user_position WHERE department_id = department.id and user_id = user.id)
            JOIN position ON position.id = (SELECT position_id FROM user_position WHERE user_id = user.id)
            JOIN user_position ON user.id = user_position.user_id
            LEFT JOIN dismission_reason ON dismission_reason.id = (SELECT user_dismission.reason_id FROM user_dismission WHERE user_id = user.id)
            LEFT JOIN user_dismission ON user.id = user_dismission.user_id
            LIMIT $art,$cntRow;",
        "dissmission" => "SELECT user.id,  user.last_name, user.first_name, user.middle_name, position.name as position, position.salary as salary, department.description, user_position.created_at as date_admission, dismission_reason.description as dismission_reason, user_dismission.created_at as date_dissmission
        FROM user
            JOIN department ON department.id = (SELECT department_id FROM user_position WHERE department_id = department.id and user_id = user.id)
            JOIN position ON position.id = (SELECT position_id FROM user_position WHERE user_id = user.id)
            JOIN user_position ON user.id = user_position.user_id
            JOIN dismission_reason ON dismission_reason.id = (SELECT user_dismission.reason_id FROM user_dismission WHERE user_id = user.id)
            JOIN user_dismission ON user.id = user_dismission.user_id"
];

if (isset($_GET['page'])){
    $page = $_GET['page'];
}else {
    $page = 1;
    }

$cntRow = 10;
$art = ($page * $cntRow) - $cntRow;

$res = mysqli_query($link, "SELECT COUNT(*) FROM user");
$row = mysqli_fetch_row($res);
$total = $row[0];

$cntPage = ceil($total / $cntRow);



    if (isset($_GET["option"])) {
        $show = $_GET['option'];
        $result = mysqli_query($link, "SELECT user.id,  user.last_name, user.first_name, user.middle_name, position.name as position, position.salary as salary, department.description, user_position.created_at as date_admission, dismission_reason.description as dismission_reason, user_dismission.created_at as date_dissmission
        FROM user
            JOIN department ON department.id = (SELECT department_id FROM user_position WHERE department_id = department.id and user_id = user.id)
            JOIN position ON position.id = (SELECT position_id FROM user_position WHERE user_id = user.id)
            JOIN user_position ON user.id = user_position.user_id
            LEFT JOIN dismission_reason ON dismission_reason.id = (SELECT user_dismission.reason_id FROM user_dismission WHERE user_id = user.id)
            LEFT JOIN user_dismission ON user.id = user_dismission.user_id
            LIMIT $art,$cntRow ");
    }



//if (isset($_GET["option"])) {
//    $result = mysqli_query($link, "SELECT user.id,  user.last_name, user.first_name, user.middle_name, position.name as position, position.salary as salary, department.description, user_position.created_at as date_admission, dismission_reason.description as dismission_reason, user_dismission.created_at as date_dissmission
//        FROM user
//            JOIN department ON department.id = (SELECT department_id FROM user_position WHERE department_id = department.id and user_id = user.id)
//            JOIN position ON position.id = (SELECT position_id FROM user_position WHERE user_id = user.id)
//            JOIN user_position ON user.id = user_position.user_id
//            JOIN dismission_reason ON dismission_reason.id = (SELECT user_dismission.reason_id FROM user_dismission WHERE user_id = user.id)
//            JOIN user_dismission ON user.id = user_dismission.user_id");
//}

echo "<b>Текущая страница</b> " . $page . "<br>";
?>
<a href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1)."&option=".$show; } ?>">Предыдущая страница</a>
<a href="<?php if($page >= $cntPage){ echo '#'; } else { echo "?page=".($page + 1)."&option=".$show; } ?>">Следующая страница</a>

<form action="index.php" method="GET">
    <input type="radio" name="option" value="all" checked>Все
    <input type="radio" name="option" value="dissmission">Уволенные
    <input type="radio" name="option" value="trialPeriod">Испытаткльный срок
    <input type="radio" name="option" value="chief">Начальники
    <input type="submit" value="Отправить" formaction="index.php">
</form>

<table width="100%" border="1">
    <tr>
        <td>
            <b>Фамиля
        </td>
        <td>
            <b>Имя
        </td>
        <td>
            <b>Отчество
        </td>
        <td>
            <b>Отдел
        </td>
        <td>
            <b>Должность
        </td>
        <td>
            <b>Дата приема
        </td>
        <td>
            <b>Размер ЗП
        </td>
        <td>
            <b>Причина увольнения
        </td>
        <td>
            <b>Дата увольнения
        </td>
</tr>
<?php
while ($r = mysqli_fetch_assoc($result)) {
    ?><tr>
        <td><?=$r["last_name"];?></td>
        <td><?=$r["first_name"];?></td>
        <td><?=$r["middle_name"];?></td>
        <td><?=$r["description"];?></td>
        <td><?=$r["position"];?></td>
        <td><?=$r["date_admission"];?></td>
        <td><?=$r["salary"];?> ₽</td>
        <td><?=$r["dismission_reason"];?></td>
        <td><?=$r["date_dissmission"];?></td>
    </tr><?php
}

?></table>
