<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 6:57
 */

namespace core;

/**
 * Class Database
 * @package core
 */
class Database
{
    /**
     * @var \mysqli
     */
    protected $connection;

    /**
     * Init DB
     * @param array $config
     * @return \mysqli
     * @throws \core\Exception
     */
    public function init(array $config)
    {
        $this->connection = new \mysqli($config['host'], $config['username'], $config['password'], $config['dbname']);

        if ($this->connection->connect_errno) {
            throw new Exception(
                'Database connection Error | Code: ' . $this->connection->connect_errno . ' | Message: ' . $this->connection->connect_error
            );
        }

        register_shutdown_function([$this, 'close']);

        return $this->connection;
    }

    /**
     * Close connection
     */
    public function close()
    {
        $this->connection->close();
        App::$logger->debug('DB connection close');
    }
}