# Tercom-PHP

## STATUS

- [ ] Core
- [ ] Fornecedores
- [ ] Fabricantes
- [ ] Produtos
- [ ] Serviços
- [ ] Clientes
- [ ] Funcionários
- [ ] Cotação
- [ ] Chat

## MÓDULOS
- Manter Fornecedores
- Manter Produto Categoria
- Manter Fabricante
- Manter Produto
- Manter Produto Valor
- Manter Serviço
- Manter Serviço Valores
- Manter Cliente
- Manter Cargos de Funcionários da TERCOM
- Manter Funcionários Cliente
- Login do Funcionário Cliente
- Manter Cargos de Funcionários da TERCOM
- Manter Funcionários TERCOM
- Login Funcionário TERCOM
- Solicitação de cotação
- Realizar cotação
- Autorização de pedido
- Aceite do Pedido
- Consultar Histórico
- Chat

## CONFIGURAÇÃO APACHE (RECOMENDADO)

```

<VirtualHost localhost:80>
	DocumentRoot "D:/Andrew/Workspace/PHP/Tercom/trunk"
	<Directory "D:/Andrew/Workspace/PHP/Tercom/trunk">
		Options Indexes FollowSymLinks Includes ExecCGI
		AllowOverride All
		Require all granted
	</Directory>
	ServerName tercom.localhost
	ServerAdmin admin@tercom
	ErrorLog "{PROJECT_DIR}logs/error.log"
	TransferLog "{PROJECT_DIR}logs/access.log"
</VirtualHost>

<VirtualHost localhost:443>
	DocumentRoot "D:/Andrew/Workspace/PHP/Tercom/trunk"
	<Directory "D:/Andrew/Workspace/PHP/Tercom/trunk">
		Options Indexes FollowSymLinks Includes ExecCGI
		AllowOverride All
		Require all granted
	</Directory>
	ServerName tercom.localhost
	ServerAdmin admin@tercom
	ErrorLog "{PROJECT_DIR}logs/error.log"
	TransferLog "{PROJECT_DIR}logs/access.log"
	SSLEngine on
	SSLCertificateFile "conf/ssl.crt/localhost.crt"
	SSLCertificateKeyFile "conf/ssl.key/localhost.key"
	CustomLog "{PROJECT_DIR}logs/ssl_request.log" \
			  "%t %h %{SSL_PROTOCOL}x %{SSL_CIPHER}x \"%r\" %b"
</VirtualHost>
```

## CRIANDO CERTIFICADO (CRT e KEY)

- https://www.youtube.com/watch?v=JfRy5coRcCE
