use dropapp_production;

-- number of beneficiaries receiving items through the free shop
SELECT COUNT(peo.id)
FROM people peo
WHERE 
    peo.parent_id IN (
        SELECT distinct p.id
        FROM people as p
        LEFT JOIN transactions t ON p.id = t.people_id
        WHERE t.transaction_date >= "2020-11-20" AND t.count >0) or
    peo.id IN (
        SELECT distinct p.id
        FROM people as p
        LEFT JOIN transactions t ON p.id = t.people_id
        WHERE t.transaction_date >= "2020-11-20" AND t.count >0);

-- number of beneficiaries created
SELECT COUNT(peo.id)
FROM people peo
WHERE peo.created >= "2020-10-20";

-- number of items given out through free shop
SELECT SUM(t.count)
from transactions t
WHERE t.transaction_date >= "2020-10-20";

-- boxes and items moved to donated
SELECT SUM(s.items), COUNT(s.id)
FROM stock s
WHERE s.box_state_id = 5  AND s.modified >= "2020-10-20";
