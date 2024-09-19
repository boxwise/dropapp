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

-- boxes moved out of stock; grouped by product category, product name, product gender, size, tags, base, organisation
-- Variant of https://github.com/boxwise/boxtribute/blob/master/back/boxtribute_server/business_logic/statistics/sql.py

-- NOTE: three of the CTEs are simply subqueries to create the
-- BoxStateChangeVersions and CreateDonateBoxes temporary tables.
-- Check the explain plan to be sure since it's possible that properly
-- materializing the final "temporary" tables for re-use, and then dropping the
-- previous CTEs from memory would scale much better.

-- Common Table Expression (CTE) to identify valid boxes
WITH recursive ValidBoxes AS (
    SELECT
        s.id,
        s.items,
        s.location_id,
        s.size_id,
        s.box_state_id,
        s.product_id
    FROM stock s
),
BoxHistory AS (
    -- CTE to retrieve box history (only include changes in FK fields such as
    -- product, size, location, box state, as well as number of items).
    -- Select only changes from 2023-01-01 and newer
    SELECT
        s.id AS box_id,
        s.items AS stock_items,
        s.location_id AS stock_location_id,
        s.product_id AS stock_product_id,
        s.size_id AS stock_size_id,
        s.box_state_id AS stock_box_state_id,
        h.record_id,
        h.changes,
        h.changedate,
        h.from_int,
        h.to_int,
        h.id AS id
    FROM history h
    JOIN ValidBoxes s ON h.record_id = s.id
    AND ((h.to_int IS NOT null AND h.id >= 1324559) OR h.changes = "Record created")
    AND h.tablename = 'stock'
    ORDER BY record_id, changedate DESC, id DESC
),
HistoryReconstruction AS (
    -- CTE to reconstruct box versions
    -- For each change in product, size, location, box state, or number of items,
    -- reconstruct the box version at that time
    SELECT
        h.box_id,
        h.from_int,
        h.to_int,
        h.changes,
        h.changedate,
        IF(h.changes <> 'items',
                -- The current change is NOT about number of items, hence the correct number of items
                -- of the box at this time must be inferred
                COALESCE(
                    -- Look for the next change in number of items related to the box and use 'from_int' value
                    (SELECT his.from_int
                    FROM history his
                    WHERE his.record_id = h.record_id AND his.changes = 'items' AND his.id > h.id
                    ORDER BY his.id ASC
                    LIMIT 1),
                    -- Look for the previous change in number of items related to the box and use 'to_int' value
                    COALESCE(
                        (SELECT his.to_int
                        FROM history his
                        WHERE his.record_id = h.record_id AND his.changes = 'items' AND his.id < h.id
                        ORDER BY his.id DESC
                        LIMIT 1),
                        -- No change in number of items ever happened to the box
                        h.stock_items
                    )
                ),
                -- The current change is about number of items
                h.to_int
        ) AS items,
        IF(h.changes <> 'location_id',
                COALESCE(
                    (SELECT his.from_int
                    FROM history his
                    WHERE his.record_id = h.record_id AND his.changes = 'location_id' AND his.id > h.id
                    ORDER BY his.id ASC
                    LIMIT 1),
                    COALESCE(
                        (SELECT his.to_int
                        FROM history his
                        WHERE his.record_id = h.record_id AND his.changes = 'location_id' AND his.id < h.id
                        ORDER BY his.id DESC
                        LIMIT 1),
                        h.stock_location_id
                    )
                ),
                h.to_int
        ) AS location_id,
        IF(h.changes <> 'box_state_id',
                COALESCE(
                    (SELECT his.from_int
                    FROM history his
                    WHERE his.record_id = h.record_id AND his.changes = 'box_state_id' AND his.id > h.id
                    ORDER BY his.id ASC
                    LIMIT 1),
                    COALESCE(
                        (SELECT his.to_int
                        FROM history his
                        WHERE his.record_id = h.record_id AND his.changes = 'box_state_id' AND his.id < h.id
                        ORDER BY his.id DESC
                        LIMIT 1),
                        h.stock_box_state_id
                    )
                ),
                h.to_int
        ) AS box_state_id,
        IF(h.changes <> 'product_id',
                COALESCE(
                    (SELECT his.from_int
                    FROM history his
                    WHERE his.record_id = h.record_id AND his.changes = 'product_id' AND his.id > h.id
                    ORDER BY his.id ASC
                    LIMIT 1),
                    COALESCE(
                        (SELECT his.to_int
                        FROM history his
                        WHERE his.record_id = h.record_id AND his.changes = 'product_id' AND his.id < h.id
                        ORDER BY his.id DESC
                        LIMIT 1),
                        h.stock_product_id
                    )
                ),
                h.to_int
        ) AS product_id,
        IF(h.changes <> 'size_id',
                COALESCE(
                    (SELECT his.from_int
                    FROM history his
                    WHERE his.record_id = h.record_id AND his.changes = 'size_id' AND his.id > h.id
                    ORDER BY his.id ASC
                    LIMIT 1),
                    COALESCE(
                        (SELECT his.to_int
                        FROM history his
                        WHERE his.record_id = h.record_id AND his.changes = 'size_id' AND his.id < h.id
                        ORDER BY his.id DESC
                        LIMIT 1),
                        h.stock_size_id
                    )
                ),
                h.to_int
        ) AS size_id
    FROM BoxHistory h
    ORDER BY id DESC
),
BoxStateChangeVersions AS (
    -- CTE for selecting all box versions at the time of box state changes
    SELECT
        h.box_id,
        h.box_state_id,
        date(h.changedate) as moved_on,
        if(h.from_int <> h.box_state_id, h.from_int, h.box_state_id) AS prev_box_state_id,
        h.items AS number_of_items,
        h.location_id,
        h.product_id AS product,
        h.size_id
    FROM HistoryReconstruction h
    WHERE h.changes = 'box_state_id'
),
CreatedDonatedBoxes AS (
    SELECT
        h.box_id,
        h.box_state_id,
        date(h.changedate) as moved_on,
        h.items AS number_of_items,
        h.location_id,
        h.product_id AS product,
        h.size_id
    FROM HistoryReconstruction h
    WHERE h.changes = 'Record created' and h.box_state_id = 5
)

