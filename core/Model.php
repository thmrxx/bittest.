<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 16:23
 */

namespace core;

/**
 * Base Model
 * @package core
 *
 * @property int $id
 */
class Model
{
    /**
     * @var string
     */
    public static $tableName;

    /**
     * @var int
     */
    public $id;

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * @param integer $id
     * @return null|Model
     */
    public static function findById($id)
    {
        $sql = 'SELECT * FROM ' . static::$tableName . ' WHERE `id` = ?';
        $stmt = App::$db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows ? static::createModel($result->fetch_array()) : null;
    }

    /**
     * @param $attributes
     * @return static
     */
    public static function createModel($attributes)
    {
        $model = new static();
        foreach ($attributes as $key => $value) {
            $model->$key = $value;
        }

        return $model;
    }

    /**
     * @return bool
     */
    public function isLoaded()
    {
        return $this->id > 0;
    }
}