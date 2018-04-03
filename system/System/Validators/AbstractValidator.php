<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.03.2018
 * Time: 15:27
 */

namespace System\Validators;

use Helper\CSRFToken;
use Helper\Request;

abstract class AbstractValidator implements AbstractValidatorInterface
{
	protected $stackErrors = [];

	private $post = 'POST';

	private $get  = 'GET';

	public function getErrors(): array
	{
		return $this->stackErrors;
	}

	public function getError(string $field): string
	{
		return $this->stackErrors[$field] ?? '';
	}

	public function isCSRFToken(): bool
	{
		return CSRFToken::create()
			->setValidationData(
				Request::create()->getCookie()->get('CSRFToken'),
				Request::create()->takePost('CSRFToken'))
			->isValid();
	}

	public function isPost(): bool
	{
		return Request::create()->getMethod() === $this->post;
	}

	public function isGet(): bool
	{
		return Request::create()->getMethod() === $this->get;
	}

	abstract public function validate(): bool;
}