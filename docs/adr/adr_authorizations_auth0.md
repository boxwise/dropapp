
# Authorization with Auth0

Trello-card: https://trello.com/c/kypUAjlw/747-vahid-will-create-adr-for-authentication-authorization-implementation-design

## Status

proposed

## Context or Problem Statement

DropApp currently uses embedded SQL queries to handle authorization. Right now, the system has a very granular access model for managing access to functionalities. 

## Decision Drivers 

1. Resource Types
2. Identity Types
3. Underling’s Protocols
4. Flexibility of Rules
5. Ease of Implementation 

## Considered Options

- **Auth0 Core Authorization** (roles and permissions - rules)
- **Auth0 Extended Authorization** (roles, permissions and groups - rules)
- **Auth0 Organizations Authorization** (roles, permissions, organizations - rule)

## Discussion

The current system access control looks like this:
 - [x] Each *User* has belong to a *Role (User Group)*
 - [x] Each *Role* belong to  *Group (User Group Level)*
 - [x] Each *Role  (User Group)* contains 1 to Many *Resource / Permission (Function)* 
 - [x] Each *Role  (User Group)* has access to 1 to Many *Base (Camp)*
 - [x] Each *Organization* has a *Base (Camp)*
 - [x] Each *Base (Camp)* can access by 1 to Many *Role  (User Group)*
 - [x] Each *Base (Camp)* contains predefined *Resource / Permission (Function)* 

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

Suggested Roles
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

### Example of flattened authorization based on Auth0:

*Organization X* which has two bases in Europe named *Base X1* and *Base X2*. User with Admin and Coordinator roles can access *Base X1* , while only Coordinator should able to access Base X2. 

**Roles:**

- base_**x1** [this role used to customized the permissions for Base X1]
- base_**x2**
- coordinator
- admin

**Permissions:**

In order to give the system the flexibility to create various kind of roles which can be used for various organization and bases, we can create dynamic permissions. This mean all the bases that already created in the system will be added as permission to Auth0. For that, we can create permission as:

    'base_' + [base identifier] + ':*' 

- base_**x1**:*
- base_**x2**:*
- role_coodinator:*
- role_**admin**:*
- role_**volunteer**:*
 - users:read
 - users:write
 - beneficiaries:read
 - beneficiaries:write

**Roles and Permissions Assignment:**

- base_**x1** <--- (role_admin:* , beneficiaries:read)
- base_**x2** <--- (role_admin:* , role_coodinator:*, beneficiaries:read, beneficiaries:write)
- admin <--- (beneficiaries:read, users:write)
- coordinator  <--- (beneficiaries:read, users:read)


## Decision



## Consequences

###Easier:

###More difficult:

