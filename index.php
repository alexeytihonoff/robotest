<?php
$user = "root";
$password = "12345678";
$host = "localhost:3306";
$db = "robotest";
$link = mysqli_connect("$host", "$user", "$password", "$db") or die("Ошибка подключения");

$query = "SELECT user.id,  user.last_name, user.first_name, user.middle_name, position.name as position, position.salary as salary, department.description, user_position.created_at as date_admission, dismission_reason.description as dismission_reason, user_dismission.created_at as date_dissmission
        FROM user ";

$option = [
        "all" => "JOIN department ON department.id = (SELECT department_id FROM user_position WHERE department_id = department.id and user_id = user.id)
            JOIN position ON position.id = (SELECT position_id FROM user_position WHERE user_id = user.id)
            JOIN user_position ON user.id = user_position.user_id
            LEFT JOIN dismission_reason ON dismission_reason.id = (SELECT user_dismission.reason_id FROM user_dismission WHERE user_id = user.id)
            LEFT JOIN user_dismission ON user.id = user_dismission.user_id
            ORDER BY user.last_name",
        "dissmission" => "JOIN department ON department.id = (SELECT department_id FROM user_position WHERE department_id = department.id and user_id = user.id)
            JOIN position ON position.id = (SELECT position_id FROM user_position WHERE user_id = user.id)
            JOIN user_position ON user.id = user_position.user_id
            JOIN dismission_reason ON dismission_reason.id = (SELECT user_dismission.reason_id FROM user_dismission WHERE user_id = user.id)
            JOIN user_dismission ON user.id = user_dismission.user_id",
    "trialPeriod" => "JOIN department ON department.id = (SELECT department_id FROM user_position WHERE department_id = department.id and user_id = user.id)
            JOIN position ON position.id = (SELECT position_id FROM user_position WHERE user_id = user.id)
            JOIN user_position ON user.id = user_position.user_id
            LEFT JOIN dismission_reason ON dismission_reason.id = (SELECT user_dismission.reason_id FROM user_dismission WHERE user_id = user.id)
            LEFT JOIN user_dismission ON user.id = user_dismission.user_id
            WHERE DATE_ADD(user_position.created_at, INTERVAL 3 MONTH) > CURRENT_TIMESTAMP
        ORDER BY user.last_name",
    "leader" => "JOIN department ON department.id = (SELECT department_id FROM user_position WHERE department_id = department.id and user_id = user.id)
            JOIN position ON position.id = (SELECT position_id FROM user_position WHERE user_id = user.id)
            JOIN user_position ON user.id = user_position.user_id
            LEFT JOIN dismission_reason ON dismission_reason.id = (SELECT user_dismission.reason_id FROM user_dismission WHERE user_id = user.id)
            LEFT JOIN user_dismission ON user.id = user_dismission.user_id
            WHERE user_position.created_at IN (SELECT max(user_position.created_at)
                FROM user_position
                WHERE department_id = department.id)
        ORDER BY user_position.created_at DESC"
];

if (isset($_GET["option"])) {
    $show = $_GET['option'];
    if (isset($_GET['page'])){
        $page = (int)$_GET['page'];
    }else {
        $page = 1;
    }
    $cntRow = 10;
    $art = ($page * $cntRow) - $cntRow;
    $res = mysqli_query($link, "SELECT COUNT(*) FROM user " . $option[$show]);
    $row = mysqli_fetch_row($res);
    $total = $row[0];
    $cntPage = ceil($total / $cntRow);
    $result = mysqli_query($link, $query . $option[$show] . " LIMIT $art,$cntRow");
}

function checked($var, $value = null)
{
    if (is_null($value)) {
        return ($var) ? ' checked' : '';
    } else {
        if (!is_array($var)) {
            $var = explode(',', $var);
        }
        return (in_array($value, $var)) ? ' checked' : '';
    }
}

if (isset($page)) {
    echo "<b>Текущая страница</b> " . $page . "<br>";
}

if ($page > 1) {
    ?><a href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1)."&option=".$show; } ?>">Предыдущая страница</a>&nbsp<?php
}
if ($page < $cntPage) {
    ?><a href="<?php if($page >= $cntPage){ echo '#'; } else { echo "?page=".($page + 1)."&option=".$show; } ?>">Следующая страница</a><?php
}
?>

<form action="index.php" method="GET">
    <input type="radio" name="option" value="all" <?php echo checked($show, "all"); ?>>Все
    <input type="radio" name="option" value="dissmission" <?php echo checked($show, "dissmission"); ?>>Уволенные
    <input type="radio" name="option" value="trialPeriod" <?php echo checked($show, "trialPeriod"); ?>>Испытаткльный срок
    <input type="radio" name="option" value="leader" <?php echo checked($show, "leader"); ?>>Последний нанятый сотрудник
    <input type="submit" value="Отправить" formaction="index.php">
</form>

<table width="100%" border="1">
    <tr>
        <td>
            <b>ID
        </td>
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
        <td><?=$r["id"];?></td>
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
