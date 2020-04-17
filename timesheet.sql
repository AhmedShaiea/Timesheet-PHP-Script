CREATE database timesheet;

DROP TABLE IF EXISTS `timesheet`.`timesheet`;
CREATE TABLE `timesheet`.`timesheet` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `userid` BIGINT unsigned NOT NULL,
  `managerid` BIGINT unsigned,
  `name` varchar(45),
  `description` varchar(250),
  `submitted` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `approvedby` BIGINT unsigned,
  `approvedtime` DATETIME,
  `reviewnotes` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` DATETIME,
  `lastupdated` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `timesheet`.`timesheet_timerange`;
CREATE TABLE `timesheet`.`timesheet_timerange` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `userid` BIGINT unsigned NOT NULL,
  `timesheetid` BIGINT unsigned,
  `fordate` DATE,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `timesheet`.`timesheet_type_user`;
CREATE TABLE `timesheet`.`timesheet_type_user` (
  `userid` BIGINT unsigned NOT NULL,
  `timesheetid` BIGINT unsigned NOT NULL,
  `typeid` BIGINT unsigned NOT NULL,
  `starttime` DATETIME NOT NULL,
  `endtime` DATETIME NOT NULL,
  `date` DATE NOT NULL,
  `hours` DECIMAL(19,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `desc` varchar(250),
  PRIMARY KEY (`userid`,`timesheetid`,`starttime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `timesheet`.`type`;
