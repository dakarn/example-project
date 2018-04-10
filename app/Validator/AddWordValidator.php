<?php

namespace Validator;

use System\Validators\AbstractValidator;

class AddWordValidator extends AbstractValidator
{
	private $errors = [
		'Не заполнен текст.',
		'Неверный запрос.',
	];

	public $useFlashErrors = true;

	public function validate(): bool
	{
		if (!$this->isPost()) {
			$this->stackErrors['query'] = $this->errors[1];
		}

		if (empty($_POST['text'])) {
			$this->stackErrors['text'] = $this->errors[0];
		}

		if (!empty($this->stackErrors)) {
			return false;
		}

		return true;
	}
}