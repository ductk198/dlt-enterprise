<?php

return [
    'users' => [
            'name' => 'Quản trị người dùng',
            'icon' => 'fa fa-user-circle',
			'child' => false,
			'check_permision' => false
	],
	'listtype' => [
		'name' => 'Quản trị danh mục',
		'icon' => 'fa fa-list',
		'check_permision' => false,
		'child' => [
			'listtype' => [
				'name' => 'Danh mục đối tượng',
				'icon' => 'fa fa-angle-double-right',
			],
			'list' => [
				'name' => 'Danh mục',
				'icon' => 'fa fa-angle-double-right',
			]
		]
	],
	'position' => [
		'name' => 'Chức vụ',
		'icon' => 'fa fa-id-card-o',
		'check_permision' => 'ADMIN_SYSTEM',
		'child' => [
			'positiongroup' => [
				'name' => 'Nhóm chức vụ',
				'icon' => 'fa fa-angle-double-right',
			],
			'position' => [
				'name' => 'Chức vụ',
				'icon' => 'fa fa-angle-double-right',
			]
		]
	],
	'enterprise' => [
		'name' => 'Quản trị doanh nghiệp',
		'icon' => 'fa fa-apple',
		'check_permision' => 'ADMIN_SYSTEM',
		'child' => false
	],
	'system' => [
		'name' => 'Quản trị hệ thống',
		'icon' => 'fa fa-cogs',
		'check_permision' => 'ADMIN_SYSTEM',
		'child' => [
			'code' => [
				'name' => 'Manager Code',
				'icon' => 'fa fa-angle-double-right',
			],
			'sql' => [
				'name' => 'Manager Sql',
				'icon' => 'fa fa-angle-double-right',
			],
		]
	],
];
