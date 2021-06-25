<?php/** * Created by PhpStorm. * User: Лука * Date: 07.05.2019 * Time: 21:54 */class DBConfig{    var $db_host = "localhost";    var $db_name = "vh379174_telegram";    var $db_user = "vh379174_root";    var $db_pass = "R3c6H1q5";    var $db_con = null;    public function __construct()    {        try        {            $this->db_con = new PDO("mysql:host={$this->db_host};dbname={$this->db_name}", $this->db_user, $this->db_pass);            $this->db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);            $this->db_con->exec("set names utf8");        } catch (PDOException $e)        {            echo $e->getMessage();        }    }    public function addUser($id, $name, $username)    {        $request = $this->db_con->prepare('INSERT INTO `sarafan_users`(`telegram_id`,`telegram_name`,`telegram_username`,`register_date`) VALUES (:param1,:param2,:param3,:param4)');        $request->execute(array(            ':param1' => $id,            ':param2' => $name,            ':param3' => $username,            ':param4' => (new DateTime('now'))->format('Y-m-d H:i:s')        ));    }    public function deleterUser($id)    {        $request = $this->db_con->prepare('delete from `sarafan_users` where `telegram_id`=:param1 limit 1');        $request->execute(array(            ':param1' => $id        ));    }    public function getUsersCount($where = '')    {        $request = $this->db_con->prepare('select count(*) as Count from `sarafan_users`' . $where);        $request->execute(array());        $result = $request->fetchAll(PDO::FETCH_OBJ);        $result = $result[0]->Count;        return $result;    }    public function getStageCompleteCount($stage)    {        $request = $this->db_con->prepare("SELECT count(*) as Count FROM `sarafan_users` WHERE `$stage` is not null");        $request->execute(array());        $result = $request->fetchAll(PDO::FETCH_OBJ);        $result = $result[0]->Count;        return $result;    }    public function getFirstStageCompleteStatistic()    {        $request = $this->db_con->prepare('select SUM(UNIX_TIMESTAMP(`first_done_date`) - UNIX_TIMESTAMP(`first_take_date`)) as sum,COUNT(*) as count FROM `sarafan_users` where `first_take_date` is not null and `first_done_date` is not null');        $request->execute(array());        return $request->fetch(PDO::FETCH_OBJ);    }    public function getSecondStageCompleteStatistic()    {        $request = $this->db_con->prepare('select SUM(UNIX_TIMESTAMP(`second_done_date`) - UNIX_TIMESTAMP(`second_take_date`)) as sum,COUNT(*) as count FROM `sarafan_users` where `second_take_date` is not null and `second_done_date` is not null');        $request->execute(array());        return $request->fetch(PDO::FETCH_OBJ);    }    public function getThirdStageCompleteStatistic()    {        $request = $this->db_con->prepare('select SUM(UNIX_TIMESTAMP(`third_done_date`) - UNIX_TIMESTAMP(`third_take_date`)) as sum,COUNT(*) as count FROM `sarafan_users` where `third_take_date` is not null and `third_done_date` is not null');        $request->execute(array());        return $request->fetch(PDO::FETCH_OBJ);    }    public function getActionTypesStatistic()    {        $request = $this->db_con->prepare('SELECT last_action_type,count(*) as count FROM `sarafan_users` GROUP BY last_action_type');        $request->execute(array());        return $request->fetchAll(PDO::FETCH_OBJ);    }    public function setLastActionType($chat_id, $type)    {        $request = $this->db_con->prepare('UPDATE `sarafan_users` SET `last_action_type`=:param1 WHERE `telegram_id`=:param2');        $request->execute(array(            ':param1' => $type,            ':param2' => $chat_id        ));    }    public function userExists($id)    {        $request = $this->db_con->prepare('select count(*) as Count from `sarafan_users` where `telegram_id` = :param1');        $request->execute(array(':param1' => $id));        $result = $request->fetchAll(PDO::FETCH_OBJ);        $result = $result[0]->Count;        return $result > 0;    }    public function getUserByTelegramID($chat_id)    {        $request = $this->db_con->prepare('select * from `sarafan_users` where `telegram_id`=:param1 limit 1');        $request->execute(array(':param1' => $chat_id));        $result = $request->fetchAll(PDO::FETCH_ASSOC);        return count($result) > 0 ? $result[0] : false;    }    public function getUserByUsername($username)    {        $request = $this->db_con->prepare('select * from `sarafan_users` where `telegram_username`=:param1 limit 1');        $request->execute(array(':param1' => $username));        $result = $request->fetchAll(PDO::FETCH_ASSOC);        return count($result) > 0 ? $result[0] : false;    }    public function getUsers($pag_index, $where = '')    {        $offset = $pag_index * 100;        $request = $this->db_con->prepare("select id,telegram_id,telegram_username,telegram_name,phone,register_date,stage,has_access_next_stage from `sarafan_users` " . $where . " order by `register_date` desc limit $offset,100");        $request->execute(array());        $result = $request->fetchAll(PDO::FETCH_ASSOC);        return $result;    }    public function getRegisteredCountBetweenDates($date1,$date2)    {        $request = $this->db_con->prepare('SELECT count(*) as count1 FROM `sarafan_users` WHERE `register_date` BETWEEN :param1 AND :param2');        $request->execute(array(':param1' => $date1,':param2'=>$date2));        return $request->fetch(PDO::FETCH_OBJ)->count1;    }    public function getFilteredUsers($where = '')    {        $request = $this->db_con->prepare("select id,telegram_id,telegram_username,telegram_name,phone,register_date,stage,has_access_next_stage from `sarafan_users` " . $where);        $request->execute(array());        $result = $request->fetchAll(PDO::FETCH_ASSOC);        return $result;    }    public function getStage($chat_id)    {        $request = $this->db_con->prepare('select `stage` from `sarafan_users` where `telegram_id`=:param1 limit 1');        $request->execute(array(':param1' => $chat_id));        $result = $request->fetch(PDO::FETCH_ASSOC);        return $result['stage'];    }    public function setStage($chat_id, $stage)    {        $request = $this->db_con->prepare('UPDATE `sarafan_users` SET `stage`=:param1,`has_access_next_stage`=:param3 WHERE `telegram_id`=:param2 limit 1');        $request->execute(array(            ':param1' => $stage,            ':param2' => $chat_id,            ':param3' => 0        ));    }    public function setNotifyCount($chat_id, $count)    {        $request = $this->db_con->prepare('UPDATE `sarafan_users` SET `notify_count`=:param1 WHERE `telegram_id`=:param2');        $request->execute(array(            ':param1' => $count,            ':param2' => $chat_id        ));    }    public function setAccessToNextStage($chat_id, $step)    {        $request = $this->db_con->prepare('UPDATE `sarafan_users` SET `has_access_next_stage`=:param1 WHERE `telegram_id`=:param2 limit 1');        $request->execute(array(            ':param1' => 1,            ':param2' => $chat_id        ));    }    public function setValue($chat_id, $column, $value)    {        $request = $this->db_con->prepare("UPDATE `sarafan_users` SET `$column`=:param1 WHERE `telegram_id`=:param2 limit 1");        $request->execute(array(            ':param1' => $value,            ':param2' => $chat_id        ));    }    function writeDump($item)    {        ob_flush();        ob_start();        var_dump($item);        file_put_contents("dump.txt", ob_get_flush());    }}