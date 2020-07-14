#
# Table structure for table 'tx_likeit_like'
#
CREATE TABLE tx_likeit_like (
	liked_uid int(80) DEFAULT '0' NOT NULL,
	liked_table varchar(80) DEFAULT '' NOT NULL,
	cookie_value varchar(255) DEFAULT '' NOT NULL,
);
