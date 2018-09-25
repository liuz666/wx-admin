<?php
return array(
	//'配置项'=>'配置值'
    //数据库配置信息
    'DB_TYPE'   => 'mysqli', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'wx', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'root', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    'DB_CHARSET'=> 'utf8',
    'TMPL_L_DELIM' => '{<',
    'TMPL_R_DELIM' => '>}',
    'URL_CASE_INSENSITIVE' => true,  // URL区分大小写 //
    'SESSION_AUTO_START'   =>  true,  //开启session
    // 'USER_AUTH_GATEWAY'    =>'/Home/Login/login',// 默认认证网关
);