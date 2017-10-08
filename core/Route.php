<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 1:30
 */

namespace core;

/**
 * Class Route
 * @package application\core
 *
 * Url Manager
 */
class Route
{
    /**
     * Route GET parameter name
     */
    const ROUTE_PARAM_NAME = 'r';

    /**
     * @return array
     */
    public static function parseRequest()
    {
        if (!isset($_GET[self::ROUTE_PARAM_NAME])) {
            return [];
        }

        return explode('/', $_GET[self::ROUTE_PARAM_NAME]);
    }

    /**
     * @param string $route
     * @param array $params
     * @param boolean $absolute
     * @return string
     */
    public static function createUrl($route, array $params = [], $absolute = false)
    {
        $url = ($absolute ? App::$baseUrl : '/') . '?' . self::ROUTE_PARAM_NAME . '=' . $route . (count($params) ? '&' . http_build_query($params) : '');

        return $url;
    }
}