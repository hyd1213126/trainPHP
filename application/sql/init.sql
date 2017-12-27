-- 创建库
drop database if exists zhuofuziben2016;

create database zhuofuziben2016 character set utf8;

use zhuofuziben2016;

set names utf8;

-- adminUsers
create table if not exists ZhuoFu2016_adminUsers(
	Auid bigint not null auto_increment,
	Auser varchar(50) not null,
	Passwd char(32) not null,
	Alive bit(1) not null,
	Aulid smallint not null,
	primary key(Auid)
) default charset=utf8;
insert into ZhuoFu2016_adminUsers(Auser,Passwd,Alive,Aulid) values
(
	'topu.net',
	'E10ADC393b5g1s2k7j6u4b8mF20F883E',
	true,
	1
);

-- adminUsersLevel
create table if not exists ZhuoFu2016_adminUsersLevel(
	Aulid smallint not null,
	Ltitle varchar(50) not null,
	Alive bit(1) not null,
	Area varchar(50) not null,
	primary key(Aulid)
) default charset=utf8;
insert into ZhuoFu2016_adminUsersLevel(Aulid,Ltitle,Alive,Area) values
(
	1,
	'系统管理员',
	true,
	'1,1,1,1,1,1,1,1'
);

-- Advertise
create table if not exists ZhuoFu2016_Advertise(
	Aid smallint not null,
	Atitle varchar(50) not null,
	Url varchar(100) not null,
	Pic1 varchar(100) not null,
	primary key(Aid)
) default charset=utf8;
insert into ZhuoFu2016_Advertise(Aid,Atitle,Url,Pic1) values
(
	1,'关于我们-顶部背景图','http://',''
);
insert into ZhuoFu2016_Advertise(Aid,Atitle,Url,Pic1) values
(
	2,'关于我们-标题图片','http://',''
);
insert into ZhuoFu2016_Advertise(Aid,Atitle,Url,Pic1) values
(
	3,'关于我们-公司介绍-中间的Banner','http://',''
);
insert into ZhuoFu2016_Advertise(Aid,Atitle,Url,Pic1) values
(
	4,'关于我们-我们的优势-中间的Banner','http://',''
);
insert into ZhuoFu2016_Advertise(Aid,Atitle,Url,Pic1) values
(
	5,'关于我们-联系我们-微信端关注','http://',''
);
insert into ZhuoFu2016_Advertise(Aid,Atitle,Url,Pic1) values
(
	6,'路演工场-顶部背景图','http://',''
);
insert into ZhuoFu2016_Advertise(Aid,Atitle,Url,Pic1) values
(
	7,'路演工场-标题图片','http://',''
);
insert into ZhuoFu2016_Advertise(Aid,Atitle,Url,Pic1) values
(
	8,'最新资讯-顶部背景图','http://',''
);
insert into ZhuoFu2016_Advertise(Aid,Atitle,Url,Pic1) values
(
	9,'最新资讯-标题图片','http://',''
);

-- Init
create table if not exists ZhuoFu2016_Init(
	Iid smallint not null,
	Iinfo text not null,
	Ititle varchar(500) not null,
	Type smallint not null,
	primary key(Iid)
) default charset=utf8;
insert into ZhuoFu2016_Init(Iid,Iinfo,Ititle,Type) values(
	1,'卓富资本PHP练习','网站名称',1
);
insert into ZhuoFu2016_Init(Iid,Iinfo,Ititle,Type) values(
	2,'15','后台显示记录个数',1
);
insert into ZhuoFu2016_Init(Iid,Iinfo,Ititle,Type) values(
	3,'PHP','网站副名称',1
);
insert into ZhuoFu2016_Init(Iid,Iinfo,Ititle,Type) values(
	4,'SEO优化关键字','默认SEO优化关键字',3
);
insert into ZhuoFu2016_Init(Iid,Iinfo,Ititle,Type) values(
	5,'SEO优化描述','默认SEO优化描述',3
);
insert into ZhuoFu2016_Init(Iid,Iinfo,Ititle,Type) values(
	100,'2016-06-02','上次清空时间',0
);

-- Info
create table if not exists ZhuoFu2016_Info(
	Iid smallint not null,
	Ititle varchar(50) not null,
	Iinfo text not null,
	Type smallint not null,
	primary key(Iid)
) default charset=utf8;
insert into ZhuoFu2016_Info(Iid,Ititle,Iinfo,Type) values(
	1,'底部版权信息','',2
);

-- Article
create table if not exists ZhuoFu2016_Article(
	Aid bigint not null auto_increment,
	Atitle varchar(1000) not null,
	Url varchar(1000) not null,
	Alive bit(1) not null,
	Kind smallint not null,
	Layer bigint not null,
	Ainfo text not null,
	Atime datetime not null,
	Pic1 varchar(100) not null,
	Pic2 varchar(100) not null,
	Summary varchar(1000) not null,
	ieTitle varchar(100) not null,
	seoKeywords varchar(1000) not null,
	seoDescription varchar(1000) not null,
	subTitle varchar(1000) not null,
	Trade_id varchar(1000) not null,
	Area_id varchar(1000) not null,
	Tags_id varchar(1000) not null,
	Source varchar(50) not null,
	New bit(1) not null,
	Person varchar(50) not null,
	Status smallint not null,
	Recommend bit(1) not null,
	primary key(Aid)
) default charset=utf8;

-- Base
create table if not exists ZhuoFu2016_Base(
	Bid bigint not null auto_increment,
	Btitle varchar(50) not null,
	Kind smallint not null,
	Alive bit(1) not null,
	Layer bigint not null,
	primary key(Bid)
) default charset=utf8;

insert into ZhuoFu2016_Base(Btitle,Kind,Alive,Layer) values(
	'行业1',1,1,1
);

insert into ZhuoFu2016_Base(Btitle,Kind,Alive,Layer) values(
	'行业2',1,1,2
);

insert into ZhuoFu2016_Base(Btitle,Kind,Alive,Layer) values(
	'行业3',1,1,3
);

insert into ZhuoFu2016_Base(Btitle,Kind,Alive,Layer) values(
	'行业4',1,1,4
);

-- PPT
create table if not exists ZhuoFu2016_PPT(
	Pid bigint not null auto_increment,
	Aid bigint not null,
	company varchar(100) not null,
	SubTitle varchar(8000) not null,
	Filepath varchar(8000) not null,
	alive bit(1) not null,
	Layer bigint not null,
	primary key(Pid)
) default charset=utf8;

-- Subjects
create table if not exists ZhuoFu2016_Subjects(
	Sid bigint not null auto_increment,
	Stack_Name varchar(50) not null,
	Stack_Code varchar(50) not null,
	Company varchar(100) not null,
	Tid varchar(4000) not null,
	Aid varchar(4000) not null,
	Url varchar(4000) not null,
	Alive bit(1) not null,
	Layer bigint not null,
	primary key(Sid)
) default charset=utf8;


set names gbk;

select * from ZhuoFu2016_Base
