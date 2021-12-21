<?php
	#DB설정
	// $_cfg['db_host'] = '127.0.0.1';//'172.27.0.248'; // 211.253.27.6
	// $_cfg['db_user'] = 'root'; 
	// $_cfg['db_password'] = 'gksqha2020!!';
	// $_cfg['db_database'] = 'dbHR';
	// $_cfg['dsn']=array("mysql:host=".$_cfg['db_host'].";dbname=".$_cfg['db_database'],$_cfg['db_user'],$_cfg['db_password']);

    #DB설정
	$_cfg['db_host'] = '127.0.0.1';//'172.27.0.248'; // 211.253.27.6
	$_cfg['db_user'] = 'root'; 
	$_cfg['db_password'] = 'hannubi@1234';
	$_cfg['db_database'] = 'dbHR';
	$_cfg['dsn']=array("mysql:host=".$_cfg['db_host'].";dbname=".$_cfg['db_database'],$_cfg['db_user'],$_cfg['db_password']);

	//$_cfg['http']='http://';
	//$_cfg['domain']='211.253.27.6';

?>