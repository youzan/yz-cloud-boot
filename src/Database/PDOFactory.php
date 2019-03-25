<?php

namespace YouzanCloudBoot\Database;

use PDO;
use YouzanCloudBoot\Component\BaseComponent;

class PDOFactory extends BaseComponent
{

    /**
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * @param $options
     * @return PDO
     */
    public function buildMySQLInstance($host, $port, $username, $password, $options): PDO
    {
        return new PDO(
            'mysql:host=' . $host . 'port=' . $port,
            $username,
            $password,
            $options
        );
    }


    /**
     * 获取有赞云内建的（如果有）
     *
     * @param string $charset 字符集，默认 utf8mb4
     * @return PDO
     */
    public function buildBuiltinMySQLInstance($charset = 'utf8mb4'): ?PDO
    {
        $env = $this->getEnvUtil();

        $host = $env->get('mysql.host');
        if (empty($host)) {
            return null;
        }
        $port = $env->get('mysql.port');
        $username = $env->get('mysql.username');
        $password = $env->get('mysql.password');
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names ' . $charset
        ];

        return $this->buildMySQLInstance($host, $port, $username, $password, $options);
    }

}