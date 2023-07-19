use dropapp_production;

-- short
SELECT 
	o.id as organisation_id, o.label as organisation, 
    ug.id as usergroup_id, ug.label as usergroup, 
    ug.deleted as usergroup_deleted,
    usercount.usercount,
    group_concat(ugc.camp_id) AS bases,
    ugr.auth0_role_name,
    ugr.auth0_role_id
FROM organisations o
LEFT JOIN cms_usergroups ug ON ug.organisation_id = o.id 
LEFT JOIN (
	SELECT ug2.id, COUNT(u.id) as usercount
    FROM cms_usergroups ug2 
    LEFT JOIN cms_users u  ON u.cms_usergroups_id=ug2.id
    GROUP BY ug2.id) usercount ON usercount.id = ug.id
LEFT JOIN cms_usergroups_camps ugc ON ugc.cms_usergroups_id = ug.id
LEFT JOIN cms_usergroups_roles ugr ON ugr.cms_usergroups_id = ug.id
GROUP BY o.id, o.label, ug.id, ug.label, ug.deleted, usercount.usercount, ugr.auth0_role_name, ugr.auth0_role_id;

-- extended
SELECT 
	o.id as organisation_id, o.label as organisation, 
    ug.id as usergroup_id, ug.label as usergroup, 
    ugl.level as usergroup_level, ugl.label as usergroup_level_description, 
    c.id as access_to_base_id, c.name as access_to_base_name,
    f.id as access_to_function_id, f.title_en as access_to_function_name, 
    f.include as  accesss_to_function_include_file,
    usercount.usercount
FROM organisations o
LEFT JOIN cms_usergroups ug ON ug.organisation_id = o.id AND ug.deleted IS NULL
LEFT JOIN (
	SELECT ug2.id, COUNT(u.id) as usercount
    FROM cms_usergroups ug2 
    LEFT JOIN cms_users u  ON u.cms_usergroups_id=ug2.id
    WHERE NOT u.deleted
    GROUP BY ug2.id) usercount ON usercount.id = ug.id
LEFT JOIN cms_usergroups_levels ugl ON ug.userlevel = ugl.id
LEFT JOIN cms_usergroups_camps ugc ON ugc.cms_usergroups_id = ug.id
LEFT JOIN camps c ON ugc.camp_id = c.id AND c.deleted IS NULL
LEFT JOIN cms_usergroups_functions ugf ON ugf.cms_usergroups_id = ug.id
LEFT JOIN cms_functions f ON ugf.cms_functions_id = f.id
WHERE 
	o.deleted IS NULL AND o.id=10 AND
    (f.id IN (SELECT cfc.cms_functions_id from cms_functions_camps cfc WHERE cfc.camps_id = c.id) OR f.allcamps);