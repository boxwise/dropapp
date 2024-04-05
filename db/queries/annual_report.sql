use dropapp_production;

-- total number of beneficiaries distributed to through free shops by year, month, organisation, camp
SELECT year, month, org, base, SUM(family_members) AS beneficiaries
FROM (
	SELECT YEAR(t.transaction_date) AS year, MONTH(t.transaction_date) AS month, ob.o_id, ob.org, ob.b_id, ob.base, t.people_id, IFNULL(MAX(children.number_of_children)+1,1) AS family_members
	FROM transactions t
	LEFT JOIN (
			SELECT p.parent_id, COUNT(DISTINCT p.id) AS number_of_children
			FROM people p
			WHERE NOT p.parent_id IS NULL
			GROUP BY p.parent_id
		) AS children ON t.people_id = children.parent_id
	LEFT JOIN (
			SELECT o.id AS o_id, o.label AS org, c.id AS b_id, c.name AS base, peo.id AS id
			FROM people peo
			LEFT JOIN camps c ON peo.camp_id=c.id
			LEFT JOIN organisations o ON o.id=c.organisation_id
		) AS ob ON ob.id = t.people_id
	WHERE t.count > 0
	GROUP BY YEAR(t.transaction_date), MONTH(t.transaction_date), ob.o_id, ob.org, ob.b_id, ob.base, t.people_id
) sub
WHERE year >= 2020
GROUP BY year, month, o_id, b_id;

-- newly registered beneficiaries by year, month, organisation and base
SELECT YEAR(peo.created), MONTH(peo.created), o.label, c.name, COUNT(peo.id)
FROM people peo
LEFT JOIN camps c ON peo.camp_id=c.id
LEFT JOIN organisations o ON o.id=c.organisation_id
WHERE YEAR(peo.created) >= 2020
GROUP BY YEAR(peo.created), MONTH(peo.created), o.id, o.label, c.id, c.name;

-- items distributed in shop by year, month, organisation and camp
SELECT YEAR(t.transaction_date) as year, MONTH(t.transaction_date) as month, o.label as org, c.name as base, cat.label as category, SUM(t.count) as items
FROM transactions t
LEFT JOIN products p ON p.id=t.product_id
-- when ASSORT products are introduced we cannot figure out the camp through products anymore.
-- It might make sens to extend transactions by a base_id column
LEFT JOIN camps c ON p.camp_id=c.id
LEFT JOIN organisations o ON o.id=c.organisation_id
LEFT JOIN product_categories cat ON p.category_id = cat.id
WHERE YEAR(t.transaction_date) >= 2020 AND t.count >0
GROUP BY YEAR(t.transaction_date), MONTH(t.transaction_date), o.id, o.label, c.id, c.name, cat.id;

-- boxes and items created by year, month, org, base, category
SELECT YEAR(s.created) as year, MONTH(s.created) as month, o.label as org, c.name as base, cat.label as category, COUNT(s.id) AS boxes, SUM(s.items) AS items
FROM stock s
LEFT JOIN products p ON p.id=s.product_id
LEFT JOIN product_categories cat ON p.category_id = cat.id
LEFT JOIN camps c ON p.camp_id=c.id
LEFT JOIN organisations o ON o.id=c.organisation_id
WHERE YEAR(s.created) >= 2020
GROUP BY YEAR(s.created), MONTH(s.created), o.id, o.label, c.id, c.name, cat.id;

-- boxes and items moved to donated by year, month, org, base, category
SELECT YEAR(s.modified), MONTH(s.modified), o.label, c.name, cat.label, COUNT(s.id) AS boxes, SUM(s.items) AS items
FROM stock s
LEFT JOIN products p ON p.id=s.product_id
LEFT JOIN product_categories cat ON p.category_id = cat.id
LEFT JOIN camps c ON p.camp_id=c.id
LEFT JOIN organisations o ON o.id=c.organisation_id
WHERE s.box_state_id = 5  AND YEAR(s.modified) >= 2020
GROUP BY YEAR(s.modified), MONTH(s.modified), o.id, o.label, c.id, c.name, cat.id;

-- moved out boxes to compare with the statviz query
SELECT 
	DATE(s.modified) as moved_on, 
    YEAR(s.modified) as moved_on_year,
    cat.id as category_id,
    p.name as product_name,
    p.gender_id as gender,
    s.size_id as size_id,
    null as tag_ids,
    l.label as target_id,
    "OutgoingLocation" AS target_type,
    c.id as base_id,
    c.name as base_name,
    o.label as org_name,
    COUNT(s.id) AS boxes, 
    SUM(s.items) AS items
FROM stock s
LEFT JOIN products p ON p.id=s.product_id
LEFT JOIN product_categories cat ON p.category_id = cat.id
LEFT JOIN locations l ON l.id = s.location_id
LEFT JOIN camps c ON p.camp_id=c.id
LEFT JOIN organisations o ON o.id=c.organisation_id
WHERE s.box_state_id = 5 AND YEAR(s.modified) < 2024 AND YEAR(s.modified) > 2019
GROUP BY DATE(s.modified), YEAR(s.modified), cat.id, p.id, s.size_id, l.id, o.id, c.id;