CREATE TABLE `timesheet`.`type` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,  
  `typecategoryid` BIGINT unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` varchar(250), 
  `role` text,
  `employee` text,
  `exceptionemployee` text,
  `read` BIGINT unsigned,
  `create` BIGINT unsigned,
  `edit` BIGINT unsigned,
  `delete` BIGINT unsigned,
  `search` BIGINT unsigned,
  `status` tinyint(1) NOT NULL DEFAULT '1',  
  `billable` tinyint(1) NOT NULL DEFAULT '0',  
  `created` DATETIME,  
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `timesheet`.`typecategory`;
CREATE TABLE `timesheet`.`typecategory` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,  
  `name` varchar(250) NOT NULL, 
  `description` varchar(250),  
  `status` tinyint(1) NOT NULL DEFAULT '1',  
  `created` DATETIME,  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER  TABLE typecategory ADD UNIQUE (name);

DROP TABLE IF EXISTS `timesheet`.`users`;
CREATE TABLE `timesheet`.`users` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `phone` varchar(45),
  `address` varchar(45),
  `address2` varchar(45),
  `city` varchar(45),
  `province` varchar(45),
  `country` varchar(250),
  `zip` varchar(45),
  `reportto` BIGINT unsigned,
  `picture` varchar(45),
  `email` varchar(250) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `description` varchar(250),
  `division` BIGINT unsigned,
  `role` BIGINT unsigned,
  `hourlyrate` DECIMAL(19,2),
  `yearlyrate` DECIMAL(19,2),
  `username` varchar(16),
  `password` varchar(255) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME,
  `ended_at` DATETIME,
  `token` varchar(250),
  `token_valid_to` DATETIME,
  `remember_token` varchar(100),
  `remember_token_valid_to` DATETIME,
  PRIMARY KEY (`id`),
  UNIQUE (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `timesheet`.`users`(`id`,`first_name`,`last_name`,`phone`,`address`,`address2`,`city`,`province`,`country`,`zip`,`reportto`,`picture`,`email`,`status`,`description`,`division`,`role`,`hourlyrate`,`yearlyrate`,`username`,`password`,`created_at`,`updated_at`,`token`,`token_valid_to`)
VALUES(1,'CEO','CEO','',null,null,'YourCity','YourProvince','YourCountry',null,null,null,'timesheetceo2019@gmail.com',1,null,1,2,null,null,'ceo','$2y$10$IzZaUf71vOyXHvkt41U1lucpzIPVDB5PHJXQuD9A0.XZ.qm6C69Uq','2018-09-01 00:00:00','2018-09-01 00:00:00',null,null);

INSERT INTO `timesheet`.`users`(`id`,`first_name`,`last_name`,`phone`,`address`,`address2`,`city`,`province`,`country`,`zip`,`reportto`,`picture`,`email`,`status`,`description`,`division`,`role`,`hourlyrate`,`yearlyrate`,`username`,`password`,`created_at`,`updated_at`,`token`,`token_valid_to`)
VALUES(2,'Admin','Admin','',null,null,'YourCity','YourProvince','YourCountry',null,1,null,'timesheetadm2019@gmail.com',1,null,1,1,null,null,'admin','$2y$10$IzZaUf71vOyXHvkt41U1lucpzIPVDB5PHJXQuD9A0.XZ.qm6C69Uq','2018-09-01 00:00:00','2018-09-01 00:00:00',null,null);

INSERT INTO `timesheet`.`users`(`id`,`first_name`,`last_name`,`phone`,`address`,`address2`,`city`,`province`,`country`,`zip`,`reportto`,`picture`,`email`,`status`,`description`,`division`,`role`,`hourlyrate`,`yearlyrate`,`username`,`password`,`created_at`,`updated_at`,`token`,`token_valid_to`)
VALUES(3,'Manager','Sales','',null,null,'YourCity','YourProvince','YourCountry',null,1,null,'timesheetmanager2019@gmail.com',1,null,4,3,null,null,'manager','$2y$10$XR0QyQgItW1HXAcTzBa4ouP4A/Anafvm7ZYPdUPR4/xn7.NZYOZ7G','2018-09-01 00:00:00','2018-09-01 00:00:00',null,null);

INSERT INTO `timesheet`.`users`(`id`,`first_name`,`last_name`,`phone`,`address`,`address2`,`city`,`province`,`country`,`zip`,`reportto`,`picture`,`email`,`status`,`description`,`division`,`role`,`hourlyrate`,`yearlyrate`,`username`,`password`,`created_at`,`updated_at`,`token`,`token_valid_to`)
VALUES(4,'User','Sales','',null,null,'YourCity','YourProvince','YourCountry',null,3,null,'timesheetnoreply2019@gmail.com',1,null,4,4,null,null,'user','$2y$10$jsZsFA62iVqcl93GLgJ7Qus089Amwe0mCpxzpNc.11q06F6quu5Yi','2018-09-01 00:00:00','2018-09-01 00:00:00',null,null);

--    password and encoded password:

--    admin account:
--    timesheetadm2019@gmail.com
--    K1H8G9p2A7V3i5Z6@T7!
--    encoded password:
--    $2y$10$IzZaUf71vOyXHvkt41U1lucpzIPVDB5PHJXQuD9A0.XZ.qm6C69Uq
--    Birthday: 1980-1-1
--    Gender: Rather not say

--    manager account:
--    timesheetmanager2019@gmail.com
--    M9b6v8h1y2q7T3C5%Y1!
--    encoded password:
--    $2y$10$XR0QyQgItW1HXAcTzBa4ouP4A/Anafvm7ZYPdUPR4/xn7.NZYOZ7G
--    Birthday: 1980-1-1
--    Gender: Rather not say

--    user account:
--    timesheetnoreply2019@gmail.com
--    N6y9J1G3u2Q5#W7z80
--    encoded password:
--    $2y$10$jsZsFA62iVqcl93GLgJ7Qus089Amwe0mCpxzpNc.11q06F6quu5Yi
--    Birthday: 1980-1-1
--    Gender: Rather not say

--    ------------------------------------------------------------


--  This view is very important!
Create View `activeuser` as 
Select * From timesheet.users where status = 1;
 
-- password encrypt: 
-- 19760108
-- $2y$10$fzWWXFPpK8NzXbBQOB9qruxT.EMpMu8YvLeO00el/UErxM8eoOhtq

  DROP TABLE IF EXISTS `password_resets`;
  CREATE TABLE `password_resets` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `userid` BIGINT unsigned,
  `email` varchar(250),
  `token` varchar(500),
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `timesheet`.`division`;
CREATE TABLE `timesheet`.`division` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `timesheet`.`division`(`id`,`name`,`description`,`status`,`created`)VALUES(1,'admin','admin',1,NOW());


DROP TABLE IF EXISTS `timesheet`.`role`;
CREATE TABLE `timesheet`.`role` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `timesheet`.`role`(`id`,`name`,`description`,`status`,`created`)VALUES(1,'admin','admin',1,NOW());
INSERT INTO `timesheet`.`role`(`id`,`name`,`description`,`status`,`created`)VALUES(2,'superuser','superuser',1,NOW());
INSERT INTO `timesheet`.`role`(`id`,`name`,`description`,`status`,`created`)VALUES(3,'manager','manager',1,NOW());
INSERT INTO `timesheet`.`role`(`id`,`name`,`description`,`status`,`created`)VALUES(4,'user','user',1,NOW());



DROP TABLE IF EXISTS `timesheet`.`access`;
CREATE TABLE `timesheet`.`access` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `target` varchar(255) NOT NULL,
  `title` varchar(255),
  `role` text,
  `employee` text,
  `exceptionemployee` text,
  `read` BIGINT unsigned,
  `create` BIGINT unsigned,
  `edit` BIGINT unsigned,
  `delete` BIGINT unsigned,
  `search` BIGINT unsigned,
  `description` varchar(255),
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `billable` tinyint(1) NOT NULL DEFAULT '0',
  `created` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  These SQL must be executed with "create table" SQL:
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('timesheet','timesheet',null,null,null,9223372036854775807,9223372036854775807,9223372036854775807,9223372036854775807,9223372036854775807,'timesheet',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('reviewtimesheet','reviewtimesheet','3',null,null,null,null,null,null,null,'reviewtimesheet',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('dashboard','dashboard','3',null,null,null,null,null,null,null,'dashboard',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('division','division',null,null,null,null,null,null,null,null,'division',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('reminder','reminder',null,null,null,null,null,null,null,null,'reminder',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('report','report','3',null,null,null,null,null,null,null,'report',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('role','role',null,null,null,null,null,null,null,null,'role',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('typecategory','typecategory',null,null,null,null,null,null,null,null,'typecategory',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('type','type',null,null,null,null,null,null,null,null,'type',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('user','user',null,null,null,null,null,null,null,null,'user',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('constant','constant',null,null,null,null,null,null,null,null,'constant',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('access','access',null,null,null,null,null,null,null,null,'access',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('autoemail','autoemail',null,null,null,null,null,null,null,null,'autoemail',1,1,NOW());
INSERT INTO `timesheet`.`access`(`target`,`title`,`role`,`employee`,`exceptionemployee`,`read`,`create`,`edit`,`delete`,`search`,`description`,`status`,`billable`,`created`)VALUES
('webhook','webhook',null,null,null,null,null,null,null,null,'webhook',1,1,NOW());


DROP TABLE IF EXISTS `timesheet`.`constant`;
CREATE TABLE `timesheet`.`constant` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  These SQL must be executed with "create table" SQL:
INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('WEBSITE_HTTP_HTTPS','http',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('ROW_PER_TABLE_INT','50',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('USER_HEAD_IMAGE_TYPES_ARRAY','{"jpg":"jpg","jpeg":"jpeg","png":"png","bmp":"bmp"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('USER_HEAD_IMAGE_SIZE_LIMIT_INT','1000000',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('HEADER_TYPECATEGORY_ARRAY','{"":"", "ID":"id", "Name":"name", "Desc":"description", "Status":"status", "Created":"created"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('HEADER_DIVISION_ARRAY','{"":"", "ID":"id", "Name":"name", "Desc":"description", "Status":"status", "Created":"created"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('HEADER_REVIEWTIMESHEET_ARRAY','{"":"", "ID":"t.id", "UserName":"username", "Name":"t.name", "Desc":"t.description", "Approved":"t.approved", "Approvedby":"approvedbyname",  "ApprovedTime":"t.approvedtime", "ReviewNotes":"t.reviewnotes", "Status":"t.status", "Created":"t.created"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('HEADER_ROLE_ARRAY','{"":"", "ID":"id", "Name":"name", "Desc":"description", "Status":"status", "Created":"created"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('HEADER_TIMESHEET_ARRAY','{"":"", "ID":"t.id", "Name":"t.name", "Desc":"t.description", "Date":"daterange", "TotalHours":"totalhours", "Approved":"t.approved", "Approvedby":"approvedbyname", "ApprovedTime":"t.approvedtime", "ReviewNotes":"t.reviewnotes", "Status":"status"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('HEADER_TYPE_ARRAY','{"":"", "ID":"t.id", "TypeCategory":"typecategoryname", "Name":"t.name", "Desc":"t.description", "Status":"t.status", "Created":"t.created", "Billable":"t.billable"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('HEADER_USER_ARRAY','{"":"", "ID":"t.id", "Name":"name", "Phone":"t.phone", "Email":"t.email", "Reportto":"reporttoperson", "Division":"divisionname", "Role":"rolename", "Username":"t.username", "Status":"t.status", "Created":"t.created_at", "Desc":"description"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('HEADER_CONSTANT_ARRAY','{"":"", "ID":"id", "Name":"name", "Desc":"description", "Status":"status", "Created":"created"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('ACCESS_PAGES_ARRAY','{"Dashboard":"Dashboard", "Division":"Division","Log":"Log","Reminder":"Reminder","Report":"Report","Reviewtimesheet":"Reviewtimesheet","Role":"Role","Timesheet":"Timesheet","Typecategory":"Typecategory","Type":"Type","User":"User"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('CONTROLLERS_ARRAY','{"Dashboard":"dashboard","Division":"division","Reminder":"reminder","Report":"report","Reviewtimesheet":"reviewtimesheet","Role":"role","Timesheet":"timesheet","Typecategory":"typecategory","Type":"type","User":"user","Constant":"constant","Access":"access","Autoemail":"autoemail","Webhook":"webhook"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('REPORT_DATAROW_ONEPAGE_INT','33',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('REVIEW_TIMESHEET_DAYS_RANGE_INT','30',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('ITEMS_PER_PAGE_ARRAY','{"10":10,"20":20,"50":50,"100":100}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('CONTROLLERS_EXTRA_METHODS_ARRAY','{"dashboard":"getHoursByEachUser,getHoursByEachType","division":"","log":"","reminder":"","report":"getdata","reviewtimesheet":"getdetail","role":"","timesheet":"","typecategory":"","type":"createtype,edittype","user":"headImagesExist,impersonate","constant":"","access":"updateAccess,createAccess"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('ADMIN_ROLE_NAME','admin',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('SUPERUSER_ROLE_NAME','superuser',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('MANAGER_ROLE_NAME','manager',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('AUTOEMAIL_NAME','AUTOEMAIL',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('AUTOEMAIL','{"create":"||", "review":"||"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('WEBHOOK_NAME','WEBHOOK',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('WEBHOOK_AMOUNT','5',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('WEBHOOK','{"create":"||||","edit":"||||","review":"||||"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('AUTOEMAIL_TOKEN','7pk6g3d8u1c2v3n5@x&1f8^9z',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('WEBHOOK_TOKEN','b1h8u3d%k7m2&5c9t6y8z',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('TIMESHEET_WEEK_AMOUNT','6',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('AUTOEMAIL_SENDER_EMAIL_ADDRESS','no-reply@example.com',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('ADMIN_ROLE_ID','1',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('ADMIN_DIVISION_ID','1',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('SUPERUSER_ROLE_ID','2',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('TIMEZONE','America/New_York',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('TIMESHEET_HOURS_COLORS_ARRAY','{"0":"primary","1":"default","2":"success","3":"info","4":"warning","5":"danger","6":"success"}',1,NOW());

INSERT INTO `timesheet`.`constant`(`name`,`description`,`status`,`created`)VALUES('LANGUAGE','en',1,NOW());

DROP TABLE IF EXISTS `timesheet`.`log`;
CREATE TABLE `timesheet`.`log` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `userid` BIGINT unsigned NOT NULL,
  `message` text NOT NULL,
  `messagetype` text,
  `created` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `timesheet`.`reminder`;
CREATE TABLE `timesheet`.`reminder` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `startremindtime` DATETIME NOT NULL,
  `endremindtime` DATETIME NOT NULL,
  `users` text NOT NULL,
  `roles` text NOT NULL,
  `groups` text NOT NULL,
  `userexception` text NOT NULL,
  `roleexception` text NOT NULL,
  `groupexception` text NOT NULL,
  `showinpages` tinyint(1),
  `emailusers` tinyint(1),
  `emaildaysbefore` INT not null,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

