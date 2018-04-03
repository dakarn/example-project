<?php

namespace Validator;

use System\Validators\AbstractValidator;

class AddWordValidator extends AbstractValidator
{
	private $errors = [
		'Не заполнен текст.',
	];

	public function validate(): bool
	{
		if (empty($_POST['text'])) {
			$this->stackErrors['text'] = $this->errors[0];
		}

		if (!empty($this->stackErrors)) {
			return false;
		}

		return true;
	}
}