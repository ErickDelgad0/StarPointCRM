<?php
// Default records per page
$default_records_per_page = 20;

// Global Ambetter Settings
$default_ambetter_column = 'id';
$Ambetter = 'Ambetter';
$Ambetter_Columns = [
	'id' => [
		'label' => '#',
		'sortable' => true,
		'type' => 'integer'
	],
    'broker_name' => [
		'label' => 'Broker Name',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Erick Delgado',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'Must be between 1 and 255 characters!',
			'validate_regex' => '/^[a-zA-Z\s]{1,255}$/',
		]
	],
	'broker_npn' => [
		'label' => 'Broker NPN',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => '123456789',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'Must be between 1 and 50 characters!',
			'validate_regex' => '/^[0-9]{1,50}$/',
		]
	],
    'policy_number' => [
		'label' => 'Policy Number',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'N12345678',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Policy Number must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z0-9]{1,50}$/',
		]
	],
    'first_name' => [
		'label' => 'First Name',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Erick',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Last name must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\'-]{1,50}$/',
		]
	],
	'last_name' => [
		'label' => 'Last Name',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Delgado',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Last name must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\'-]{1,50}$/',
		]
	],
    'broker_effective_date' => [
		'label' => 'Broker Effective Date',
		'sortable' => true,
		'type' => 'date',
		'input' => [
			'placeholder' => '2023-10-01',
			'type' => 'date',
			'required' => false,
			'validate_msg' => 'Please Enter a Valid Date',
			'validate_regex' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
		]
	],
    'broker_term_date' => [
		'label' => 'Broker Term Date',
		'sortable' => true,
		'type' => 'date',
		'input' => [
			'placeholder' => '2023-02-28',
			'type' => 'date',
			'required' => false,
			'validate_msg' => 'Please Enter a Valid Date',
			'validate_regex' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
		]
	],
    'policy_effective_date' => [
		'label' => 'Policy Effective Date',
		'sortable' => true,
		'type' => 'date',
		'input' => [
			'placeholder' => '2023-06-12',
			'type' => 'date',
			'required' => false,
			'validate_msg' => 'Please Enter a Valid Date',
			'validate_regex' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
		]
	],
    'policy_term_date' => [
		'label' => 'Policy Term Date',
		'sortable' => true,
		'type' => 'date',
		'input' => [
			'placeholder' => '2023-09-12',
			'type' => 'date',
			'required' => false,
			'validate_msg' => 'Please Enter a Valid Date',
			'validate_regex' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
		]
	],
    'paid_through_date' => [
		'label' => 'Paid Through Date',
		'sortable' => true,
		'type' => 'date',
		'input' => [
			'placeholder' => '2023-09-12',
			'type' => 'date',
			'required' => false,
			'validate_msg' => 'Please Enter a Valid Date',
			'validate_regex' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
		]
	],
    'county' => [
		'label' => 'County',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'MIAMIDADE',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'County must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z0-9 &.,\'-]{1,50}$/',
		]
	],
    'state' => [
		'label' => 'State',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'FL, HW, GA',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'State must be Abbreviated!',
			'validate_regex' => '/^[a-zA-Z]{2}$/',
		]
	],
	'on_off_exchange' => [
		'label' => 'On/Off Exchange',
		'sortable' => true,
		'type' => 'select', // Type is 'select' indicating a dropdown.
		'input' => [
			'placeholder' => 'Select...', // This might not be needed for a select type input
			'type' => 'select', // Redundant definition inside 'input', but it's okay if your form builder requires it
			'required' => false,
			'options' => [ // Options for the dropdown
				'' => '', // Empty option allowing users not to choose
				'on' => 'On',
				'off' => 'Off'
			],
			'validate_msg' => 'Please select an On/Off Exchange option', // Message for validation
		],
	],

    'exchange_subscriber_id' => [
		'label' => 'Exchange Subscriber id',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => '1234',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'County must be between 1 and 255 characters!',
			'validate_regex' => "/^[a-zA-Z0-9']{1,255}$/",
		]
	],
    'member_phone_number' => [
		'label' => 'Member Phone Number',
		'sortable' => false,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Phone Number',
			'type' => 'tel',
			'required' => false,
			'validate_msg' => 'Please enter a valid phone number!',
			'validate_regex' => '/^(\d{3})[\s.-]?(\d{3})[\s.-]?(\d{4})$/',
		]
	],
	'member_email' => [
		'label' => 'Email',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Email Address',
			'type' => 'email',
			'required' => false,
			'validate_msg' => 'Please enter a valid email address!',
			'validate_regex' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
		]
	],
    'member_responsibility' => [
		'label' => 'Member Responsibility',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => '',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'On/ Off Exchange must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z0-9._%+-]$/',
		]
	],
    'member_DOB' => [
		'label' => 'Member Date of Birth',
		'sortable' => true,
		'type' => 'date',
		'input' => [
			'placeholder' => '2001-08-12',
			'type' => 'date',
			'required' => true,
			'validate_msg' => 'Please Enter a Valid Date',
			'validate_regex' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',
		]
	],
	'autopay' => [
		'label' => 'Autopay',
		'sortable' => true,
		'type' => 'select',
		'input' => [
			'placeholder' => 'yes/no',
			'type' => 'select',
			'required' => false,
			'options' => [
				'' => '',
				'yes' => 'Yes',
				'no' => 'No',
			],
			'validate_msg' => 'Please select an Autopay option',
		]
	],
	'eligible_for_commission' => [
		'label' => 'Eligible for Commission',
		'sortable' => true,
		'type' => 'select',
		'input' => [
			'placeholder' => 'Select...',
			'type' => 'select',
			'required' => false,
			'options' => [
				'' => '',
				'yes' => 'Yes',
				'no' => 'No'
			],
			'validate_msg' => 'Please select if eligible for commission',
		]
	],
    'number_of_members' => [
		'label' => 'Number of Members',
		'sortable' => true,
		'type' => 'integer',
		'input' => [
			'placeholder' => '1, 2, 3',
			'type' => 'number',
			'required' => false,
			'validate_msg' => 'Number of members must be a number!',
			'validate_regex' => '/^[0-9]$/',
		]
	],
    'payable_agent' => [
		'label' => 'Payable Agent',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Ambetter Health',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'Payable agent must be between 1 and 255 characters!',
			'validate_regex' => '/^[a-zA-Z0-9 &.,\'-]{1,255}$/',
		]
	],

	'ar_policy_type' => [
		'label' => 'AR Policy Type',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Policy',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'Title must be between 1 and 255 characters long!',
			'validate_regex' => '/^[a-zA-Z]{1,50}$/',
		]
	],
	'created' => [
		'label' => 'Created',
		'sortable' => true,
		'type' => 'datetime',
		'input' => [
			'placeholder' => 'Created',
			'type' => 'datetime-local',
			'required' => true,
			'validate_msg' => 'Please enter a valid date!',
			'validate_regex' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}$/',
		]
	]
];
?>