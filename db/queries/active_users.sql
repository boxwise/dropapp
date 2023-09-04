use dropapp_production;

SELECT u.id, u.naam, u.email, u.lastlogin, u.lastaction, u.valid_firstday, u.valid_lastday, u.created, u.modified
FROM cms_users AS u
LEFT OUTER JOIN cms_usergroups AS g ON g.id = u.cms_usergroups_id 
LEFT OUTER JOIN cms_usergroups_camps AS uc ON uc.cms_usergroups_id = g.id
WHERE 
	(NOT g.deleted OR g.deleted IS NULL) AND (NOT u.deleted OR u.deleted IS NULL)
	AND NOT (u.valid_lastday < CURDATE() AND UNIX_TIMESTAMP(u.valid_lastday) != 0) 
	AND UNIX_TIMESTAMP(u.deleted) = 0
GROUP BY u.id
ORDER BY UNIX_TIMESTAMP(u.valid_firstday)
