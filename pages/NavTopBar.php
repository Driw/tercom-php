<?php

use dProject\Primitive\StringUtil;
use dProject\Primitive\UrlFriendly;
use tercom\Core\System;
use tercom\bootstrap\navbar\NavbarItems;
use tercom\bootstrap\navbar\NavbarSeparator;

$navbar = System::getNavbar();

?>
	<!-- Inicio da Barra Superior -->
	<nav class='navbar navbar-expand-lg navbar-topbar'>
<?php
	echo "		<a class='navbar-brand' href='" .$navbar->getNavbarBrand()->getLink(). "'>" .$navbar->getNavbarBrand()->getName(). "</a>".PHP_EOL;
?>
		<button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarContent' aria-controls='navbarContent' aria-expanded='false'>
			<span class='sr-only'>Toggle navigation</span>
			<span class='icon-bar'></span>
			<span class='icon-bar'></span>
			<span class='icon-bar'></span>
		</button>
		<div class='collapse navbar-collapse' id='navbarContent'>
			<ul class='navbar-nav mx-auto'>
<?php

	foreacH ($navbar->getNavbarItems()->getItems() as $index => $navbarItem)
	{
		$active = StringUtil::startsWith(UrlFriendly::getBase(), $navbarItem->getLink());
		$activeClass = !$active ? '' : ' active';
		$activeContent = !$active ? '' : ' <span class="sr-only">(current)</span></a>';

		if (!($navbarItem instanceof NavbarItems))
		{
			echo "				<li class=\"nav-item nav-bordered$activeClass\">".PHP_EOL;
			echo "					<a class=\"nav-link\" href=\"" .$navbarItem->getLink(). "\">" .$navbarItem->getName(). "$activeContent</a>".PHP_EOL;
			echo "				</li>".PHP_EOL;
		}

		else
		{
			$id = strtolower($navbarItem->getName());
			$id = str_replace(' ', '-', $id);
			$id = "$id-$index";

			echo "				<li class='nav-item nav-bordered dropdown$activeClass'>".PHP_EOL;
			echo "					<a class='nav-link dropdown-toggle' href='#' id='$id' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>".PHP_EOL;
			echo "						" .$navbarItem->getName(). "$activeContent".PHP_EOL;
			echo "					</a>".PHP_EOL;
			echo "					<div class='dropdown-menu bg-dark text-light' aria-labelledby='$id'>".PHP_EOL;

			foreach ($navbarItem->getItems() as $dropdownItem)
			if ($dropdownItem instanceof NavbarSeparator)
			echo "						<div class='dropdown-divider'></div>".PHP_EOL;
			else
			echo "						<a class='dropdown-item' href='" .$dropdownItem->getLink(). "'>" .$dropdownItem->getName(). "</a>".PHP_EOL;
			echo "					</div>".PHP_EOL;
			echo "				</li>".PHP_EOL;
		}
	}

?>
			</ul>
			<ul class='nav navbar-nav navbar-right navbar-login'>
				<li class='nav-text'>Já possui uma conta?</li>
				<li class='nav-item dropdown'>
					<a class='nav-link dropdown-toggle' href='#' id='nav-login-dropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
						<b>Entrar</b> <span class='caret'></span>
					</a>
					<div class='dropdown-menu dropdown-menu-right' aria-labelledby='nav-login-dropdown'>
						<form method="post" action="/">
							<fieldset class='form-group'>
								Entrar via
								<div class='login-row social-buttons'>
									<a href='#' class='btn btn-fb'><i class='fa fa-facebook'></i> Facebook</a>
									<a href='#' class='btn btn-tw'><i class='fa fa-twitter'></i> Twitter</a>
								</div>
								ou
								<div class='login-row input-group'>
									<div class="input-group-prepend">
										<div class="input-group-text">E-mail</div>
									</div>
									<input type='email' class='form-control' name='email' required>
								</div>
								<div class='login-row input-group'>
									<div class="input-group-prepend">
										<div class="input-group-text">Senha</div>
									</div>
									<input type='password' class='form-control' name='senha' required>
								</div>
								<div class='login-row form-check text-center'>
									<div class='help-block'><a href=''>Esqueceu sua senha ?</a></div>
								</div>
								<div class='login-row text-center'>
									<button type='submit' class='btn btn-primary btn-block'>Entrar</button>
								</div>
								<div class='login-row checkbox'>
									<label><input type='checkbox'> Manter-me conectado</label>
								</div>
							</fieldset>
						</form>
						<div class="bottom text-center">
							Novo aqui? <a href="/account/new"><b>Junte-se</b></a>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</nav>
	<!-- Fim da Barra Superior -->

	<!-- Inicio do Modal - Recuperar Senha -->
	<div id='modalRecoveryPassword' class='modal fade' tabindex='-1' role='dialog' aria-hidden='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header'>
						<h1 class='text-center'>Recuperar Senha</h1>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
				</div>
				<div class='modal-body'>
					<div class='col-md-12'>
						<div class='panel panel-default'>
							<div class='panel-body'>
								<div class='text-center'>
									<p>Se você esqueceu a sua senha, você pode recuperá-la aqui.</p>
									<div class='panel-body'>
										<form method='post' action='recoveryPassword.php'>
											<fieldset>
												<div class='form-group'>
													<div class='input-group'>
														<div class='input-group-prepend'>
															<span class='input-group-text'>E-mail</span>
														</div>
														<input type='email' class='form-control form-control-sm' name='email' required>
													</div>
												</div>
												<input type='submit' class='btn btn-lg btn-primary btn-block' value='Recuperar Senha'>
											</fieldset>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class='modal-footer'>
					<div class='col-md-12'>
					<button class='btn' data-dismiss='modal' aria-hidden='true'>Cancelar</button>
				</div>	
				</div>
			</div>
		</div>
	</div>
	<!-- Fim do Modal - Recuperar Senha -->
