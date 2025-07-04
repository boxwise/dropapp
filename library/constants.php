<?php

// static - temp

$menusToActions = [
    [
        'id' => 87,
        'module' => 'Free Shop',
        'menu' => 'Checkout',
        'action' => 'check_out',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'checkout_beneficiaries',
        ],
    ],
    [
        'id' => 110,
        'module' => 'Free Shop',
        'menu' => 'Stockroom',
        'action' => 'container-stock',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'view_inventory',
        ],
    ],
    [
        'id' => 111,
        'module' => 'Free Shop',
        'menu' => 'Generate market schedule',
        'action' => 'market_schedule',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'generate_market_schedule',
        ],
    ],
    [
        'id' => 92,
        'module' => 'Tokens',
        'menu' => 'Give tokens <span>to</span> all',
        'action' => 'give2all',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'manage_tokens',
        ],
    ],
    [
        'id' => 67,
        'module' => 'Admin',
        'menu' => 'Manage products',
        'action' => 'products',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'manage_products',
        ],
    ],
    [
        'id' => 115,
        'module' => 'Admin',
        'menu' => 'Edit Warehouses',
        'action' => 'locations',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'manage_warehouses',
        ],
    ],
    [
        'id' => 43,
        'module' => 'Admin',
        'menu' => 'Manage Users',
        'action' => 'cms_users',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '1',
        'action_permission' => [
            'manage_volunteers',
            'manage_coordinators',
            'manage_admins',
        ],
    ],
    [
        'id' => 112,
        'module' => 'Inventory',
        'menu' => 'Print box labels',
        'action' => 'qr',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'create_label',
        ],
    ],
    [
        'id' => 90,
        'module' => 'Inventory',
        'menu' => 'Manage Boxes',
        'action' => 'stock',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'manage_inventory',
        ],
    ],
    [
        'id' => 166,
        'module' => 'Inventory',
        'menu' => 'NEW Manage Boxes (<span>beta</span>)',
        'action' => 'new_manage_boxes',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'manage_inventory',
        ],
    ],
    [
        'id' => 160,
        'module' => 'Inventory',
        'menu' => 'Stock Overview',
        'action' => 'stock_overview',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'view_inventory',
        ],
    ],
    [
        'id' => 123,
        'module' => 'Report',
        'menu' => 'Start page',
        'action' => 'start',
        'adminonly' => '0',
        'visible' => '0',
        'allusers' => '1',
        'allcamps' => '0',
        'action_permission' => [
            'view_start_page',
        ],
    ],
    [
        'id' => 125,
        'module' => 'Profile',
        'menu' => 'User profile',
        'action' => 'cms_profile',
        'adminonly' => '0',
        'visible' => '0',
        'allusers' => '1',
        'allcamps' => '0',
        'action_permission' => [
            'be_user',
        ],
    ],
    [
        'id' => 96,
        'module' => 'Report',
        'menu' => 'Sales reports',
        'action' => 'sales_list',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'list_sales',
        ],
    ],
    [
        'id' => 102,
        'module' => 'Report',
        'menu' => 'Fancy graphs (<span>beta</span>)',
        'action' => 'fancygraphs',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'view_beneficiary_graph',
        ],
    ],
    [
        'id' => 167,
        'module' => 'Report',
        'menu' => 'Dashboard v2 (<span>beta</span>)',
        'action' => 'statviz_dashboard',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'view_inventory',
            'view_shipments',
            'view_beneficiary_graph',
        ],
    ],
    [
        'id' => 145,
        'module' => 'Library',
        'menu' => 'Library titles (<span>beta</span>)',
        'action' => 'library_inventory',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'manage_library',
        ],
    ],
    [
        'id' => 146,
        'module' => 'Library',
        'menu' => 'Lent out (<span>beta</span>)',
        'action' => 'library',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'show_library_history',
        ],
    ],
    [
        'id' => 157,
        'module' => 'Admin',
        'menu' => 'Bases',
        'action' => 'camps',
        'adminonly' => '1',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '1',
        'action_permission' => [
            'manage_base',
            'be_god',
        ],
    ],
    [
        'id' => 154,
        'module' => 'Admin',
        'menu' => 'Organisations',
        'action' => 'organisations',
        'adminonly' => '1',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '1',
        'action_permission' => [
            'manage_organizations',
            'be_god',
        ],
    ],
    [
        'id' => 50,
        'module' => 'Admin',
        'menu' => 'Manage menu functions',
        'action' => 'cms_functions',
        'adminonly' => '1',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '1',
        'action_permission' => [
            'be_god',
        ],
    ],
    [
        'id' => 44,
        'module' => 'Admin',
        'menu' => 'Settings',
        'action' => 'cms_settings',
        'adminonly' => '1',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '1',
        'action_permission' => [
            'be_god',
        ],
    ],
    [
        'id' => 45,
        'module' => 'Admin',
        'menu' => 'Texts',
        'action' => 'cms_translate',
        'adminonly' => '1',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '1',
        'action_permission' => [
            'be_god',
        ],
    ],
    [
        'id' => 158,
        'module' => 'Beneficiaries',
        'menu' => 'Add beneficiary',
        'action' => 'people_add',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'create_beneficiaries',
        ],
    ],
    [
        'id' => 118,
        'module' => 'Beneficiaries',
        'menu' => 'Manage beneficiaries',
        'action' => 'people',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'manage_beneficiaries',
        ],
    ],
    [
        'id' => 162,
        'module' => 'Beneficiaries',
        'menu' => 'Manage tags',
        'action' => 'tags',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'manage_tags',
        ],
    ],
    [
        'id' => 164,
        'module' => 'Transfers',
        'menu' => 'Manage Shipments',
        'action' => 'shipments',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'view_shipments',
        ],
    ],
    [
        'id' => 165,
        'module' => 'Transfers',
        'menu' => 'Manage Agreements',
        'action' => 'transfer_agreements',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'view_transfer_agreements',
        ],
    ],
    [
        'id' => 168,
        'module' => 'Services',
        'menu' => 'Use Service (<span>beta</span>)',
        'action' => 'use_service',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'register_service_usage',
        ],
    ],
    [
        'id' => 169,
        'module' => 'Services',
        'menu' => 'Manage Services (<span>beta</span>)',
        'action' => 'services',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '0',
        'action_permission' => [
            'manage_services',
        ],
    ],
    [
        'id' => 170,
        'module' => 'Admin',
        'menu' => 'Base Settings (<span>beta</span>)',
        'action' => 'base_settings',
        'adminonly' => '0',
        'visible' => '1',
        'allusers' => '0',
        'allcamps' => '1',
        'action_permission' => [
            'manage_base_settings',
        ],
    ],
];

