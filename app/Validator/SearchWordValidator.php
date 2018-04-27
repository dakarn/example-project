<?php

namespace App\Validator;

use System\Validators\AbstractValidator;

class SearchWordValidator extends AbstractValidator
{
	/**
	 * @var array
	 */
	private $errors = [
		'Не заполнен текст.',
	];

	/**
	 * @var bool
	 */
	public $isUseFlashErrors = true;

	/**
	 * @var bool
	 */
	public $usAutoValidCSRF = true;

	/**
	 * @return void
	 */
	public function validate(): void
	{
		if (!$this->isCSRFToken()) {
			$this->stackErrors['token'] = 'Отправлена невалидная форма.';
		}

		if (empty($_POST['text'])) {
			$this->stackErrors['text'] = $this->errors[0];
		}
	}
}