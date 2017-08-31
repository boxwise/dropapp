CREATE TABLE need_periods (
	id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	label varchar(255) NOT NULL,
	week_min int(11) NOT NULL, 
	week_max int(11) NOT NULL, 
	PRIMARY KEY(id)
);

INSERT INTO need_periods (label, week_min, week_max)
	VALUES ("1 mth to 3 mths", 4, 13);
INSERT INTO need_periods (label, week_min, week_max)
	VALUES ("2 weeks to 6 weeks", 2, 6);
INSERT INTO need_periods (label, week_min, week_max)
	VALUES ("2 mths to 6 mths", 8, 26);
