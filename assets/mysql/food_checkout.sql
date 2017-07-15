CREATE TABLE food_distributions (
	id int(11) NOT NULL AUTO_INCREMENT,
	label varchar(255) NOT NULL,
	food_1 int(11) NOT NULL,
	food_2 int(11),
	food_3 int(11),
	food_4 int(11),
	food_5 int(11),
	food_6 int(11),
	deleted datetime,
	visible int(11) NOT NULL DEFAULT 1,
	created datetime,
	PRIMARY KEY (ID)
);

CREATE TABLE food_transactions (
        id int(11) NOT NULL AUTO_INCREMENT,
        people_id int(11) NOT NULL,
	dist_id int(11) NOT NULL,
        food_id int(11) NOT NULL,
        count float NOT NULL,
        created datetime NOT NULL,
        created_by int(11),
        PRIMARY KEY (ID)
);
