<?php

namespace Validator;

use System\Validators\AbstractValidator;

class SearchWordValidator extends AbstractValidator
{
	private $errors = [
		'Не заполнен текст.',
	];

	public $useFlashErrors = true;

	public function validate()
	{
		if (empty($_POST['text'])) {
			$this->stackErrors['text'] = $this->errors[0];
		}
	}
}