-- MAIN QUERY

-- Collect information about boxes created in donated state
select
    t.moved_on,
    p.category_id,
    TRIM(LOWER(p.name)) AS product_name,
    p.gender_id AS gender,
    t.size_id,
    GROUP_CONCAT(DISTINCT tr.tag_id) AS tag_ids,
    loc.label AS target_id,
    "OutgoingLocation" AS target_type,
    c.id as base_id,
    c.name as base_name,
    o.label as org_name,
    count(t.box_id) AS boxes_count,
    sum(t.number_of_items) AS items_count
FROM CreatedDonatedBoxes t
JOIN products p ON p.id = t.product
JOIN locations loc ON loc.id = t.location_id
JOIN camps c ON c.id = loc.camp_id
JOIN organisations o ON o.id = c.organisation_id
LEFT OUTER JOIN tags_relations tr ON tr.object_id = t.box_id AND tr.object_type = "Stock" AND tr.deleted_on IS NULL
GROUP BY moved_on, p.category_id, p.name, p.gender_id, t.size_id, loc.label, c.id, c.name, o.label

UNION ALL

-- Collect information about boxes being moved between states InStock and Donated
select
    t.moved_on,
    p.category_id,
    TRIM(LOWER(p.name)) AS product_name,
    p.gender_id AS gender,
    t.size_id,
    GROUP_CONCAT(DISTINCT tr.tag_id) AS tag_ids,
    loc.label AS target_id,
    "OutgoingLocation" AS target_type,
    c.id as base_id,
    c.name as base_name,
    o.label as org_name,
    sum(
        CASE
            WHEN t.prev_box_state_id = 1 AND t.box_state_id = 5 THEN 1
            WHEN t.prev_box_state_id = 5 AND t.box_state_id = 1 THEN -1
            ELSE 0
        END
    ) AS boxes_count,
    sum(
        CASE
            WHEN t.prev_box_state_id = 1 AND t.box_state_id = 5 THEN 1 * t.number_of_items
            WHEN t.prev_box_state_id = 5 AND t.box_state_id = 1 THEN -1 * t.number_of_items
            ELSE 0
        END
    ) AS items_count
