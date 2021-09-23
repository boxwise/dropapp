
# Authorization with Auth0

Trello-card: https://trello.com/c/kypUAjlw/747-vahid-will-create-adr-for-authentication-authorization-implementation-design

## Status

proposed

## Context or Problem Statement

DropApp currently uses embedded SQL queries to handle authorization. Right now, the system has a very granular access model for managing access to functionalities. 

## Decision Drivers 

1. Resource Types
2. Underling’s Protocols
3. Flexibility of Rules
4. Ease of Implementation
5. Security / Risk for us
6.  Ease of Use
7.  Cost

## Considered Options

- **Auth0 Core Authorization** (roles and permissions - rules)
- **Auth0 Extended Authorization** (roles, permissions and groups - rules)
- **Auth0 Organizations Authorization** (roles, permissions, organizations - rule)

## Discussion

The current system access control looks like this:
 - [x] Each *User* has belong to a *Role**
 - [x] Each *Role* belong to one  *Access Level*
 - [x] Each *Role* contains 1 to Many *Resource / Permission**** 
 - [x] Each *Role* has access to 1 to Many *Base*
 - [x] Each *Organization* has a *Base***
 - [x] Each *Base* can access by 1 to Many *Role*
 - [x] Each *Base* contains predefined *Resource / Permission* 

Notes:
- (*) In DropApp Role is called 'User Group'
- (**) In DropApp  Base is called 'Camp' 
- (***) In DropApp data model Resource / Permission is called 'Functions'

Using the above mentioned model, system admins can grant or revoke certain functions based on either **Role** or **Base**. Boxtribute is implementing a new stack and currently Authentication is backed by Auth0, so it is necessary to move Authorization into a reliable service.

*Auth0 core* supports simple *Role Based Access Control (RBAC)*, and for each tenant, Roles and Permissions can be assigned to Users. To migrate the current complex authorization system, we must simplify or flatten the Roles and Permissions in a way that maintains the previous access model.

In order to capture the requirements for this change, the following steps were taken:

 1. Permissions captured from **Database perspectives**. 
 2. Analyzing all requests in the DropApp application to find the exact requests and mapping them to permissions.

The summary of the permissions taken from the system is:

| Module |  Permissions| Required  Data Models |
|--|--| --|
| Beneficiary | *beneficiary:read* - *beneficiary:write* - *transactions:read* - *transaction:write* - *transaction:give* - *tags:read* | `people, transactions, people_tags`
| Inventory  | *qr:create* - *qr:read* - *stock:read* - *stock:write* - *products:read* - *locations:read* - *tags:read* - *product_categoris:read*| `locations, stock, qr, genders, sizes, products, product_categories`
| Free Shop  | *transcation:read* - *transcation:write* - *stock:read* - *products:read*, *beneficiary:read* | `transations, people, stock, products, product_categories`
| Insight  | *transactions:read* - *products:read*| `transations, products, product_categories`
| Admin  | *products:read* - *products:update* - *users:read* - *users:write* - *locations:read* - *locations:write*| `transations, people, stock, products, product_categories`
| Boxtribute God  | *organization:read* - *organization:write* - *bases:read* - *bases:write* | `organizations, camps`

Suggested Roles based End-User
-

|Role|Permissions|
|:----|:----|
|**Warehouse Info**|history:read|
| |base:read|
| |locations:read|
| |products:read|
| |product_categories:read|
| |tags:read|
| |stock:read|
|**Warehouse Volunteer**|Requirement: Warehouse Info|
| |qr:create|
| |stock:write|
| |history:write|
|**Warehouse Manager / Coordinator**|Requirement: Warehouse Volunteer|
| |products:write|
| |locations:write|
| |tags:write|
|**Beneficiary Info**|history:read|
| |base:read|
| |tags:read|
| |beneficiaries:read|
|**Beneficiary Volunteer**|Requirement:  Beneficiary Info|
| |history:write|
| |beneficiaries:create|
|**Beneficiary Manager / Coordinator**|Requirement: Beneficiary Volunteer|
| |beneficiaries:write|
|**Free Shop Info**|Requirement: Beneficiary Info|
| |transactions:read|
| |transactions:read|
| |products:read|
| |product_categories:read|
|**Free Shop Volunteer**|Requirement: Free Shop Info|
| |history:write|
| |transactions:purchase|
|**Free Shop Manager / Coordinator**|Requirement: Free Shop Volunteer|
| |products:write|
| |tags:write|
| |transactions:give|
|**Volunteer Manager / Coordinator**|history:read|
| |base:read|
| |history:write|
| |users:write|
|**Admin**|history:read|
| |base:read|
| |history:write|
| |bases:edit|

