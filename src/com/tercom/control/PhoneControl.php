<?php

namespace tercom\control;

use dProject\MySQL\MySQL;
use tercom\dao\PhoneDAO;
use tercom\entities\Phone;
use tercom\entities\lists\Phones;

/**
 * <h1>Controle de Telefone</h1>
 *
 * <p>Controles são utilizados para realizar a comunicação com o acesso direto no banco de dados com o restante do sistema.
 * Além de realizar esse intermédio, possui algumas das regras/validações para garantir a integridade final dos dados.
 * Por exemplo, dados do qual não podem ser nulos/brancos são aqui considerados antes de acessar o banco.</p>
 *
 * @see GenericControl
 * @author Andrew
 */

class PhoneControl extends GenericControl
{
	/**
	 * @var PhoneDAO acesso aos telefones no banco de dados.
	 */
	private $phoneDAO;

	/**
	 * Cria uma nova instância de um controle para telefones.
	 * @param MySQL $mysql conexão com o banco de dados com os telefones.
	 */

	function __construct(MySQL $mysql)
	{
		$this->phoneDAO = new PhoneDAO($mysql);
	}

	/**
	 * Procedimento interno que realiza a validação de um telefone conforme as regras do banco de dados.
	 * @param Phone $phone telefone do qual terá seus dados validados.
	 * @param bool $validarID é necessário possuir um código de identificação único.
	 * @throws ControlException ocorre se houver qualquer divergência conforme as regras do banco de dados.
	 */

	private function validate(Phone $phone, bool $validarID)
	{
		if ($validarID) {
			if ($phone->getID() === 0)
				throw new ControlException('telefone não identificado');
		} else {
			if ($phone->getID() !== 0)
				throw new ControlException('telefone já identificado');
		}

		if (empty($phone->getNumber()))
			throw new ControlException('número do telefone não definido');
	}

	/**
	 * Adiciona um novo telefone no sistema, realiza validação de dados.
	 * Não pode possuir um código de identificação único definido.
	 * Ao final o telefone terá seu código de identificação atualizado.
	 * @param Phone $phone telefone do qual será adicionado no sistema.
	 * @return bool true se conseguiu adicionar ou false caso contrário.
	 */

	public function addPhone(Phone $phone):bool
	{
		$this->validate($phone, false);

		return $this->phoneDAO->insert($phone);
	}

	/**
	 * Define os novos dados do telefone no sistema, realiza validação de dados.
	 * É necessário que o telefone já possua um código de identificação único.
	 * @param Phone $phone telefone do qual será definido no sistema.
	 * @return bool true se conseguir definir ou false caso contrário.
	 */

	public function setPhone(Phone $phone):bool
	{
		$this->validate($phone, true);

		return $this->phoneDAO->update($phone);
	}

	/**
	 * Obtém os dados de um telefone através do seu código de identificação único.
	 * @param int $phoneID código de identificação único do telefone à obter.
	 * @throws ControlException apenas se código de identificação for inválido.
	 * @return NULL|Phone aquisição do telefone conforme código ou nulo se não encontrado.
	 */

	public function getPhone(int $phoneID):Phone
	{
		if ($phoneID < 1)
			throw new ControlException('identificação do telefone inválida');

		return $this->phoneDAO->selectByID($phoneID);
	}

	/**
	 * Obtém dados de telefones através de uma lista de códigos de identificação úncio.
	 * Caso algum dos código de identificação não sejam encontrados não será informado.
	 * @param array $phonesID lista contendo o código d eidentificação dos telefones à obter.
	 * @throws ControlException apenas se algum dos códigos de identificação forem inválidos.
	 * @return array aquisição da lista contendo os telefones que foram obtidos.
	 */

	public function getPhones(array $phonesID):array
	{
		foreach ($phonesID as $phoneID)
			if ($phoneID < 1)
				throw new ControlException('identificação do telefone inválida');

		return $this->phoneDAO->selectByIDs($phonesID);
	}

	/**
	 * Carrega os dados do banco de dados de um telefone já existente conforme:
	 * @param Phone $phone referência do telefone, necessário ID especificado.
	 * @return bool true se conseguir carregar os dados ou false caso contrário.
	 */

	public function loadPhone(Phone $phone):bool
	{
		if ($phone->getID() === 0)
			return false;

		if (($dbphone = $this->getPhone($phone->getID())) === null)
			return false;

		$phone->fromArray($dbphone->toArray());

		return true;
	}

	/**
	 * Carrega os dados do banco de dados dos telefones informados por lista.
	 * @param Phones $phones lista contendo os telefones a serem carregados.
	 * @return bool true se ao menos um telefone for carregado ou false caso contrário.
	 */

	public function loadPhones(Phones $phones):bool
	{
		$loaded = false;

		foreach ($phones as $phone)
			if ($this->loadPhone($phone))
				$loaded = true;

		return $loaded;
	}

	/**
	 * Apaga os dados de um telefone existente no sistema, necessário código de identificação único.
	 * @param Phone $phone referência do telefone à ser apagado.
	 * @throws ControlException código de identificação inválido.
	 * @return bool true se conseguir apagar ou false caso não encontrado.
	 */

	public function removePhone(Phone $phone):bool
	{
		if ($phone->getID() < 1)
			throw new ControlException('identificação do telefone inválida');

		return $this->phoneDAO->delete($phone);
	}

	/**
	 * Apaga os dados de um ou mais telefones existentes no sistema, necessário código de identificação único.
	 * @param Phones $phones lista contendo o código de identificação dos telefones a serem apagados.
	 * @throws ControlException telefone com instância inválida.
	 * @return int quantidade de telefones que foram removidos do sistema.
	 */

	public function removePhones(Phones $phones):int
	{
		$phoneIDs = [];

		foreach ($phones as $phone)
			if ($phone->getID() > 0)
				array_push($phoneIDs, $phone->getID());

		return $this->phoneDAO->deletePhones($phoneIDs);
	}

	/**
	 * Procedimento que verifica a necessidade de adicionar ou atualizar os dados de um telefone.
	 * Caso o telefone já possua uma identificação irá atualizar suas informações,
	 * caso contrário irá adicionar as informações no banco e obter uma identificação.
	 * @param Phone $phone referência do telefone do qual será mantido.
	 * @return bool true se atualizado ou adicionado caso contrário false.
	 */

	public function keepPhone(Phone $phone):bool
	{
		if ($phone->getID() === 0 && !empty($phone->getNumber()))
			return $this->addPhone($phone);

		else if ($phone->getID() !== 0)
			return $this->setPhone($phone);

		return false;
	}

	/**
	 * Mantém os dados de diversos telefones especificados em uma lista de telefones.
	 * @param Phones $phones lista contendo todos os telefones a serem mantidos.
	 * @throws ControlException se algum objeto do vetor não for um telefone.
	 * @return bool true se um ou mais telefones foram adicionados e/ou atualizados.
	 */

	public function keepPhones(Phones $phones):bool
	{
		$keepPhone = false;

		foreach ($phones as $phone)
			if ($this->keepPhone($phone));
				$keepPhone = true;

		return $keepPhone;
	}
}

?>