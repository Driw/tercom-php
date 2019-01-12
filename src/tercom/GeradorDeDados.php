<?php

namespace tercom;

use dProject\Primitive\Session;
use dProject\Primitive\StringUtil;
use tercom\entities\Phone;

IncludeThirdParty('simple_html_dom');

class GeradorDeDados
{
	public static function callWebService($webservice, $parameters, $emptyAsNull = false)
	{
		if ($emptyAsNull)
		{
			foreach ($parameters as $key => $value)
				if (is_string($value) && StringUtil::isEmpty($value))
					unset($parameters[$key]);
		}

		$session = Session::getInstance();
		$session->start();

		if ($session->isSetted(SessionVar::LOGIN_ID) && $session->isSetted(SessionVar::LOGIN_TOKEN))
		{
			if ($session->isSetted(SessionVar::LOGIN_TERCOM_ID))
			{
				$parameters[SessionVar::LOGIN_TERCOM_ID] = $session->getInt(SessionVar::LOGIN_TERCOM_ID);
				$parameters[SessionVar::LOGIN_ID] = $session->getInt(SessionVar::LOGIN_ID);
				$parameters[SessionVar::LOGIN_TOKEN] = $session->getString(SessionVar::LOGIN_TOKEN);
			}

			else if ($session->isSetted(SessionVar::LOGIN_CUSTOMER_ID))
			{
				$parameters[SessionVar::LOGIN_CUSTOMER_ID] = $session->getInt(SessionVar::LOGIN_CUSTOMER_ID);
				$parameters[SessionVar::LOGIN_ID] = $session->getInt(SessionVar::LOGIN_ID);
				$parameters[SessionVar::LOGIN_TOKEN] = $session->getString(SessionVar::LOGIN_TOKEN);
			}
		}

		$post = http_build_query($parameters);
		$endpoint = sprintf('http://%s/api/site', $_SERVER['SERVER_NAME']);
		$url = "$endpoint/$webservice";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);;

		$response = curl_exec($curl);
		$error = curl_error($curl);

		curl_close($curl);

		if (!empty($error))
			return [
				'endpoint' => $endpoint,
				'websercice' => $webservice,
				'fullUrl' => $url,
				'post' => $parameters,
				'curl_error' => $error,
			];

		$jsonResponse = json_decode($response, true);

		if (json_last_error() == JSON_ERROR_NONE)
			return [
				'endpoint' => $endpoint,
				'websercice' => $webservice,
				'fullUrl' => $url,
				'post' => $parameters,
				'response' => $jsonResponse,
			];

