# Auth0

We are using Auth0 for the authentication of Boxtribute. As an introduction see the quickstart guides, e.g. for [react](https://auth0.com/docs/quickstart/spa/react)

## General

To mirror the development lifecycle of boxtribute there are four Auth0 tenants:
- [boxtribute-dev](https://boxtribute-dev.eu.auth0.com/)
This is the tenant for development. Every developer of Boxtribute has access to it. Just write Hans in slack if this is not the case.
- [boxtribute-staging](https://boxtribute-staging.eu.auth0.com/)
- [boxtribute-demo](https://boxtribute-demo.eu.auth0.com/)
This is the tenant for the demo instance for interested ngos.
- [boxtribute-production](https://boxtribute-production.eu.auth0.com/)

## Authentication
We are only using [email-password authentication](https://auth0.com/docs/connections/database) and do not have any social logins enabled at the moment. We are using the Auth0 user store to administrate the user accounts. 
Every tenant has only one [Database Connection](https://auth0.com/docs/connections/database) since the Auth0 authentication does not allow multiple Database Connections for the same Application.

The development and staging include demo user accounts which match the accounts in the database seed of the dropapp and boxtribute repo.
These demo users are imported and updated by hand at the moment.

## Applications
Every Auth0 tenant has two [applications](https://auth0.com/docs/applications) set-up:
- a single page application called `boxtribute-react` for the new mobile framework.
- a regular web application called `dropapp-php` for the old dropapp framework.

## [Custom Domains](https://auth0.com/docs/custom-domains)
The domains for the Auth0 tenants are linked to the following domains:
- dev-login.boxtribute.org --> boxtribute-dev.eu.auth0.com
- staging-login.boxtribute.org --> boxtribute-staging.eu.auth0.com
- demo-login.boxtribute.org --> boxtribute-demo.eu.auth0.com
- login.boxtribute.org --> boxtribute-production.eu.auth0.com

## Auth0 user data
The following data of each user are currently saved in the [auth0 user db](https://auth0.com/docs/users/references/bulk-import-database-schema-examples):
- `user_id` (string)
`id`-column in `cms_users` table. Auth0 prepends the connection to the string, e.g. `auth0|1`
- `name` (string)
`naam`-column in `cms_users` table.
- `email` (string)
`email`-column in `cms_users` table. Must be in email format.
- `blocked` (boolean)
`deleted`-column in `cms_users` table.
- `app_metadata['last_blocked_date']` (date)
`deleted`-column in `cms_users` table.
- `app_metadata['organisation_id']` (int)
`organisation_id`-column in `cms_usergroups` table. OPTIONAL, not filled if user is a God Admin.
- `app_metadata['base_ids']` (list of int)
aggregation of `camp_id`-columns in `cms_usergroup_camps` table. OPTIONAL, not filled if user is a God Admin.
- `app_metadata['usergroup_id']` (int)
`cms_usergroups_id`-column in `cms_users` table. OPTIONAL, not filled if user is a God Admin.
- `app_metadata['valid_firstday']` (int)
`valid_firstday`-column in `cms_users` table. OPTIONAL, only filled if user has a time-limited access.
- `app_metadata['valid_lastday']` (int)
`valid_lastday`-column in `cms_users` table. OPTIONAL, only filled if user has a time-limited access.

A user is a God Admin if they have the "Boxtribute God" role assigned. For these users, the `is_admin`-column in `cms_users` table equals 1.

## Auth0 re-seeding
If you want to re-seed the users in Auth0, start the dropapp-php server and call in any Browser the following script.

              http://localhost:8100/reseed-auth0.php
