# Database description #

This list describes the database setup, indicating for which part of the dropapp the table is used. And also it indicates whether some records need to be present in that table in order for the app to work. 

### Obsolete ###

These tables can possible be deleted

- pagetree (part of the CMS functions of the framework, but not used)
- numbers 
- products_prices
- redirect (part of the CMS functions of the framework, but not used)
- units (used for the old food functions, can be deleted)

### Maybe obsolete? ###

- tipofday (Are we going to use this or lets just phase this out?)


### Framework tables ###

- cms_access (Crosstable to manage which functions are available for which users. Admins have all functions, so app works with empty table)
- cms_functions (The elements of the app that are available, refering to files in /include, basic record set required)
- cms_functions_camps (Define which functions are available for which camp, basic record set required)
- cms_users (List of users, at least one record with is_admin as true, to login and create new users)
- cms_users_camps (Define which users have access to which camps. Admins have all camps, so app works with empty table)
- history (Keeps all changes that are done by editing and saving a form in the framework, no record necessary)
- languages (App languages and also languages list for people list, at least one record is needed, not user editable)
- settings (Some app settings, some of those need to move to the camps table. Almost all records required)
- settings_categories (Not very actively used, but record is required. As the settings table is not used so intensivley anymore, we can phase this category function out here)
- translate (Many texts/phrases for the framework, to allow multilanguage, keep all records - although there are many obsolete ones)
- translate_categories (to order the translates into categories, keep all records)

### Drop app core tables for stock, warehouse, shop ###

- bases (App doesnt function without at least one record)
- genders (Not user editable, table with all records that need to be present)
- itemsout (Hans added this for stats reasons, not necessary for daily operations. No record necessary)
- locations (Locations, at least one record per base necessary)
- need_periods (List of timing for needed items list, need to have one record at least)
- people (List of beneficiaries, app works without records)
- product_categories (Not user editable, full set of records need to be present)
- products (No records needed)
- qr (links the hash that makes the qr-code to a id that is used in the stock table, no records needed)
- sizegroup (defines which set of sizes are selectable for a product, full set of records needed)
- sizes (full set of records is required)
- stock (Each box has a record, app functions without records)
- transactions ('Give tokens' actions are recorded here and also every purchase. No records needed)
- x_people_languages (to record what language each beneficiaries speaks, can be empty)

### Bicycle station/sport gear tables ###

The bicycle station is not made (yet) to suppport multiple bases

- borrow_categories (Not user editable, at least one record need to be present)
- borrow_items (All lendable items, no record necessary)
- borrow_locations (Not user editable, at least one record need to be present)
- borrow_transactions (All borrow transactions, one record for taking out an item and one for the return, no record necessary)

### Laundry station ###

- laundry_appointments (List of all appointments, no record necessary)
- laundry_machines (List of machines, not user editable)
- laundry_slots (List of slots, including each machine, a number of days in a repeating systematic approach and a number of time slots each day)
- laundry_stations (List of stations, one record is necessary)
- laundry_times (Definition of time slots, needs)

### Books library ###

- library (List of all books, no record necessary)
- library_transactions (List of all transactions
- library_type (Types of books, one record necessary)
