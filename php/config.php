<?php
// Default records per page
$default_records_per_page = 10;

// Global Ambetter Settings
$default_ambetter_column = 'policy_number';
$Ambetter = 'Ambetter';
$Ambetter_Columns = [
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
		'type' => 'select',
		'input' => [
			'placeholder' => 'Select...',
			'type' => 'select',
			'required' => false,
			'options' => [
				'' => '',
				'on' => 'On',
				'off' => 'Off'
			],
			'validate_msg' => 'Please select an On/Off Exchange option',
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
		'type' => 'tel',
		'input' => [
			'placeholder' => 'Phone Number',
			'type' => 'tel',
			'required' => false,
			'validate_msg' => 'Please enter a valid phone number!',
			'validate_regex' => '/^(\d{3})[\s.-]?(\d{3})[\s.-]?(\d{4})$/',
		]
	],
	'member_email' => [
		'label' => 'Member Email',
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
			'validate_msg' => 'Member Responsibility must be between 1 and 50 characters!',
			'/^[a-zA-Z0-9._%+\- ]+$/',
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


$default_agentcrm_column = 'contact_id';
$AgentCRM = 'AgentCRM';
$AgentCRM_all_Columns = [
	'contact_id' => [
		'label' => 'Contact Id',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter Contact ID',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Contact ID must not be empty!',
			'validate_regex' => '/^.{1,255}$/',
		]
	],
	'policy_number' => [
		'label' => 'Policy Number',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter Policy Number',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Policy number must be between 1 and 255 characters!',
			'validate_regex' => '/^[a-zA-Z0-9\s]{1,255}$/',
		]
	],
	'first_name' => [
		'label' => 'First Name',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter First Name',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'First name must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\s]{1,50}$/',
		]
	],
	'last_name' => [
		'label' => 'Last Name',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter Last Name',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'Last name must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\s\-\'\.]{1,50}$/',
		]
	],
	'phone' => [
		'label' => 'Phone',
		'sortable' => true,
		'type' => 'tel',
		'input' => [
			'placeholder' => 'Enter Phone Number',
			'type' => 'tel',
			'required' => false,
			'validate_msg' => 'Phone number must be between 1 and 15 characters!',
			'validate_regex' => '/^[\d\s()+-]{1,15}$/',
		]
	],
	'email' => [
		'label' => 'Email',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter Email Address',
			'type' => 'email',
			'required' => false,
			'validate_msg' => 'Must be a valid email address!',
		]
	],
	'state' => [
		'label' => 'State',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter State',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'State must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\s]{1,50}$/',
		]
	],
	'DOB' => [
		'label' => 'Date_of_Birth',
		'sortable' => true,
		'type' => 'date',
		'input' => [
			'placeholder' => 'Enter Date of Birth',
			'type' => 'date',
			'required' => false,
			'validate_msg' => 'Must be a valid date!',
		]
	],
	'closure' => [
		'label' => 'Closure',
		'sortable' => true,
		'type' => 'text',
		'input' => [
			'placeholder' => 'John Sins',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Please enter a closure!'
		]
	],
	'closure_date' => [
		'label' => 'Closure_Date',
		'sortable' => true,
		'type' => 'date',
		'input' => [
			'placeholder' => 'Enter Closure Date',
			'type' => 'date',
			'required' => true,
			'validate_msg' => 'Must be a valid date!',
			// No need for regex here since 'date' type input inherently validates the format.
		]
	],
	'closure_time' => [
		'label' => 'Closure_Time',
		'sortable' => true,
		'type' => 'time',
		'input' => [
			'placeholder' => 'Enter Closure Time',
			'type' => 'time',
			'required' => true,
			'validate_msg' => 'Must be a valid time!',
			// No need for regex here since 'time' type input inherently validates the format.
		]
	],
	'closure_stage' => [
		'label' => 'Closure_Stage',
		'sortable' => true,
		'type' => 'text',
		'input' => [
			'placeholder' => 'OP/RC Completed',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Please enter a closure stage!',
		],
	],
	'closure_pipeline' => [
		'label' => 'Closure_Pipeline',
		'sortable' => true,
		'type' => 'text',
		'input' => [
			'placeholder' => 'NEW LEAD',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Please enter a valid pipeline!',
		],
	],	
	'team_name' => [
		'label' => 'Team Name',
		'sortable' => true,
		'type' => 'text',
		'input' => [
			'placeholder' => 'Enter Team Name',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Please enter a team name!',
		],
	]	
];

