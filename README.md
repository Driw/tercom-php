# Tercom-PHP

## MÓDULOS
### Primeira Iteração
- [x] Manter Fornecedores
- [x] Manter Contatos de Fornecedor
- [x] Manter Fabricante
- [x] Manter Unidade de Produto
- [x] Manter Categoria de Produto
- [x] Manter Produto
- [x] Manter Produto Valor
- [x] Manter Serviço
- [x] Manter Serviço Valor
### Segunda Iteração
- [x] Manter Cliente
- [x] Manter Perfil de Cliente
- [x] Manter Funcionário de Cliente
- [x] Login do Funcionário Cliente
- [x] Manter Perfil TERCOM
- [x] Manter Funcionário TERCOM
- [x] Login Funcionário TERCOM
### Terceira Iteração
- [ ] Solicitação de Cotação
- [ ] Manter e Realizar Cotação
- [ ] Autorização de Pedido
- [ ] Aceite do Pedido
- [ ] Consultar Histórico
### Extra
- [ ] Chat

## CONFIGURAÇÃO APACHE (RECOMENDADO)

```
<VirtualHost *:80>
	DocumentRoot "{DIRETORIO_PUBLIC_HTML}"
	<Directory "{DIRETORIO_PUBLIC_HTML}">
		Options Indexes FollowSymLinks Includes ExecCGI
		AllowOverride All
		Require all granted
	</Directory>
	ServerName tercom.localhost
	ServerAdmin admin@tercom
	ErrorLog "{DIRETORIO_LOGS}error.log"
	TransferLog "{DIRETORIO_LOGS}access.log"
</VirtualHost>

<VirtualHost *:443>
	DocumentRoot "{DIRETORIO_PUBLIC_HTML}"
	<Directory "{DIRETORIO_PUBLIC_HTML}">
		Options Indexes FollowSymLinks Includes ExecCGI
		AllowOverride All
		Require all granted
	</Directory>
	ServerName tercom.localhost
	ServerAdmin admin@tercom
	ErrorLog "{DIRETORIO_LOGS}error.log"
	TransferLog "{DIRETORIO_LOGS}access.log"
	SSLEngine on
	SSLCertificateFile "conf/ssl.crt/server.crt"
	SSLCertificateKeyFile "conf/ssl.key/server.key"
	CustomLog "{DIRETORIO_LOGS}ssl_request.log" \
			  "%t %h %{SSL_PROTOCOL}x %{SSL_CIPHER}x \"%r\" %b"
</VirtualHost>
```

## CONFIGURAÇÃO ARQUIVO HOSTS

Apesar do subdominio local funcionar os webservices não funcional por curl, portanto alterar o arquivo hosts:

```
127.0.0.1 tercom.localhost
```

## CRIANDO CERTIFICADO (CRT e KEY)

Necessário apenas criar um certificado para localhost, já que tercom.localhost é um subdominio seu.

- https://www.youtube.com/watch?v=JfRy5coRcCE
