<?php
$link = $_GET['link'];
$name = $_POST['name'];
$code = $_POST['code'];
$update = $_POST['update'];
//omit the check correct inputs

if(!empty($update) && $link == 'update') {
    $link = mysqli_init();
    if (mysqli_real_connect($link, 'localhost', 'root', '', 'test')) {
        if (mysqli_connect_errno()) {
            printf("Ошибка соединения: %s\n", mysqli_connect_error());
            exit();
        }
        if($stmt = mysqli_prepare($link, 'SELECT name, code FROM test.sms WHERE name=?')) {
            mysqli_stmt_bind_param($stmt, "s", $name);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $name_answer,$code_answer);
            mysqli_stmt_fetch($stmt);
            if($code_answer != null) {
                printf("Ваш код: %s\n", $code_answer);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($link);
    }
}

if(!empty($name) && empty($code) && $link == 'reg') {
    $link = mysqli_init();
    if (mysqli_real_connect($link, 'localhost', 'root', '', 'test')) {
        if (mysqli_connect_errno()) {
            printf("Ошибка соединения: %s\n", mysqli_connect_error());
            exit();
        }
        if($stmt = mysqli_prepare($link, 'SELECT name FROM test.sms WHERE name=?')) {
            mysqli_stmt_bind_param($stmt, "s", $name);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if(!mysqli_stmt_num_rows($stmt)) {
                $rand = rand(1000, 9999);
                if($stmt = mysqli_prepare($link, 'INSERT INTO test.sms(id, name, code) VALUES (0, ?, ?)')) {
                    mysqli_stmt_bind_param($stmt, "si", $name, $rand);
                    mysqli_stmt_execute($stmt);
                }
            } else {
                printf('Ошибка, пользователь существует');
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($link);
    }
}
if(!empty($name) && !empty($code) && $link == 'success') {
    $link = mysqli_init();
    if (mysqli_real_connect($link, 'localhost', 'root', '', 'test')) {
        if (mysqli_connect_errno()) {
            printf("Ошибка соединения: %s\n", mysqli_connect_error());
            exit();
        }
    if($stmt = mysqli_prepare($link, 'SELECT name, code FROM test.sms WHERE name=? AND code=?')) {
        mysqli_stmt_bind_param($stmt, "si", $name,$code);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $name_answer,$code_answer);
        mysqli_stmt_fetch($stmt);
        if($code != $code_answer) {
            printf("Неверный код");
        } else {
            mysqli_query($link, 'UPDATE test.sms SET code=null WHERE name='.$name_answer);
            printf("%s, успешно зарегистрированы с помощью кода %s\n", $name_answer, $code_answer);
        }
        mysqli_stmt_close($stmt);
    }
        mysqli_close($link);
    }
}