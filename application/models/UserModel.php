<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 7:05
 */

namespace application\models;

use core\App;
use core\Exception;
use core\Model;

/**
 * Class User
 * @package application\models
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $hash
 */
class UserModel extends Model
{
    public static $tableName = 'user';
    public static $accountTableName = 'account';

    /**
     * @param string $username
     * @return UserModel|null
     */
    public static function findByUsername($username)
    {
        $sql = 'SELECT * FROM ' . static::$tableName . ' WHERE `username` = ?';
        $stmt = App::$db->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows ? static::createModel($result->fetch_array()) : null;
    }

    /**
     * @param string $password
     * @return string
     */
    public static function passwordHash($password)
    {
        return md5(md5($password));
    }

    /**
     * @param string $sessionHash
     * @return string
     */
    public static function generateSessionHash($sessionHash)
    {
        $ip = md5(md5($_SERVER['SERVER_ADDR']));

        return md5($ip . $sessionHash);
    }

    /**
     * @param string $hash
     * @return bool
     */
    public function updateSessionHash($hash)
    {
        if (!$this->isLoaded()) {
            return false;
        }

        $sql = 'UPDATE ' . static::$tableName . ' SET `hash` = ? WHERE `id` = ?';
        $stmt = App::$db->prepare($sql);
        $stmt->bind_param('si', $hash, $this->id);

        return $stmt->execute();
    }

    /**
     * @return int
     */
    public function getBalance()
    {
        $status = 'success';
        $sql = 'SELECT SUM(`value`) FROM ' . static::$accountTableName . ' WHERE `user_id` = ? AND `status` = ?';
        $stmt = App::$db->prepare($sql);
        $stmt->bind_param('is', $this->id, $status);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows ? $result->fetch_array()[0] : 0;
    }

    /**
     * @return array
     */
    public function getAccountHistory()
    {
        $status = 'new';
        $sql = 'SELECT * FROM ' . static::$accountTableName . ' WHERE `user_id` = ? AND `status` != ? ORDER BY `id` DESC';
        $stmt = App::$db->prepare($sql);
        $stmt->bind_param('is', $this->id, $status);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * @param float $value
     * @return bool|string
     */
    public function createPay($value)
    {
        $value = -1 * abs($value);
        $hash = md5(md5(time() + $this->getBalance()));
        $stmt = App::$db->prepare('INSERT INTO `' . static::$accountTableName . '` (`user_id`, `value`, `hash`) VALUES (?, ?, ?)');
        $stmt->bind_param('ids', $this->id, $value, $hash);
        $result = $stmt->execute();

        return $result ? $hash : false;
    }

    /**
     * @param string $hash
     * @return bool
     */
    public function pay($hash)
    {
        try {
            $db = App::$db;
            $db->begin_transaction();

            // get "new" pay
            $status = 'new';
            $sql = 'SELECT * FROM ' . static::$accountTableName . ' WHERE `hash` = ? AND `status` = ? FOR UPDATE';
            $stmt = $db->prepare($sql);
            $stmt->bind_param('ss', $hash, $status);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result->num_rows) {
                $db->rollback();
                App::$logger->error('Transaction error: pay ' . $hash . ' not found');

                return false;
            }

            $pay = $result->fetch_assoc();
            $this->setPayStatus($pay['id'], 'process');

            $value = (float)$pay['value'];

            $balance = $this->getBalance();

            if ($balance + $value < 0) {
                $db->rollback();
                App::$logger->error('Transaction error: user do not have enough money');
                $this->setPayStatus($pay['id'], 'failed');

                return false;
            }

            $sql = 'UPDATE ' . static::$accountTableName . ' SET `status` = "success" WHERE `hash` = ?';
            $stmt = $db->prepare($sql);
            $stmt->bind_param('s', $hash);
            $result = $stmt->execute();

            if (!$result) {
                $db->rollback();
                App::$logger->error('Transaction error: could not update pay status');
                $this->setPayStatus($pay['id'], 'failed');

                return false;
            }

            $this->setPayStatus($pay['id'], 'success');
            App::$logger->info('Success transaction: ' . $hash);
            $db->commit();

            return true;

        } catch (Exception $e) {
            $db->rollback();
        }
    }

    /**
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function setPayStatus($id, $status)
    {
        $id = (int)$id;
        $sql = 'UPDATE ' . static::$accountTableName . ' SET `status` = ? WHERE `id` = ?';
        $stmt = App::$db->prepare($sql);
        $stmt->bind_param('si', $status, $id);
        $result = $stmt->execute();

        return $result;
    }
}