So far we captured the current system modules, roles, and permissions. 

## Example of Flattened Authorization based on Auth0:

In order to give the system the flexibility to create various kind of roles which can be used for various organizations and bases, we can create dynamic roles. This mean all the bases that already created in the system will be added as role to Auth0. For that, we can create role as:

    'base_' + [base identifier]

This way we are able to grant or revoke specific permissions to any bases in our system. 

E.g. *Organization 10001* which has two bases in Europe named *Base X1* and *Base X2*. User with Admin and Coordinator roles can access *Base X1* , while only Coordinator should able to access Base X2. 

**Roles:**

- base_**x1**  *(this role used to customized the permissions for Base X1)*
- base_**x2**
- coordinator
- admin

**Permissions:**

In addition, we create a new permission to be used by with the following pattern:

    'role_' + [system level roles: admin / coordinator / volunteer ]
    
This above mentioned permission has created to restrict some specific user level to a specific base. So considers the following roles and permission in our example:

- base_**x1**
- base_**x2**
- role_coordinator:*
- role_**admin**:*
- role_**volunteer**:*
 - users:read
 - users:write
 - beneficiaries:read 
	 *- The role contains this permission can have read access  to beneficiaries*
 - beneficiaries:write 
	 *- The role contains this permission can have write access to beneficiaries*

**Roles and Permissions Assignment:**

In the following example **base_x1**  has a specific permission for reading of beneficiary module and also access to this base is limited to only user that have role with admin access level. Further, **base_x2** assigned both read and write permission on beneficiary module, and its restricted to users with either coordinator or admin access level:

- base_**x1** <--- (role_admin:* , beneficiaries:read)
- base_**x2** <--- (role_admin:* , role_coordinator:*, beneficiaries:read, beneficiaries:write)
- admin <--- (beneficiaries:read, users:write)
- coordinator  <--- (beneficiaries:read, users:read)


*Implementation:*
-
**Assumptions:**

We includes organization_id and base_ids as part of JWT for all the users. This will reduce complexity of design for the time being but we still need to keep our DB in sync with Auth0 user for any changes.

**Auth0 Rules:**

Auth0 by default includes all the **permissions** for the roles assigned to a user in permissions array inside **JWT**, but in order to add custom permissions according to the user assigned bases, we need to use Auth0 Rules (Actions - newly introduced) feature that acts as a middleware within token generation process. The following picture captured from Auth0 depicted the how Rules applies during the authentication.

