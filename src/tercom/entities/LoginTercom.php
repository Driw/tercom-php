<?php

namespace tercom\entities;

/**
 * Acesso da TERCOM
 *
 * O acesso da TERCOM é o acesso de um funcionário da TERCOM seja ele qual for.
 * Possui todas as informações de um acesso e adiciona um funcionário da TERCOM no mesmo.
 *
 * @see Login
 * @see TercomEmployee
 *
 * @author Andrew
 */
class LoginTercom extends Login
{
	/**
	 * @var TercomEmployee funcionário da TERCOM que realizou o acesso.
	 */
	private $tercomEmployee;

	/**
	 * @return TercomEmployee aquisição do funcionário da TERCOM que realizou o acesso.
	 */
	public function getTercomEmployee(): TercomEmployee
	{
		return $this->tercomEmployee === null ? ($this->tercomEmployee = new TercomEmployee()) : $this->tercomEmployee;
	}

	/**
	 * @param TercomEmployee $tercomEmployee funcionário da TERCOM que realizou o acesso.
	 */
	public function setTercomEmployee($tercomEmployee)
	{
		$this->tercomEmployee = $tercomEmployee;
	}

	/**
	 * @return int aquisição do código de identificação único do funcionário da TERCOM
	 * que realizou o acesso ou zero se não informado.
	 */
	public function getTercomEmployeeId(): int
	{
		return $this->tercomEmployee === null ? 0 : $this->tercomEmployee->getId();
	}

	/**
	 * {@inheritDoc}
	 * @see \tercom\entities\Login::getAttributeTypes()
	 */
	public function getAttributeTypes()
	{
		$attributeTypes = parent::getAttributeTypes();
		$attributeTypes['tercomEmployee'] = TercomEmployee::class;

		return $attributeTypes;
	}
}