FROM BoxStateChangeVersions t
JOIN products p ON p.id = t.product
JOIN locations loc ON loc.id = t.location_id
JOIN camps c ON c.id = loc.camp_id
JOIN organisations o ON o.id = c.organisation_id
LEFT OUTER JOIN tags_relations tr ON tr.object_id = t.box_id AND tr.object_type = "Stock" AND tr.deleted_on IS NULL
WHERE (t.prev_box_state_id = 1 AND t.box_state_id = 5) OR
      (t.prev_box_state_id = 5 AND t.box_state_id = 1)
GROUP BY moved_on, p.category_id, p.name, p.gender_id, t.size_id, loc.label, c.id, c.name, o.label

UNION ALL

-- Collect information about all boxes sent from the specified base as source, that
-- were not removed from the shipment during preparation
SELECT
    DATE(sh.sent_on) AS moved_on,
    p.category_id,
    TRIM(LOWER(p.name)) AS product_name,
    p.gender_id AS gender,
    d.source_size_id AS size_id,
    GROUP_CONCAT(DISTINCT tr.tag_id) AS tag_ids,
    c.name AS target_id,
    "Shipment" AS target_type,
    c.id AS base_id,
    c.name as base_name,
    o.label as org_name,
    COUNT(d.box_id) AS boxes_count,
    SUM(d.source_quantity) AS items_count
FROM
    shipment_detail d
JOIN
    shipment sh
ON
    d.shipment_id = sh.id AND
    d.removed_on IS NULL AND
    sh.sent_on IS NOT NULL
JOIN camps c ON c.id = sh.target_base_id
JOIN organisations o ON o.id = c.organisation_id
JOIN products p ON p.id = d.source_product_id
LEFT OUTER JOIN tags_relations tr ON tr.object_id = d.box_id AND tr.object_type = "Stock" AND tr.deleted_on IS NULL
GROUP BY moved_on, p.category_id, p.name, p.gender_id, d.source_size_id, c.name, c.id, c.name, o.label

UNION ALL

-- Collect information about boxes that were turned into Lost/Scrap state; it is
-- assumed that these boxes have not been further moved but still are part of the
-- specified base
SELECT
    DATE(h.changedate) AS moved_on,
    p.category_id,
    TRIM(LOWER(p.name)) AS product_name,
    p.gender_id AS gender,
    b.size_id,
    GROUP_CONCAT(DISTINCT tr.tag_id) AS tag_ids,
    bs.label AS target_id,
    "BoxState" AS target_type,
    c.id as base_id,
    c.name as base_name,
    o.label as org_name,
    COUNT(h.id) AS boxes_count,
    SUM(b.items) AS items_count
FROM
    history h
JOIN
    stock b
ON
    h.tablename = "stock" AND
    h.changes = "box_state_id" AND
    h.record_id = b.id AND
    h.from_int = 1 AND
    h.to_int IN (2, 6) -- (Lost, Scrap)
JOIN products p ON p.id = b.product_id
JOIN box_state bs on bs.id = h.to_int
JOIN locations loc ON loc.id = b.location_id
JOIN camps c ON c.id = loc.camp_id
JOIN organisations o ON o.id = c.organisation_id
LEFT OUTER JOIN tags_relations tr ON tr.object_id = b.id AND tr.object_type = "Stock" AND tr.deleted_on IS NULL
GROUP BY moved_on, p.category_id, p.name, p.gender_id, b.size_id, bs.label, c.id, c.name, o.label
