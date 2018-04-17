<?php

namespace App\Validator;

use System\Validators\AbstractValidator;

class AddWordValidator extends AbstractValidator
{
	private $errors = [
		'Не заполнен текст.',
		'Неверный запрос.',
	];

	public $isUseFlashErrors = true;

	public function validate(): void
	{
		if (!$this->isPost()) {
			$this->stackErrors['query'] = $this->errors[1];
		}

		if (empty($_POST['text'])) {
			$this->stackErrors['text'] = $this->errors[0];
		}
	}
}