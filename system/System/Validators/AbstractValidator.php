<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.03.2018
 * Time: 15:27
 */

namespace System\Validators;

use Helper\Cookie;
use Helper\CSRFToken;
use Helper\FlashText;
use Http\Request\ServerRequest;

abstract class AbstractValidator implements AbstractValidatorInterface
{
	/**
	 * @var array
	 */
	protected $stackErrors = [];

	/**
	 * @var bool
	 */
	public $isUseFlashErrors = false;

	/**
	 * @var string
	 */
	private $post = 'POST';

	/**
	 * @var string
	 */
	private $get  = 'GET';

	/**
	 * @return array
	 */
	public function getErrorsApi(): array
	{
		$errors = [
			'errors' => []
		];

		foreach ($this->stackErrors as $errorItem => $errorText) {
			$errors['errors'][] = [
				$errorItem => $errorText
			];
		}

		return $errors;
	}

	/**
	 * @return AbstractValidator
	 */
	public function setFlashErrors(): self
	{
		if (!$this->isUseFlashErrors) {
			return $this;
		}

		foreach ($this->stackErrors as $errorText) {
			FlashText::add('danger', $errorText);
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getErrors(): array
	{
		return $this->stackErrors;
	}

	/**
	 * @param string $field
	 * @return string
	 */
	public function getError(string $field): string
	{
		return $this->stackErrors[$field] ?? '';
	}

	/**
	 * @return bool
	 */
	public function isCSRFToken(): bool
	{
		return CSRFToken::create()
			->setValidationData(
				Cookie::create()->get('CSRFToken'),
				ServerRequest::create()->takePost('CSRFToken'))
			->isValid();
	}

	/**
	 * @return bool
	 */
	public function isPost(): bool
	{
		return ServerRequest::create()->getMethod() === $this->post;
	}

	/**
	 * @return bool
	 */
	public function isGet(): bool
	{
		return ServerRequest::create()->getMethod() === $this->get;
	}

	/**
	 * @return bool
	 */
	public function isValid(): bool
	{
		$this->validate();
		$this->setFlashErrors();

		return empty($this->stackErrors);
	}

	/**
	 * @return mixed
	 */
	abstract public function validate();
}