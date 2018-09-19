<?php namespace MariuszAnuszkiewicz\classes\Config;

class Config {

    protected static $settings = array();

    public static function get($key) {
        return isset(self::$settings[$key]) ? self::$settings[$key] : null;
    }

    public static function set($key, $value) {
        self::$settings[$key] = $value;
    }
}

// database

Config::set('db.host', 'localhost');
Config::set('db.user', 'root');
Config::set('db.password', '');
Config::set('db.db_name', 'league_manager');