$default_agentcrm_column = 'contact_id';
$AgentCRM = 'AgentCRM';
$AgentCRM_Columns = [
	'policy_number' => [
		'label' => 'Policy Number',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter Policy Number',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Policy number must be between 1 and 255 characters!',
			'validate_regex' => '/^[a-zA-Z0-9\s]{1,255}$/',
		]
	],
	'first_name' => [
		'label' => 'First Name',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter First Name',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'First name must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\s]{1,50}$/',
		]
	],
	'last_name' => [
		'label' => 'Last Name',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter Last Name',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'Last name must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\s\-\'\.]{1,50}$/',
		]
	],
	'phone' => [
		'label' => 'Phone',
		'sortable' => true,
		'type' => 'tel',
		'input' => [
			'placeholder' => 'Enter Phone Number',
			'type' => 'tel',
			'required' => false,
			'validate_msg' => 'Phone number must be between 1 and 15 characters!',
			'validate_regex' => '/^[\d\s()+-]{1,15}$/',
		]
	],
	'email' => [
		'label' => 'Email',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter Email Address',
			'type' => 'email',
			'required' => false,
			'validate_msg' => 'Must be a valid email address!',
		]
	],
	'state' => [
		'label' => 'State',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter State',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'State must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\s]{1,50}$/',
		]
	],
	'DOB' => [
		'label' => 'Date_of_Birth',
		'sortable' => true,
		'type' => 'date',
		'input' => [
			'placeholder' => 'Enter Date of Birth',
			'type' => 'date',
			'required' => false,
			'validate_msg' => 'Must be a valid date!',
		]
	],
	'closure' => [
		'label' => 'Closure',
		'sortable' => true,
		'type' => 'text',
		'input' => [
			'placeholder' => 'John Sins',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Please enter a closure!'
		]
	],
	'closure_date' => [
		'label' => 'Closure_Date',
		'sortable' => true,
		'type' => 'date',
		'input' => [
			'placeholder' => 'Enter Closure Date',
			'type' => 'date',
			'required' => true,
			'validate_msg' => 'Must be a valid date!',
		]
	],
	'closure_time' => [
		'label' => 'Closure_Time',
		'sortable' => true,
		'type' => 'time',
		'input' => [
			'placeholder' => 'Enter Closure Time',
			'type' => 'time',
			'required' => true,
			'validate_msg' => 'Must be a valid time!',
		]
	],
	'closure_stage' => [
		'label' => 'Closure_Stage',
		'sortable' => true,
		'type' => 'text',
		'input' => [
			'placeholder' => 'OP/RC Completed',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Please enter a closure stage!',
		],
	],
	'closure_pipeline' => [
		'label' => 'Closure_Pipeline',
		'sortable' => true,
		'type' => 'text',
		'input' => [
			'placeholder' => 'NEW LEAD',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Please enter a valid pipeline!',
		],
	],	
	'team_name' => [
		'label' => 'Team Name',
		'sortable' => true,
		'type' => 'text',
		'input' => [
			'placeholder' => 'Enter Team Name',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Please enter a team name!',
		],
	]	
];

// Global Admin Employee Settings
$default_employee_column = 'id';
$Employee = 'Employee';
$Employee_Columns = [
	'id' => [
		'label' => '#',
		'sortable' => true,
		'type' => 'integer'
	],
    'username' => [
		'label' => 'username',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Username',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'Must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\s]{1,50}$/',
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
	'email' => [
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
	'password' => [
		'label' => 'Password',
		'sortable' => false,
		'type' => 'password',
		'input' => [
			'placeholder' => 'Enter password',
			'type' => 'password',
			'required' => true,
			'validate_msg' => 'Please enter a valid password',
			'validate_regex' => '/^.{8,}$/',
		]
	],
	'activation_code' => [
		'label' => 'Activation Code',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter activation code',
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Please enter a valid activation code',
			'validate_regex' => '/^[A-Za-z0-9]{1,50}$/',
		]
	],
	'rememberme' => [
		'label' => 'Remember Me',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Remember Me Token',
			'type' => 'text',
			'required' => false,
		]
	],
	'phone' => [
		'label' => 'Phone_Number',
		'sortable' => true,
		'type' => 'tel',
		'input' => [
			'placeholder' => 'Enter phone number',
			'type' => 'tel', 
			'required' => true,
			'validate_msg' => 'Please enter a valid phone number',
			'validate_regex' => '/^\+?[0-9]{10,20}$/',
		]
	],
	'created' => [
		'label' => 'Creation_Date',
		'sortable' => true,
		'type' => 'datetime',
		'input' => [
			'type' => 'datetime-local',
			'required' => true,
		]
	],
	'registered' => [
		'label' => 'Registration_Date',
		'sortable' => true,
		'type' => 'datetime',
		'input' => [
			'type' => 'datetime-local',
			'required' => true,
		]
	],
	'role' => [
		'label' => 'User Role',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'type' => 'text',
			'required' => true,
			'validate_msg' => 'Please select a valid role',
			'validate_regex' => '/^(admin|employee|guest)$/',
		]
	],
	'last_seen' => [
		'label' => 'Last_Seen',
		'sortable' => true,
		'type' => 'datetime',
		'input' => [
			'type' => 'datetime-local',
			'required' => false,
		]
	]
];

