<?php

namespace Config;

use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var string[]
	 */
	public $ruleSets = [
		Rules::class,
		FormatRules::class,
		FileRules::class,
		CreditCardRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array<string, string>
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------

	public $createUser = [
		'firstName' => [
			'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[60]',
			'errors' => [
				'required' => 'Nie podano imienia użytkownika!',
				'alpha_numeric_space' => 'Imię zawiera niedozwolone znaki!',
				'min_length' => 'Imię musi się składać z conajmniej 3 znaków!',
				'max_length' => 'Imię nie może składać się z więcej niż 60 znaków!'
			]
		],
		'lastName' => [
			'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[60]',
			'errors' => [
				'required' => 'Nie podano nazwiska użytkownika!',
				'alpha_numeric_space' => 'Nazwisko zawiera niedozwolone znaki!',
				'min_length' => 'Nazwisko musi się składać z conajmniej 3 znaków!',
				'max_length' => 'Nazwisko nie może się składać z więcej niż 60 znaków'
			]
		],
		'email' => [
			'rules' => 'valid_email|is_unique[users.email]',
			'errors' => [
				'valid_email' => 'Podano niepoprawny adres e-mail!',
				'is_unique' => 'Podany adres e-mail jest już używany przez inne konto!',
			]
		],
		'group' => [
			'rules' => 'required|in_list[1,2,3]',
			'errors' => [
				'required' => 'Musisz wybrać grupę!',
				'in_list' => 'Musisz wybrać grupę!'
			]
		],
	];

	public $updateUser = [
		'firstName' => [
			'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[60]',
			'errors' => [
				'required' => 'Nie podano imienia użytkownika!',
				'alpha_numeric_space' => 'Imię zawiera niedozwolone znaki!',
				'min_length' => 'Imię musi się składać z conajmniej 3 znaków!',
				'max_length' => 'Imię nie może składać się z więcej niż 60 znaków!'
			]
		],
		'lastName' => [
			'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[60]',
			'errors' => [
				'required' => 'Nie podano nazwiska użytkownika!',
				'alpha_numeric_space' => 'Nazwisko zawiera niedozwolone znaki!',
				'min_length' => 'Nazwisko musi się składać z conajmniej 3 znaków!',
				'max_length' => 'Nazwisko nie może się składać z więcej niż 60 znaków'
			]
		],
		'email' => [
			'rules' => 'valid_email|is_unique[users.email,id,{id}]',
			'errors' => [
				'valid_email' => 'Podano niepoprawny adres e-mail!',
				'is_unique' => 'Podany adres e-mail jest już używany przez inne konto!',
			]
		],
		'group' => [
			'rules' => 'required|in_list[1,2,3]',
			'errors' => [
				'required' => 'Musisz wybrać grupę!',
				'in_list' => 'Musisz wybrać grupę!'
			]
		],
	];

	public $deleteUser = [
		'password' => [
			'rules' => 'required',
			'errors' => [
				'required' => 'Musisz podać swoje hasło w celu potwierdzenia tej czynności!',
			],
		],
		'password-confirm' => [
			'rules' => 'required|matches[password]',
			'errors' => [
				'required' => 'Musisz potwierdzić swoje hasło!',
				'matches' => 'Hasła się nie zgadzają!',
			],
		],
	];

	public $updateSettings = [
		'baseURL' => [
			'rules' => 'regex_match[/^(https|http):\/\/[a-zA-z0-9+-.,_]+(\/|)$/]',
			'errors' => [
				'regex_match' => 'Adres strony musi być poprawnym adresem URL!'
			]
		],
		'siteName' => [
			'rules' => 'required',
			'errors' => [
				'required' => 'Musisz podać nazwę strony!',
			]
		],
		'siteLogo' => [
			'rules' => 'mime_in[siteLogo,image/jpeg,image/jpg,image/png]|max_size[siteLogo,2072]|max_dims[siteLogo,2000,2000]',
			'errors' => [
				'mime_in' => 'Logo musi być plikiem w formacie PNG!',
				'max_size' => 'Logo nie może być większe niż 2MB!',
				'max_dims' => 'Logo nie może mieć większych rozmiarów niż 2000x2000!'
			],
		],
	];

	public $uploadFile = [
		'myImage' => [
			'rules' => 'mime_in[myImage,image/jpeg,image/jpg,image/png]|max_size[myImage,2000]|max_dims[myImage,2444,2444]',
			'errors' => [
				'max_size' => 'max_size',
				'max_dims' => 'max_dims',
				'mime_in' => 'mime_in'
			],
		],
	];

	public $createNews = [
		'title' => [
			'rules' => 'required|min_length[5]|max_length[196]|is_unique[news.title]',
			'errors' => [
				'required' => 'Musisz podać tytuł dla ogłoszenia!',
				'min_length' => 'Tytuł musi się składać z conajmniej 5 znaków',
				'max_length' => 'Tytuł nie może być dłuższy niż 196 znaków',
				'is_unique' => 'Post z takim tytułem już istnieje'
			]
		],
		'postBody' => [
			'rules' => 'required|min_length[50]',
			'errors' => [
				'required' => 'Ogłoszenie nie może być puste!',
				'min_length' => 'Ogłoszenie musi się składać z conajmniej 50 znaków!',
			]
		],
	];

	public $editNews = [
		'title' => [
			'rules' => 'required|min_length[5]|max_length[196]|is_unique[news.title,id,{postId}]',
			'errors' => [
				'required' => 'Musisz podać tytuł dla ogłoszenia!',
				'min_length' => 'Tytuł musi się składać z conajmniej 5 znaków',
				'max_length' => 'Tytuł nie może być dłuższy niż 196 znaków',
				'is_unique' => 'Post z takim tytułem już istnieje'
			]
		],
		'postBody' => [
			'rules' => 'required|min_length[50]',
			'errors' => [
				'required' => 'Ogłoszenie nie może być puste!',
				'min_length' => 'Ogłoszenie musi się składać z conajmniej 50 znaków!',
			]
		],
	];

	public $deleteNews = [
		'password' => [
			'rules' => 'required',
			'errors' => [
				'required' => 'Musisz podać swoje hasło w celu potwierdzenia tej czynności!',
			],
		],
		'password-confirm' => [
			'rules' => 'required|matches[password]',
			'errors' => [
				'required' => 'Musisz potwierdzić swoje hasło!',
				'matches' => 'Hasła się nie zgadzają!',
			],
		],
	];

	public $userLogin = [
		'email' => [
			'rules' => 'required|valid_email',
			'errors' => [
				'required' => 'Musisz podać adres e-mail!',
				'valid_email' => 'Musisz podać poprawny adres e-mail!'
			],
		],
		'password' => [
			'rules' => 'required',
			'errors' => [
				'required' => 'Musisz podać hasło!'
			]
		]
	];
}