// This has to be identical to the rolesMapping in the Auth0 dynamic-permissions script
$rolesToActions = [
    'boxtribute_god' => [
        'be_god',
    ],

    'administrator' => [
        'manage_products',
        'manage_volunteers',
        'manage_coordinators',
        'manage_admins',
        'manage_warehouses',
        'create_label',
        'view_inventory',
        'manage_inventory',
        'create_beneficiaries',
        'manage_beneficiaries',
        'delete_transactions',
        'delete_beneficiary',
        'manage_tags',
        'manage_tokens',
        'checkout_beneficiaries',
        'list_sales',
        'view_beneficiary_graph',
        'view_start_page',
        'be_user',
        'create_transfer_agreement',
        'cancel_transfer_agreement',
        'reject_transfer_agreement',
        'accept_transfer_agreement',
        'create_shipment',
        'send_shipment',
        'cancel_shipment',
        'receive_shipment',
        'view_transfer_agreements',
        'view_shipments',
        'fill_shipment',
        'create_shareable_link',
        'manage_services',
        'register_service_usage',
        'manage_base_settings',
    ],

    'coordinator' => [
        'manage_products',
        'manage_volunteers',
        'manage_warehouses',
        'create_label',
        'view_inventory',
        'manage_inventory',
        'create_beneficiaries',
        'manage_beneficiaries',
        'delete_transactions',
        'delete_beneficiary',
        'manage_tags',
        'manage_tokens',
        'checkout_beneficiaries',
        'list_sales',
        'view_beneficiary_graph',
        'view_start_page',
        'be_user',
        'create_transfer_agreement',
        'cancel_transfer_agreement',
        'reject_transfer_agreement',
        'accept_transfer_agreement',
        'create_shipment',
        'send_shipment',
        'cancel_shipment',
        'receive_shipment',
        'view_transfer_agreements',
        'view_shipments',
        'fill_shipment',
        'create_shareable_link',
        'manage_services',
        'register_service_usage',
        'manage_base_settings',
    ],

    'warehouse_volunteer' => [
        'create_label',
        'view_inventory',
        'manage_inventory',
        'view_start_page',
        'be_user',
        'receive_shipment',
        'view_transfer_agreements',
        'view_shipments',
        'fill_shipment',
    ],

    'free_shop_volunteer' => [
        'create_beneficiaries',
        'manage_beneficiaries',
        'checkout_beneficiaries',
        'view_inventory',
        'view_beneficiary_graph',
        'view_start_page',
        'be_user',
        'list_sales',
        'register_service_usage',
    ],

    'library_volunteer' => [
        'manage_library',
        'show_library_history',
        'view_start_page',
        'be_user',
    ],

    'label_creation' => [
        'create_label',
        'be_user',
    ],

    'external_free_shop_checkout' => [
        'checkout_beneficiaries',
        'be_user',
    ],

    'authenticated_user' => [
        'be_user',
    ],
];
// ----------------------------
