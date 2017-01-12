<?php
return array(
	"DB_TYPE"    => "mysql",
	"DB_HOST"    => "localhost",
	"DB_NAME"    => "ucenter",
	"DB_USER"    => "root",
	"DB_PWD"     => "root9527",
	"DB_PORT"    => "3306",
	"DB_PREFIX"  => "",
	"DB_CHARSET" => "utf8",
	"server"     =>  array(
		/*
		 * Server Info
		 */
		"appSecret"    => "1000001",
		"token"        => "123456",

		/*
		 * Ticket Checks
		 */
		"ticketChecks" => "all", // session , cookie

		/*
		 * Ticket Store Name
		 */
		"ticketName"   => "ticket",

		/*
		 * Ticke Store Expire Time , second
		 */
		'expire_time'  => 6000,

		/*
		 * Client Application Info
		 */
		'clients'      => array(
			'9001' => array(
				'appId'     => '9001',
				'token'     => '654321',
				'loginUrl'  => 'http://localhost/index.php/admin/SsoClient/login',
				'logoutUrl' => 'http://localhost/index.php/admin/SsoClient/toLogout'
			),
			'9002' => array(
				'appId'     => '9002',
				'token'     => '87654',
				'loginUrl'  => 'http://qm2/index.php/admin/SsoClient/login',
				'logoutUrl' => 'http://qm2/index.php/admin/SsoClient/toLogout'
			)
		)
	)
);