// Global Employee Settings for Profile changes
$Employee = 'Employee';
$Employee_Profile_Columns = [
	'id' => [
		'label' => '#',
		'sortable' => true,
		'type' => 'integer'
	],
    'username' => [
		'label' => 'username',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Username',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'Must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\s]{1,50}$/',
		]
	],
    'first_name' => [
		'label' => 'First Name',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Erick',
			'type' => 'text',
			'required' => false,
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
			'required' => false,
			'validate_msg' => 'Last name must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\'-]{1,50}$/',
		]
	],
	'email' => [
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
	'phone' => [
		'label' => 'Phone Number',
		'sortable' => true,
		'type' => 'tel',
		'input' => [
			'placeholder' => 'Enter phone number',
			'type' => 'tel', 
			'required' => false,
			'validate_msg' => 'Please enter a valid phone number',
			'validate_regex' => '/^\+?[0-9]{10,20}$/',
		]
	]
];

// Global Employee Settings for Admin changes
$Employee = 'Employee';
$Employee_Admin_Columns = [
	'id' => [
		'label' => '#',
		'sortable' => true,
		'type' => 'integer'
	],
    'username' => [
		'label' => 'username',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Username',
			'type' => 'text',
			'required' => false,
			'validate_msg' => 'Must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\s]{1,50}$/',
		]
	],
    'first_name' => [
		'label' => 'First Name',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Erick',
			'type' => 'text',
			'required' => false,
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
			'required' => false,
			'validate_msg' => 'Last name must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\'-]{1,50}$/',
		]
	],
	'email' => [
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
	'phone' => [
		'label' => 'Phone Number',
		'sortable' => true,
		'type' => 'tel',
		'input' => [
			'placeholder' => 'Enter phone number',
			'type' => 'tel', 
			'required' => false,
			'validate_msg' => 'Please enter a valid phone number',
			'validate_regex' => '/^\+?[0-9]{10,20}$/',
		]
	],
	'activation_code' => [
		'label' => 'Activation',
		'sortable' => true,
		'type' => 'select',
		'input' => [
			'placeholder' => 'Select...',
			'type' => 'select',
			'required' => true,
			'validate_msg' => 'Required: Is this Employee Active?',
			'options' => [
				0 => 0,
				1 => 1,
			],
		],
	],
	'role' => [
		'label' => 'Employee Role',
		'sortable' => true,
		'type' => 'select',
		'input' => [
			'placeholder' => 'Select...',
			'type' => 'select',
			'required' => true,
			'validate_msg' => 'Required: What Is the Employee Role',
			'options' => [
				'guest' => 'guest',
				'agent' => 'agent',
				'admin' => 'admin'
			],
		],
	]
];

// Global Settings for Leads
$default_leads_column = 'id';
$Leads = 'Leads';
$Leads_Columns = [
	'id' => [
		'label' => '#',
		'sortable' => true,
		'type' => 'integer'
	],
    'first_name' => [
		'label' => 'First Name',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Erick',
			'type' => 'text',
			'required' => false,
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
			'required' => false,
			'validate_msg' => 'Last name must be between 1 and 50 characters!',
			'validate_regex' => '/^[a-zA-Z\'-]{1,50}$/',
		]
	],
	'email' => [
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
	'phone' => [
		'label' => 'Phone_Number',
		'sortable' => true,
		'type' => 'tel',
		'input' => [
			'placeholder' => 'Enter phone number',
			'type' => 'tel', 
			'required' => false,
			'validate_msg' => 'Please enter a valid phone number',
			'validate_regex' => '/^\+?[0-9]{10,20}$/',
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
	'DOB' => [
		'label' => 'Member_Date_of_Birth',
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
	'serviced' => [
		'label' => 'Serviced',
		'sortable' => true,
		'type' => 'select',
		'input' => [
			'placeholder' => 'Select...',
			'type' => 'select',
			'required' => true,
			'validate_msg' => 'Has this lead been serviced!',
			'options' => [
				0 => 0,
				1 => 1,
			],
		],
	],
	'created' => [
		'label' => 'Creation_Date',
		'sortable' => true,
		'type' => 'datetime',
		'input' => [
			'type' => 'datetime-local',
			'required' => true,
		]
	],
	'recontact_date' => [
		'label' => 'Recontact_Datetime',
		'sortable' => true,
		'type' => 'datetime',
		'input' => [
			'type' => 'datetime-local',
			'required' => false,
		]
	],
	'notes' => [
		'label' => 'Notes',
		'sortable' => true,
		'type' => 'text',
		'input' => [
			'type' => 'textarea',
			'required' => false,
			'maxlength' => 500
		]
	]
];