![enter image description here](https://images.ctfassets.net/cdy7uua7fh8z/1IdyXP3cxbukXrMYuCk3VG/ac5a8d8bc8b595b79ae544c7bcc0468b/rules-best-practice-pipeline.png)

Having base_ids in the Auth0 app meta data we can use that info to query permissions of the bases and finalized actual users permissions:

    function (user, context, callback) { 
	    var ManagementClient = require('auth0@2.9.1').ManagementClient; 
	    var management = new ManagementClient({ token: auth0.accessToken, domain: auth0.domain });
	    management.getUsers(function (err, users) { 
			console.log(users); callback(null, user, context);
	    }); 
	} 


More detail information about this can be access via: https://auth0.com/docs/rules/use-management-api

## Decision

 ### Comparison of Different Auth0 Authorization
 
|                      | Detail                                                                   | Authorization Core                                                                                                                                                                                                                                      | Authorization Extension                                                                                                                                             | Authorization Organization                                                                                                                                                                   |
|----------------------|--------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Features             | Enhanced performance and scalability                                     | Yes - Read Entity Limit Policy (Roles per tenant 1000, Roles per user 50, Permissions per user 1000 and Permissions per role 1000)                                                                                                                      | No - Limited to 500KB of data (1000 groups, 3000 users, where each user is a member of 3 groups; or 20 groups, 7000 users, where each user is a member of 3 groups) | Yes - Rate Limit Policy (Organizations per tenant 100000, Members per organization 100000, Connections per organization 10, Role assignments per organization member 50)                             |
|                      | Create/edit/delete Roles                                                 | Yes                                                                                                                                                                                                                                                     | Yes                                                                                                                                                                 | Yes                                                                                                                                                                                                  |
|                      | Roles can contain permissions from one or more APIs                      | Yes                                                                                                                                                                                                                                                     | No                                                                                                                                                                  | No                                                                                                                                                                                                   |
|                      | Users and Roles can be assigned to Groups                                | No                                                                                                                                                                                                                                                      | Yes                                                                                                                                                                 | No                                                                                                                                                                                                   |
|                      | Roles are attached to specific applications                              | No                                                                                                                                                                                                                                                      | Yes                                                                                                                                                                 | No                                                                                                                                                                                                   |
|                      | Create/edit/delete Users                                                 | Yes                                                                                                                                                                                                                                                     | Yes                                                                                                                                                                 | Yes                                                                                                                                                                                                  |
|                      | Search Users by user, email, connection                                  | Yes                                                                                                                                                                                                                                                     | Yes                                                                                                                                                                 | Yes                                                                                                                                                                                                  |
|                      | Search Users by identity provider, login count, last login, phone number | Yes                                                                                                                                                                                                                                                     | No                                                                                                                                                                  | Yes                                                                                                                                                                                                  |
|                      | Search Users using Lucene syntax                                         | Yes                                                                                                                                                                                                                                                     | No                                                                                                                                                                  | Yes                                                                                                                                                                                                  |
|                      | User import/export via JSON                                              | Not currently                                                                                                                                                                                                                                           | Yes                                                                                                                                                                 | Yes                                                                                                                                                                                                  |
|                      | Create custom authorization policies                                     | Yes                                                                                                                                                                                                                                                     | No - does not support rule                                                                                                                                          | Yes                                                                                                                                                                                                  |
|                      | Special Login page per Organization                                      | No                                                                                                                                                                                                                                                      | No                                                                                                                                                                  | Yes                                                                                                                                                                                                  |
|                      | Rules                                                                    | 10                                                                                                                                                                                                                                                      |                                                                                                                                                                     | 10                                                                                                                                                                                                   |
|                      | Login Interface                                                          |                                                                                                                                                                                                                                                         |                                                                                                                                                                     | Supported only for New Universal Login (not supported for Classic Universal Login or Lock.js).                                                                                                       |
|                      | Auth0 recommendation                                                     | If you are looking to represent teams, business customers, or partners in a B2B or SaaS application, we recommend representing them as Organizations and using Authorization Core. The Authorization Extension does not have support for Organizations. |                                                                                                                                                                     |                                                                                                                                                                                                      |
|                      | Prerequisites                                                            | -                                                                                                                                                                                                                                                       | Need extention installation                                                                                                                                         | Need Organization plan (Cost involved)                                                                                                                                                               |
| Ease of use          | In term of DropApp and New Boxtribute Stack                              | This can be done by flattened RBAC model and via Auth0 Management API roles and permissions can be sync                                                                                                                                                 | Using groups may solve issue arround group_id but add complexity to new system                                                                                      | this is more ideal ways to implement as we have different organizations, but will added another layer of complexity to our system; also cost involved as we may need go for some other Auth0 plans   |
| Flexibility of Rules |                                                                          | Although we need to create some dynamic roles and permission but its not too complicated as long as the number of bases and roles not too high (<50)                                                                                                    | May remove the need to have some dynamic roles but as custome authorization policiy not support its make it difficult to use properly in our case                   | This is prefect as long as we need to have various organization and different roles and permissions but management of all these add another layer of complexity to our system                        |


**Based on the above comparisons it decided that we go for Auth0 Core and simplify / flatten our current RBAC and use of Auth0 Policy Rule to add required information to JWT claims.** 

## Consequences

### Easier:
Auth0 Core simplify the permissions and roles and still with Rules / Actions some business rules can be defined as part of generation of JWT. 
### More difficult:
Auth0 Organizational can be more fitted with the current group_id in use in legacy  system but its make our system so tied into Auth0 any change in their current model.
