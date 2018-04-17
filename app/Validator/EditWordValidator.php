<?php

namespace App\Validator;

use System\Validators\AbstractValidator;

class EditWordValidator extends AbstractValidator
{
	private $errors = [
		'Не заполнен заголовок.',
		'Не заполнены "Ключевые слова".',
		'Данные для страницы не введены.',
	];

	public function validate(): void
	{
		$errors = [];

		if (empty($_POST['title'])) {
			$errors[] = $this->errors[0];
		}

		if (empty($_POST['keywords'])) {
			$errors[] = $this->errors[1];
		}

		if (empty($_POST['text'])) {
			$errors[] = $this->errors[2];
		}
	}
}