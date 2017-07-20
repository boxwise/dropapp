CREATE FUNCTION IsNumeric (val varchar(255)) RETURNS tinyint 
	RETURN val REGEXP '^(-|\\+){0,1}([0-9]+\\.[0-9]*|[0-9]*\\.[0-9]+|[0-9]+)$';

CREATE FUNCTION NumericOnly (val VARCHAR(255)) 
	RETURNS VARCHAR(255)
BEGIN
	DECLARE idx INT DEFAULT 0;
	IF ISNULL(val) THEN RETURN NULL; END IF;
	IF LENGTH(val) = 0 THEN RETURN ""; END IF;
	SET idx = LENGTH(val);
		WHILE idx > 0 DO
			IF IsNumeric(SUBSTRING(val,idx,1)) = 0 THEN
				SET val = REPLACE(val,SUBSTRING(val,idx,1),"");
				SET idx = LENGTH(val)+1;
			END IF;
			SET idx = idx - 1;
		END WHILE;
	RETURN val;
END;

CREATE PROCEDURE split_lines()
BEGIN
	DECLARE done INT DEFAULT FALSE;
	DECLARE id_tmp INT UNSIGNED;
	DECLARE ch_tmp TEXT;
	DECLARE rec_tmp, usr_tmp, semi_col INT;
	DECLARE ip_tmp VARCHAR(20);
	DECLARE date_tmp datetime;
	DECLARE cur CURSOR FOR SELECT id, changes, record_id, user_id, ip, changedate FROM history WHERE tablename='stock';
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

	OPEN cur;

	read_loop: LOOP
		FETCH cur into id_tmp, ch_tmp, rec_tmp, usr_tmp, ip_tmp, date_tmp;
		IF done THEN
			LEAVE read_loop;
		END IF;
		IF ROUND((CHAR_LENGTH(ch_tmp)-CHAR_LENGTH(REPLACE(ch_tmp, "from", "")))/CHAR_LENGTH("from")) > 1 THEN
			SET semi_col = LOCATE(";", ch_tmp);
			UPDATE history SET changes = left(ch_tmp, semi_col) WHERE id = id_tmp;
			INSERT INTO history (changes, tablename, record_id, user_id, ip, changedate)
			VALUES (right(ch_tmp, CHAR_LENGTH(ch_tmp) - semi_col -1), "stock", rec_tmp, usr_tmp, ip_tmp, date_tmp);
		END IF;
	END LOOP;
	CLOSE cur;
END;

CALL split_lines();
CALL split_lines();
CALL split_lines();
CALL split_lines();
CALL split_lines();
CALL split_lines();
CALL split_lines();

CREATE PROCEDURE write_from_to(OUT test TEXT)
BEGIN
	DECLARE done INT DEFAULT FALSE;
	DECLARE id_tmp INT UNSIGNED;
	DECLARE ch_tmp TEXT;
	DECLARE rec_tmp INT;
	DECLARE a,b,c,d INT;
	DECLARE text_tmp TEXT DEFAULT "";
	DECLARE cur CURSOR FOR SELECT id, record_id, changes FROM history WHERE tablename='stock';
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

	OPEN cur;

	read_loop: LOOP
		FETCH cur into id_tmp, rec_tmp, ch_tmp;
		IF done THEN
			LEAVE read_loop;
		END IF;

		IF LOCATE(";", ch_tmp) > 0 AND LEFT(ch_tmp, 8) != "comments" THEN
			SET a = LOCATE("\"", ch_tmp);
			SET b = LOCATE("\"", ch_tmp, a+1);
			SET c = LOCATE("\"", ch_tmp, b+1);
			SET d = LOCATE("\"", ch_tmp, c+1);
			UPDATE history SET changes = LEFT(ch_tmp, LOCATE(" ", ch_tmp)-1) WHERE id = id_tmp;
		ELSEIF LOCATE("Changed number", ch_tmp) > 0 THEN
			SET ch_tmp = SUBSTRING_INDEX(ch_tmp, " ", -3);
			SET a = 0;
			SET b = LOCATE(" ", ch_tmp);
			SET c = LOCATE(" ", ch_tmp, b+1);
			SET d = CHAR_LENGTH(ch_tmp) + 1;
			UPDATE history SET changes = "items" WHERE id = id_tmp;
		ELSEIF LOCATE("Box moved", ch_tmp) > 0 THEN
			SET text_tmp = SUBSTRING(ch_tmp, LOCATE("to", ch_tmp) + 3, CHAR_LENGTH(ch_tmp));
			IF text_tmp = "Vicente's" THEN 
				SET text_tmp = "Vicente's WH";
			ELSEIF text_tmp = "Main Warehouse" THEN 
				SET text_tmp = "The Cat's WH";
			END IF;
			UPDATE history SET changes = "location_id" WHERE id = id_tmp;
			UPDATE history SET to_int = (SELECT loc.id FROM locations AS loc 
			WHERE loc.camp_id = (SELECT loc2.camp_id FROM locations AS loc2, stock AS sto WHERE sto.id = rec_tmp AND sto.location_id = loc2.id)
			AND loc.label = text_tmp) WHERE id=id_tmp; 
			SET a=0, b=0, c=0, d=0;
		ELSE 
			SET a=0, b=0, c=0, d=0;
		END IF;
		IF b-a > 1 THEN
			IF ŃumericOnly(SUBSTRING(ch_tmp, a+1, b-a-1)) = "" THEN 
				SET test = id_tmp; 
				LEAVE read_loop;
			END IF;
			UPDATE history SET from_int = ŃumericOnly(SUBSTRING(ch_tmp, a+1, b-a-1)) WHERE id = id_tmp;
		END IF;
		IF d-c > 1 THEN
			UPDATE history SET to_int = NumericOnly(SUBSTRING(ch_tmp, c+1, d-c-1)) WHERE id = id_tmp;
		END IF;
	END LOOP;
	CLOSE cur;
END;

CALL write_from_to(@test);
