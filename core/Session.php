<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 15:09
 */

namespace core;

/**
 * Class Session
 * @package core
 */
class Session
{
    /**
     * @return bool
     */
    public function isActive()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Open session
     * @param bool $write
     */
    public function open($write = false)
    {
        session_start();
        App::$logger->debug('Session start' . ($write ? ' (write)' : ''));

        if (!$write) {
            $this->writeClose();
        }
    }

    /**
     * Close session
     */
    public function writeClose()
    {
        session_write_close();
        App::$logger->debug('Session write close');
    }

    /**
     * @param string $key
     * @param null|mixed $defaultValue
     * @return null|mixed
     */
    public function get($key, $defaultValue = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $defaultValue;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Session destroy
     */
    public function destroy()
    {
        if ($this->isActive()) {
            session_unset();
            session_destroy();
        }
    }
}