		return [
			'endpoint' => $endpoint,
			'websercice' => $webservice,
			'fullUrl' => $url,
			'post' => $parameters,
			'response' => $response,
		];
	}

	private static function call($acao, $parameters)
	{
		$parameters['acao'] = $acao;
		$fields = http_build_query($parameters);
		$ch = curl_init('https://www.4devs.com.br/ferramentas_online.php');

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_REFERER, 'https://www.4devs.com.br');

		return curl_exec($ch);
	}

	private static function getAsText($acao, $parameters)
	{
		return self::call($acao, $parameters);
	}

	private static function getAsInnerText($acao, $parameters, $selector)
	{
		$htmlResult = self::call($acao, $parameters);
		$html = str_get_html($htmlResult);
		$values = array();

		foreach ($html->find($selector) as $element)
		{
			if (!empty($element->getAttribute('value')))
				$values[$element->getAttribute('value')] = $element->innertext();
			else
				array_push($values, strip_tags($element->innertext()));
		}

		return $values;
	}

	private static function getAsArray($acao, $parameters, $idElements, $input = false)
	{
		do {
			$htmlResult = self::call($acao, $parameters); // Por algum motivo as vezes está vindo em branco
		} while (empty($htmlResult));

		$html = str_get_html($htmlResult);
		$values = array();

		foreach ($idElements as $idElement)
		{
			if ($input)
				$values[$idElement] = strip_tags($html->getElementById($idElement)->value);
			else
				$values[$idElement] = strip_tags($html->getElementById($idElement)->innertext());
		}

		return $values;
	}

	private static function getAsJSON($acao, $parameters)
	{
		$htmlResult = self::call($acao, $parameters);

		return json_decode($htmlResult, true);
	}

	public static function randArray($array)
	{
		return $array[array_rand($array, 1)];
	}

	public static function randKeyArray($array)
	{
		return array_rand($array);
	}

	public static function genContaBancaria($uf = null, $banco = null)
	{
		if (!isset($uf)) $uf = self::randArray(self::getUFs());
		if (!isset($banco)) $banco = self::randArray(self::getBancos());

		$parameters = array('estado' => $uf, 'banco' => $banco);
		$idElements = array('banco', 'cidade', 'estado', 'agencia', 'conta_corrente');

		return self::getAsArray('gerar_conta_bancaria', $parameters, $idElements);
	}

	public static function genCartaoDeCredito($comPontuacao = false, $bandeira = null)
	{
		if (!isset($bandeira)) $bandeira = self::randArray(self::getBandeiras());

		$parameters = array('pontuacao' => $comPontuacao ? 'S' : 'N', 'bandeira' => $bandeira);
		$idElements = array('cartao_numero', 'data_validade', 'codigo_seguranca');
		$cartaoDeCredito = self::getAsArray('gerar_cc', $parameters, $idElements);
		$cartaoDeCredito['bandeira'] = $bandeira;
		$cartaoDeCredito['mes'] = substr($cartaoDeCredito['data_validade'], 3, 2);
		$cartaoDeCredito['ano'] = substr($cartaoDeCredito['data_validade'], 6, 2);

		return $cartaoDeCredito;
	}

	public static function genPessoa($comPontuacao = false, $sexo = null, $idade = null, $estado = null, $cidade = null)
	{
		if (!isset($idade)) $idade = rand(18, 80);
		if (!isset($sexo)) $sexo = self::randArray(self::getSexos());
		if (!isset($estado)) $estado = self::randArray(self::getUFs());
		if (!isset($cidade)) $cidade = self::randKeyArray(self::getCidades($estado));

		$parameters = array(
			'pontuacao' => $comPontuacao ? 'S' : 'N',
			'sexo' => isset($sexo) ? $sexo : '',
			'idade' => isset($idade) ? $idade : '',
			'cep_estado' => $estado,
			'cep_cidade' => $cidade,
		);
		$pessoa = self::getAsJSON('gerar_pessoa', $parameters);
		if (($offset = strpos($pessoa['email'], '__')) !== false) $pessoa['email'] = substr($pessoa['email'], $offset + 1);
		if (($offset = strpos($pessoa['email'], '..')) !== false) $pessoa['email'] = substr($pessoa['email'], $offset + 1);
		$pessoa['sexo'] = !strcmp($sexo, 'H') ? 'M' : !strcmp($sexo, 'M') ? 'F' : '';
		$pessoa['idade'] = $idade;
		$pessoa['email'] = self::genEmail($pessoa['nome'], substr($pessoa['email'], strpos($pessoa['email'], '@')));
		$pessoa['telefone_fixo'] = sprintf('%d-%04d', rand(1000, 5000), rand(0, 9999));
		$pessoa['telefone_fixo_ddd'] = self::randArray(self::getDDDs($pessoa['estado']));
		$pessoa['celular'] = sprintf('9%d-%04d', rand(5000, 9999), rand(0, 9999));
		$pessoa['celular_ddd'] = self::randArray(self::getDDDs($pessoa['estado']));
		$endereco = explode(' ', $pessoa['endereco']);
		$pessoa['endereco_complemento'] = '';
		if (rand(0, 100) <= 20)
		$pessoa['endereco_complemento'] = sprintf('Apto %d%d', rand(1, 24), rand(1, 6));
		$pessoa['endereco_numero'] = intval(array_pop($endereco));
		$pessoa['endereco_rua'] = implode(' ', $endereco);

		return $pessoa;
	}

	public static function genEmpresa($comPontuacao = false, $estado = null, $idade = null)
	{
		if (!isset($estado)) $estado = self::randArray(self::getUFs());
		if (!isset($idade)) $idade = rand(1, 30);

		$parameters = array('pontuacao' => $comPontuacao ? 'S' : 'N', 'estado' => $estado, 'idade' => $idade);
		$idElements = array(
			'nome', 'cnpj', 'ie', 'data_abertura', 'site', 'email',
			'cep', 'endereco', 'numero', 'bairro', 'cidade', 'estado', 'telefone_fixo', 'celular'
		);
		$empresa = self::getAsArray('gerar_empresa', $parameters, $idElements, true);
		$empresa['nomeFantasia'] = implode(' ', array_slice(explode(' ', $empresa['nome']), 0, -1));
		$empresa['idade'] = $idade;
		$empresa['telefone_fixo_ddd'] = self::randArray(self::getDDDs($empresa['estado']));
		$empresa['celular_ddd'] = self::randArray(self::getDDDs($empresa['estado']));
		$empresa['complemento'] = (rand(0, 100) <= 10) ? sprintf('Cj %d%d', rand(1, 24), rand(1, 8)) : '';

		return $empresa;
	}

	public static function genTelefone($estado = null)
	{
		if (!isset($estado)) $estado = self::randArray(self::getUFs());

		$telefone = [
			'ddd' => self::randArray(self::getDDDs($estado)),
			'numero' => sprintf('%d-%04d', rand(1000, 5000), rand(0, 9999)),
			'tipo' => array_rand(Phone::getTypes()),
		];

		return $telefone;
	}

	public static function genCelular($estado = null)
	{
		if (!isset($estado)) $estado = self::randArray(self::getUFs());

		$telefone = [
			'ddd' => self::randArray(self::getDDDs($estado)),
			'numero' => sprintf('9%d-%04d', rand(5000, 9999), rand(0, 9999)),
		];

		return $telefone;
	}

	public static function genRG($comPontuacao = false)
	{
		$parameters = array('pontuacao' => $comPontuacao ? 'S' : 'N');

		return self::getAsText('gerar_rg', $parameters);
	}

	public static function genCPF($comPontuacao = false, $estado = null)
	{
		if (!isset($estado)) $estado = self::randArray(self::getBancos());

		$parameters = array('pontuacao' => $comPontuacao ? 'S' : 'N', 'cpf_estado' => $estado);

		return self::getAsText('gerar_cpf', $parameters);
	}

	public static function genCNPJ($comPontuacao = false)
	{
		$parameters = array('pontuacao' => $comPontuacao ? 'S' : 'N');

		return self::getAsText('gerar_cnpj', $parameters);
	}

	public static function genCEP($numero = true, $pontuacao = false, $estado = null, $cidade = null)
	{
		if (!isset($estado)) $estado = self::randArray($estado);
		if (!isset($cidade)) $cidade = self::randArray(self::getCidades($estado));

		$parameters = array('pontuacao' => $pontuacao ? 'S' : 'N', 'cep_estado' => $estado, 'cep_cidade' => $cidade);
		$idElements = array('cep', 'endereco', 'bairro', 'cidade', 'estado');
		$dados = self::getAsArray('gerar_cep', $parameters, $idElements, true);

		if ($numero)
		{
			$dados['numero'] = rand(1, 1000);
			$dados['complemento'] = rand(0, 100) <= 80 ? '' : sprintf('Apto %d%d', rand(1, 20), rand(1, 6));
		}

		return $dados;
	}

	public static function genCargo()
	{
		return self::randArray(self::getCargos());
	}

	public static function genEmail($nome, $dominio)
	{
		$nome = Functions::stripAccents($nome);
		$nomes = explode(' ', strtolower($nome));
		$separador = self::randArray(['', '-', '_', '.']);
		$sobrenome = rand(0, 1) === 1 ? $nomes[1] : end($nomes);
		$nome = $nomes[0];

		return "$nome$separador$sobrenome$dominio";
	}

	public static function genPassword($length = 6): string
	{
		$pass = [];
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$alphaLength = strlen($alphabet) - 1;

		do {

			for ($i = 0; $i < $length; $i++)
				$pass[] = $alphabet[rand(0, $alphaLength)];

		} while (preg_match(PATTERN_PASSWORD, ($password = implode($pass))) === 0);

		return $password;
	}

	public static function getCidades($estado)
	{
		$parameters = array('cep_estado' => $estado);
		$cidades = self::getAsInnerText('carregar_cidades', $parameters, 'option');
		unset($cidades['0']);

		return $cidades;
	}

	public static function getUFs()
	{
		return array(
			'AC', 'AL', 'AM', 'AP', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
			'MG', 'MS', 'MT', 'PA', 'PB', 'PE', 'PI', 'PR', 'RJ', 'RN',
			'RS', 'RO', 'RR', 'SC', 'SE', 'SP', 'TO'
		);
	}

	public static function getBancos()
	{
		return array(002, 121, 005, 120, 151);
	}

	public static function getBandeiras()
	{
		return array('visa16', 'master', 'amex', 'discover', 'diners', 'jcb', 'elo');
	}

	public static function getSexos()
	{
		return array('H', 'M');
	}

	public static function getAllCodigoBancos()
	{
		return array(
			'001' => 'BANCO DO BRASIL S.A.',
			'003' => 'BANCO DA AMAZONIA S.A.',
			'004' => 'BANCO DO NORDESTE DO BRASIL S.A.',
			'014' => 'NATIXIS BRASIL S.A. BANCO MÚLTIPLO',
			'017' => 'BNY MELLON BANCO S.A.',
			'018' => 'BANCO TRICURY S.A.',
			'019' => 'BANCO AZTECA DO BRASIL S.A.',
			'021' => 'BANESTES S.A. BANCO DO ESTADO DO ESPIRITO SANTO',
			'024' => 'BANCO BANDEPE S.A.',
			'025' => 'BANCO ALFA S.A.',
			'029' => 'BANCO ITAÚ BMG CONSIGNADO S.A.',
			'033' => 'BANCO SANTANDER (BRASIL) S.A.',
			'036' => 'BANCO BRADESCO BBI S.A.',
			'037' => 'BANCO DO ESTADO DO PARÁ S.A.',
			'040' => 'BANCO CARGILL S.A.',
			'041' => 'BANCO DO ESTADO DO RIO GRANDE DO SUL S.A.',
			'047' => 'BANCO DO ESTADO DE SERGIPE S.A.',
			'062' => 'HIPERCARD BANCO MÚLTIPLO S.A.',
			'063' => 'BANCO BRADESCARD S.A.',
			'064' => 'GOLDMAN SACHS DO BRASIL BANCO MULTIPLO S.A.',
			'066' => 'BANCO MORGAN STANLEY S.A.',
			'069' => 'BPN BRASIL BANCO MÚLTIPLO S.A.',
			'070' => 'BRB - BANCO DE BRASILIA S.A.',
			'074' => 'BANCO J. SAFRA S.A.',
			'075' => 'BANCO ABN AMRO S.A.',
			'076' => 'BANCO KDB DO BRASIL S.A.',
			'077' => 'BANCO INTERMEDIUM S.A.',
			'079' => 'BANCO ORIGINAL DO AGRONEGÓCIO S.A.',
			'081' => 'BBN BANCO BRASILEIRO DE NEGÓCIOS S.A.',
			'082' => 'BANCO TOPÁZIO S.A.',
			'083' => 'BANCO DA CHINA BRASIL S.A.',
			'088' => 'BANCO RANDON S.A.',
			'094' => 'BANCO PETRA S.A.',
			'095' => 'BANCO CONFIDENCE DE CÂMBIO S.A.',
			'096' => 'BANCO BM&FBOVESPA DE SERVIÇOS DE LIQUIDAÇÃO E CUSTÓDIA S.A.',
			'104' => 'CAIXA ECONOMICA FEDERAL',
			'107' => 'BANCO BBM S/A',
			'119' => 'BANCO WESTERN UNION DO BRASIL S.A.',
			'120' => 'BANCO RODOBENS S.A.',
			'121' => 'BANCO GERADOR S.A.',
			'122' => 'BANCO BRADESCO BERJ S.A.',
			'124' => 'BANCO WOORI BANK DO BRASIL S.A.',
			'125' => 'BRASIL PLURAL S.A. BANCO MÚLTIPLO',
			'132' => 'ICBC DO BRASIL BANCO MÚLTIPLO S.A.',
			'184' => 'BANCO ITAÚ BBA S.A.',
			'204' => 'BANCO BRADESCO CARTÕES S.A.',
			'208' => 'BANCO BTG PACTUAL S.A.',
			'212' => 'BANCO ORIGINAL S.A.',
			'213' => 'BANCO ARBI S.A.',
			'217' => 'BANCO JOHN DEERE S.A.',
			'218' => 'BANCO BONSUCESSO S.A.',
			'222' => 'BANCO CRÉDIT AGRICOLE BRASIL S.A.',
			'224' => 'BANCO FIBRA S.A.',
			'233' => 'BANCO CIFRA S.A.',
			'237' => 'BANCO BRADESCO S.A.',
			'241' => 'BANCO CLASSICO S.A.',
			'243' => 'BANCO MÁXIMA S.A.',
			'246' => 'BANCO ABC BRASIL S.A.',
			'248' => 'BANCO BOAVISTA INTERATLANTICO S.A.',
			'249' => 'BANCO INVESTCRED UNIBANCO S.A.',
			'250' => 'BCV - BANCO DE CRÉDITO E VAREJO S/A',
			'254' => 'PARANÁ BANCO S.A.',
			'263' => 'BANCO CACIQUE S.A.',
			'265' => 'BANCO FATOR S.A.',
			'266' => 'BANCO CEDULA S.A.',
			'300' => 'BANCO DE LA NACION ARGENTINA',
			'318' => 'BANCO BMG S.A.',
			'320' => 'BANCO INDUSTRIAL E COMERCIAL S.A.',
			'341' => 'ITAÚ UNIBANCO S.A.',
			'366' => 'BANCO SOCIETE GENERALE BRASIL S.A.',
			'370' => 'BANCO MIZUHO DO BRASIL S.A.',
			'376' => 'BANCO J.P. MORGAN S.A.',
			'389' => 'BANCO MERCANTIL DO BRASIL S.A.',
			'394' => 'BANCO BRADESCO FINANCIAMENTOS S.A.',
			'399' => 'HSBC BANK BRASIL S.A. - BANCO MULTIPLO',
			'412' => 'BANCO CAPITAL S.A.',
			'422' => 'BANCO SAFRA S.A.',
			'456' => 'BANCO DE TOKYO-MITSUBISHI UFJ BRASIL S.A.',
			'464' => 'BANCO SUMITOMO MITSUI BRASILEIRO S.A.',
			'473' => 'BANCO CAIXA GERAL - BRASIL S.A.',
			'477' => 'CITIBANK N.A.',
			'479' => 'BANCO ITAUBANK S.A.',
			'487' => 'DEUTSCHE BANK S.A. - BANCO ALEMAO',
			'488' => 'JPMORGAN CHASE BANK, NATIONAL ASSOCIATION',
			'492' => 'ING BANK N.V.',
			'494' => 'BANCO DE LA REPUBLICA ORIENTAL DEL URUGUAY',
			'495' => 'BANCO DE LA PROVINCIA DE BUENOS AIRES',
			'505' => 'BANCO CREDIT SUISSE (BRASIL) S.A.',
			'600' => 'BANCO LUSO BRASILEIRO S.A.',
			'604' => 'BANCO INDUSTRIAL DO BRASIL S.A.',
			'610' => 'BANCO VR S.A.',
			'611' => 'BANCO PAULISTA S.A.',
			'612' => 'BANCO GUANABARA S.A.',
			'613' => 'BANCO PECUNIA S.A.',
			'623' => 'BANCO PAN S.A.',
			'626' => 'BANCO FICSA S.A.',
			'630' => 'BANCO INTERCAP S.A.',
			'633' => 'BANCO RENDIMENTO S.A.',
			'634' => 'BANCO TRIANGULO S.A.',
			'637' => 'BANCO SOFISA S.A.',
			'641' => 'BANCO ALVORADA S.A.',
			'643' => 'BANCO PINE S.A.',
			'652' => 'ITAÚ UNIBANCO HOLDING S.A.',
			'653' => 'BANCO INDUSVAL S.A.',
			'654' => 'BANCO A.J. RENNER S.A.',
			'655' => 'BANCO VOTORANTIM S.A.',
			'707' => 'BANCO DAYCOVAL S.A.',
			'719' => 'BANIF - BANCO INTERNACIONAL DO FUNCHAL (BRASIL), S.A.',
			'735' => 'BANCO POTTENCIAL S.A.',
			'739' => 'BANCO CETELEM S.A.',
			'740' => 'BANCO BARCLAYS S.A.',
			'741' => 'BANCO RIBEIRAO PRETO S.A.',
			'743' => 'BANCO SEMEAR S.A.',
			'745' => 'BANCO CITIBANK S.A.',
			'746' => 'BANCO MODAL S.A.',
			'747' => 'BANCO RABOBANK INTERNATIONAL BRASIL S.A.',
			'748' => 'BANCO COOPERATIVO SICREDI S.A.',
			'751' => 'SCOTIABANK BRASIL S.A. BANCO MÚLTIPLO',
			'752' => 'BANCO BNP PARIBAS BRASIL S.A.',
			'753' => 'NOVO BANCO CONTINENTAL S.A. - BANCO MÚLTIPLO',
			'754' => 'BANCO SISTEMA S.A.',
			'755' => 'BANK OF AMERICA MERRILL LYNCH BANCO MÚLTIPLO S.A.',
			'756' => 'BANCO COOPERATIVO DO BRASIL S.A. - BANCOOB',
			'757' => 'BANCO KEB DO BRASIL S.A.',
			'M06' => 'BANCO DE LAGE LANDEN BRASIL S.A.',
			'M07' => 'BANCO GMAC S.A.',
			'M10' => 'BANCO MONEO S.A.',
			'M11' => 'BANCO IBM S.A.',
			'M12' => 'BANCO MAXINVEST S.A.',
			'M14' => 'BANCO VOLKSWAGEN S.A.',
			'M17' => 'BANCO OURINVEST S.A.',
			'M18' => 'BANCO FORD S.A.',
			'M19' => 'BANCO CNH INDUSTRIAL CAPITAL S.A.',
			'M20' => 'BANCO TOYOTA DO BRASIL S.A.',
			'M22' => 'BANCO HONDA S.A.',
			'M23' => 'BANCO VOLVO BRASIL S.A.',
			'M24' => 'BANCO PSA FINANCE BRASIL S.A.',
		);
	}

	public static function getDDDs($uf = null)
	{
		$ddds = [
			'AC' => [68],
			'AL' => [82],
			'AM' => [92, 97],
			'AP' => [96],
			'BA' => [71, 73, 74, 75, 77],
			'CE' => [85],
			'DF' => [61],
			'ES' => [27, 28],
			'GO' => [61, 62, 64],
			'MA' => [98,99],
			'MG' => [32, 32, 33, 34, 35, 37, 38],
			'MS' => [67],
			'MT' => [65, 66, 67],
			'PA' => [91, 93, 94],
			'PB' => [83],
			'PE' => [81, 87],
			'PI' => [86, 88, 89],
			'PR' => [41, 42, 43, 44, 45, 46],
			'RJ' => [21, 22, 24],
			'RN' => [84],
			'RS' => [51, 53, 54, 55],
			'RO' => [69],
			'RR' => [95],
			'SC' => [47, 48, 49],
			'SE' => [79],
			'SP' => [11, 12, 13, 14, 15, 16, 17, 18, 19],
			'TO' => [63],
		];

		return isset($ddds[$uf]) ? $ddds[$uf] : $ddds;
	}

	public static function getCargos()
	{
		return [
			'Presidente',
			'Diretor',
			'Gerente',
			'Coordenador',
			'Supervisor',
			'Analista',
			'Assistente',
			'Auxiliar',
			'Estagiário',
			'Aprendiz',
		];
	}
}

?>