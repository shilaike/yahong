<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'   => 'sqlsrv', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'UFSMBJPB', // 数据库名
    'DB_USER'   => 'sa', // 用户名
    'DB_PWD'    => '123', // 密码
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
    ),
);