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


$default_agentcrm_column = 'contact_id';
$AgentCRM = 'AgentCRM';
$AgentCRM_Columns = [
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
		'type' => 'string',
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
		'label' => 'Date of Birth',
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
		'type' => 'select',
		'input' => [
			'placeholder' => 'Select...',
			'type' => 'select',
			'required' => true,
			'validate_msg' => 'Please select a closure!',
			'options' => [
				'Genesis Romero' => 'Genesis Romero',
				'Juan Villatoro' => 'Juan Villatoro',
				'Roberto Valle' => 'Roberto Valle',
				'Navil Urbina' => 'Navil Urbina',
				'Johnny Urbina' => 'Johnny Urbina',
				'Emily Avila' => 'Emily Avila',
				'Jose Lopez' => 'Jose Lopez',
				'Arnaldo Williams' => 'Arnaldo Williams',
				'Micheal Corral' => 'Micheal Corral',
				'Michael Villanueva' => 'Michael Villanueva',
				'Jack Volpe' => 'Jack Volpe',
				'Nic Narzisi' => 'Nic Narzisi',
				'Jorge Serna' => 'Jorge Serna',
				'Alejandro Schulz' => 'Alejandro Schulz',
				'Juan Castro' => 'Juan Castro',
				'Eduardo Real' => 'Eduardo Real',
				'Miguel Gutierrez' => 'Miguel Gutierrez',
				'Jack Buffa' => 'Jack Buffa',
				'Adrian Alfonso' => 'Adrian Alfonso',
				'Kevin Freyre' => 'Kevin Freyre',
				'Cooper Scully' => 'Cooper Scully',
				'Amanda Benitez' => 'Amanda Benitez',
				'Taimi Silvera' => 'Taimi Silvera',
				'Joel Mendez' => 'Joel Mendez',
				'Andrew Melero' => 'Andrew Melero',
				'Eddie Durand' => 'Eddie Durand',
				'Ivannia Vilchez' => 'Ivannia Vilchez',
				'Peter Moran' => 'Peter Moran',
				'Evan Garcia' => 'Evan Garcia',
				'Nancy Lopez' => 'Nancy Lopez',
				'Juan Mila' => 'Juan Mila',
			]
		]
	],
	
	'closure_date' => [
		'label' => 'Closure Date',
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
		'label' => 'Closure Time',
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
		'label' => 'Closure Stage',
		'sortable' => true,
		'type' => 'select',
		'input' => [
			'placeholder' => 'Select...',
			'type' => 'select',
			'required' => true,
			'validate_msg' => 'Please select a closure stage!',
			'options' => [
				'Carrier' => 'Carrier',
				'Not Found-SSN/Income Needed Create New' => 'Not Found-SSN/Income Needed Create New',
				'SOLD AOR OP/RC - Ambetter' => 'SOLD AOR OP/RC/Ambetter',
				'Found - CANX/No Plan/ - SSN/Income Needed' => 'Found/CANX/No Plan/SSN/Income Needed',
				'Issues' => 'Issues',
				'Multi apps' => 'Multi apps',
				'NPN' => 'NPN',
				'state' => 'state',
				'SOLD OP/RC Ambetter SSN/Income Needed' => 'SOLD OP/RC Ambetter SSN/Income Needed',
				'DO NOT WORK - Unfinished Leads' => 'DO NOT WORK/Unfinished Leads',
				'ACA MYACA NEW LEAD PROCESSOR DISTRIBUTION' => 'MYACA NEW LEAD PROCESSOR DISTRIBUTION',
				'Not Processed - Multi apps' => 'Not Processed - Multi apps',
				'SOLD - AOR- OP/RC - Ambetter' => 'SOLD/AOR/OP/RC/Ambetter',
				'Found - No Plan/CANX - SSN/Income Needed' => 'Found/No Plan/CANX/SSN/Income Needed',
				'STATE EOM' => 'STATE EOM',
				'NOT FOUND' => 'NOT FOUND',
				'ACA MYACA NEW LEAD AGENT PIPELINE' => 'MYACA NEW LEAD AGENT PIPELINE',
				'MYACA End of month' => 'MYACA End of month',
				'Not Processed - Carrier' => 'Not Processed - Carrier',
			]
		]
	],
	'closure_pipeline' => [
		'label' => 'Closure Pipeline',
		'sortable' => true,
		'type' => 'select',
		'input' => [
			'placeholder' => 'Select...',
			'type' => 'select',
			'required' => true,
			'validate_msg' => 'Must be a valid pipeline!',
			'options' => [
				'ACA MYACA NEW LEAD PROCESSOR DISTRIBUTION' => 'MYACA NEW LEAD PROCESSOR DISTRIBUTION',
				'ACA MYACA NEW LEAD AGENT PIPELINE' => 'MYACA NEW LEAD AGENT PIPELINE',
				'MYACA End of month' => 'MYACA End of month',
				'ACA 2023BOOKFLG Pipeline' => 'ACA 2023BOOKFLG Pipeline',
			],
		],
	],
	'team_name' => [
		'label' => 'Team Name',
		'sortable' => true,
		'type' => 'select',
		'input' => [
			'placeholder' => 'Select...',
			'type' => 'select',
			'required' => true,
			'validate_msg' => 'Must select a team!',
			'options' => [
				'UF TEAM' => 'UF TEAM',
				'International CREW' => 'International CREW',
			],
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
		'label' => 'Phone Number',
		'sortable' => true,
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter phone number',
			'type' => 'tel', 
			'required' => true,
			'validate_msg' => 'Please enter a valid phone number',
			'validate_regex' => '/^\+?[0-9]{10,20}$/',
		]
	],
	'created' => [
		'label' => 'Creation Date',
		'sortable' => true,
		'type' => 'datetime',
		'input' => [
			'type' => 'datetime-local',
			'required' => true,
		]
	],
	'registered' => [
		'label' => 'Registration Date',
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
		'label' => 'Last Seen',
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
		'type' => 'string',
		'input' => [
			'placeholder' => 'Enter phone number',
			'type' => 'tel', 
			'required' => false,
			'validate_msg' => 'Please enter a valid phone number',
			'validate_regex' => '/^\+?[0-9]{10,20}$/',
		]
	]
];
?>