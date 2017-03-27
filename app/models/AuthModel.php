<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;

/**
 * класс переписан так, чтобы для аутентификации и авторизации использовались только куки,
 * потому что необходима возможность заходить под одним пользователям с разных компьютеров 
 * и чтобы не слетала утентификация
 * в приложении не требуется разделение прав
 */
class AuthModel extends Model {

    // объект для работы с БД
    private $dbh;

    public function __construct() {
        // передает класса из которого вызывается, для каждого класса свои
        // настройки mysql
        $this->dbh = new MysqlModel(MysqlModel::STKApps);
    }

    /**
     * Авторизация
     */
    public function authorization() {
        //проеверяет ниличие кук
        if (isset($_COOKIE['id_user']) and isset($_COOKIE['code_user'])) {

            $id_user = $_COOKIE['id_user'];
            $code_user = $_COOKIE['code_user'];

            if ($this->dbh->query("SELECT * FROM `session` WHERE `id_user` = ?;", 'num_row', '', array($id_user)) == 1) {

                $data = $this->dbh->query("SELECT * FROM `session` WHERE `id_user` = ?;", 'accos', '', array($id_user));

                if ($data['code_sess'] == $code_user and $data['user_agent_sess'] == 'none') {
                    return true;
                } else {
                    return false;
                }
            } else {

                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Аутентификация
     */
    public function authentication() {
        $login = $_POST['login'];
        $password = md5($_POST['password'] . 'lol');

        if ($this->dbh->query("SELECT * FROM `users` WHERE `login_user` = ? AND `password_user` = ?;", 'num_row', '', array($login, $password)) == 1) {
 
            $id_user = $this->dbh->query("SELECT * FROM `users` WHERE `login_user` = ? AND `password_user` = ?;", 'result', 0, array($login, $password));
            $r_code = $this->generateCode(15);

            if ($this->dbh->query("SELECT * FROM `session` WHERE `id_user` = ?;", 'num_row', '', array($id_user)) == 1) {
                
                $this->dbh->query("UPDATE `session` SET `code_sess` = ?, `user_agent_sess` = ? where `id_user` = ?;", 'none', '', array($r_code, 'none', $id_user));
            } else {
                
                $this->dbh->query("INSERT INTO `session` (`id_user`, `code_sess`, `user_agent_sess`) VALUE (?, ?, ?);", 'none', '', array($id_user, $r_code, 'none'));
            }
            //ставим куки на 2 недели
            setcookie("id_user", $id_user, time() + 3600 * 24 * 14, '/', null, false, true);
            setcookie("code_user", $r_code, time() + 3600 * 24 * 14, '/', null, false, true);
            return true;
        } else {

            //пользователь не найден в БД или пароль неверный
            if ($this->dbh->query("SELECT * FROM `users` WHERE `login_user` = ?;", 'num_row', 0, array($login)) == 1) {
                $error[] = 'Неверный пароль';
            } else {
                $error[] = 'Пользователя не сущестует';
            }
            $this->errors = $error;
            return false;
        }
    }

    public function exit_user() {
        session_start();
        session_destroy();
        setcookie("id_user", '', time() - 3600, '/', null, false, true);
        setcookie("code_user", '', time() - 3600, '/', null, false, true);
        var_dump($_COOKIE);
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/auth';
        header("Location:" . $host);
        exit();
    }

    /**
     * генерирует случайную строку
     */
    private function generateCode($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = '';
        $clean = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clean)];
        }

        return $code;
    }

}
