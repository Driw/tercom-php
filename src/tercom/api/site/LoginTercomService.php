<?php

namespace tercom\api\site;

use dProject\restful\ApiContent;
use tercom\api\site\results\ApiResultObject;

/**
 * @author Andrew
 */
class LoginTercomService extends DefaultSiteService
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

		$loginTercom = $this->getLoginTercomControl()->newLoginTercom($email, $password, $userAgent);
		$this->getLoginTercomControl()->add($loginTercom);

		$result = new ApiResultObject();
		$result->setObject($loginTercom);
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
		$idTercomEmployee = $post->getInt('idTercomEmployee');
		$token = $post->getString('token');

		$loginTercom = $this->getLoginTercomControl()->get($idLogin, $idTercomEmployee, $token);
		$this->getLoginTercomControl()->keepAlive($loginTercom);

		$result = new ApiResultObject();
		$result->setObject($loginTercom);
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
		$idTercomEmployee = $post->getInt('idTercomEmployee');
		$token = $post->getString('token');

		$loginTercom = $this->getLoginTercomControl()->get($idLogin, $idTercomEmployee, $token);
		$this->getLoginTercomControl()->logout($loginTercom);

		$result = new ApiResultObject();
		$result->setObject($loginTercom);
		$result->setMessage('acesso encerrado com êxito');

		return $result;
	}
}

