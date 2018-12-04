#
# Table structure for table 'tx_likeit_like'
#
CREATE TABLE tx_likeit_like (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	liked_uid int(80) DEFAULT '0' NOT NULL,
	liked_table varchar(80) DEFAULT '' NOT NULL,
	cookie_value varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent(pid)
);
