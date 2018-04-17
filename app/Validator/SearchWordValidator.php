<?php

namespace App\Validator;

use System\Validators\AbstractValidator;

class SearchWordValidator extends AbstractValidator
{
	private $errors = [
		'Не заполнен текст.',
	];

	public $isUseFlashErrors = true;

	public function validate(): void
	{
		if (empty($_POST['text'])) {
			$this->stackErrors['text'] = $this->errors[0];
		}
	}
}