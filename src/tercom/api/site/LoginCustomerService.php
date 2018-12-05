<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;

/**
 * @author Andrew
 */
class LoginCustomerService extends DefaultSiteService
{
	/**
	 *
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionLogin(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$email = $post->getString('email');
		$password = $post->getString('password');
		$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $post->getString('userAgent');

		$loginCustomer = $this->getLoginCustomerControl()->newLoginCustomer($email, $password, $userAgent);
		$this->getLoginCustomerControl()->add($loginCustomer);

		$result = new ApiResultObject();
		$result->setObject($loginCustomer);
		$result->setMessage('acesso realizado com êxito');

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionVerify(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idLogin = $post->getInt('idLogin');
		$idCustomerEmployee = $post->getInt('idCustomerEmployee');
		$token = $post->getString('token');

		$loginCustomer = $this->getLoginCustomerControl()->get($idLogin, $idCustomerEmployee, $token);
		$this->getLoginCustomerControl()->keepAlive($loginCustomer);

		$result = new ApiResultObject();
		$result->setObject($loginCustomer);
		$result->setMessage('acesso verificado com êxito');

		return $result;
	}

	/**
	 *
	 * @ApiAnnotation({"method":"post"})
	 * @param ApiContent $content
	 * @return ApiResultObject
	 */
	public function actionLogout(ApiContent $content): ApiResultObject
	{
		$post = $content->getPost();
		$idLogin = $post->getInt('idLogin');
		$idCustomerEmployee = $post->getInt('idCustomerEmployee');
		$token = $post->getString('token');

		$loginCustomer = $this->getLoginCustomerControl()->get($idLogin, $idCustomerEmployee, $token);
		$this->getLoginCustomerControl()->logout($loginCustomer);

		$result = new ApiResultObject();
		$result->setObject($loginCustomer);
		$result->setMessage('acesso encerrado com êxito');

		return $result;
	}
}

