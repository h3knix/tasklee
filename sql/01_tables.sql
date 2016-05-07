
create table `task` (
	`id` int unsigned not null AUTO_INCREMENT
	
	,`name` varchar(255) not null default ''
	,`is_complete` tinyint not null default 0
	,`completed` datetime not null default '0000-00-00 00:00:00'
	
	,`created` datetime not null
	,`modified` datetime not null default '0000-00-00 00:00:00'
	
	,primary key (`id`)
) engine=innodb;
