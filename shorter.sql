-- mysql -u root -p < shorter.sql

create database links;
use links;
create table user (
	userid int unsigned not null auto_increment primary key,
	email varchar(100) not null,
	firstName varchar(50) not null,
	lastName varchar(50) not null,
	password char(40) not null
);
create table link (
	linkid int unsigned not null auto_increment primary key,
	userid int unsigned not null,
	initialLink varchar(2048) not null,
	shortedLink varchar(100) not null,
	title varchar(256),
	date date not null,
	count int unsigned
);

grant select, insert, update, delete
on links.*
to linksUser@localhost identified by 'fdsdwp9cmreu';