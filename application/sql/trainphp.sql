-- 如果存在TrainPHP则删除
DROP DATABASE IF EXISTS trainphp;

-- 创建库
CREATE DATABASE trainphp CHARACTER SET utf8;

USE trainphp;

-- 删除表
-- drop table IF EXISTS adminuser;

-- adminUsers
CREATE TABLE IF NOT EXISTS adminuser(
	Auid BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,-- 自增1 主键
	Auser VARCHAR(50) NOT NULL,
	Passwd CHAR(32) NOT NULL,
	Alive BIT(1) NOT NULL DEFAULT TRUE,-- 默认为true
	Aulid SMALLINT NOT NULL
) DEFAULT CHARSET=utf8;

TRUNCATE TABLE  adminuser
-- 添加数据
INSERT INTO adminuser(Auser,Passwd,Aulid) VALUES('胡浴东','123456',1),('topu.net','123456',1),('周杰','123456',2)

-- 查询时 order by在group by之后 limit:a到b表示从a开始到b的记录,a从0开始,b若为-1则表示从a开始到最后一条数据，若只传a,则表示取a条记录
SELECT * FROM adminuser  GROUP BY aulid  ORDER BY Auid DESC LIMIT 0,2;
SELECT * FROM adminuser  ORDER BY Auid DESC LIMIT 0,2;

-- 删除article
DROP TABLE IF EXISTS  article;
-- Article
CREATE TABLE IF NOT EXISTS article(
	Aid BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Atitle VARCHAR(1000) NOT NULL,
	Url VARCHAR(1000) NOT NULL,
	Alive BIT(1) NOT NULL DEFAULT TRUE,
	Kind SMALLINT NOT NULL,
	Layer BIGINT NOT NULL,
	Ainfo TEXT NOT NULL,
	Atime DATETIME NOT NULL DEFAULT NOW() ,
	Pic1 VARCHAR(100) NOT NULL,
	Pic2 VARCHAR(100) NOT NULL,
	Summary VARCHAR(1000) NOT NULL,
	ieTitle VARCHAR(100) NOT NULL DEFAULT '',
	seoKeywords VARCHAR(1000) NOT NULL DEFAULT '',
	seoDescription VARCHAR(1000) NOT NULL DEFAULT ''
) DEFAULT CHARSET=utf8;

-- 添加数据
INSERT INTO article(Atitle,Url,Kind,Layer,Ainfo,Pic1,Pic2,Summary) VALUES('练习myssql建库建表','http://www.baidu.com',1,1,'网太慢，啥都干不了，还能说说个啥','','','概述为空')

SELECT* FROM article