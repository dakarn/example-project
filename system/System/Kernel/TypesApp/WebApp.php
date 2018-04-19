<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.04.2018
 * Time: 14:53
 */

namespace System\Kernel\TypesApp;

use App\AppKernel;
use Providers\StorageProviders;
use Http\Request\Request;
use Http\Response\Response;

final class WebApp extends AbstractApplication
{
	/**
	 * @var Response
	 */
	public $response;

	/**
	 * @param AppKernel $appKernel
	 * @return AbstractApplication
	 */
	public function setAppKernel(AppKernel $appKernel): AbstractApplication
	{
		parent::setAppKernel($appKernel);
		StorageProviders::add($appKernel->getProviders());

		return $this;
	}

	/**
	 * @return WebApp
	 */
	public function handle(): WebApp
	{
		$request = Request::create();
		$request->handle($this->appKernel, $request);

		return $this;
	}

	/**
	 * @var void
	 */
	public function run(): void
	{
		try {
			$this->handle();
		} catch(\Throwable $e) {
			$this->outputException($e);
		}
	}

	/**
	 * @var void
	 */
	public function outputResponse(): void
	{
		$this->response = Request::create()->resultHandle();

		$this->response->sendHeaders();
		$this->response->output();
	}
}