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
<VirtualHost medicapp:443>
	DocumentRoot "{PROJECT_DIR}"
	<Directory "{PROJECT_DIR}">
		Options Indexes FollowSymLinks Includes ExecCGI
		AllowOverride All
		Require all granted
	</Directory>
	ServerName tercom
	ServerAdmin admin@tercom
	ErrorLog "{PROJECT_DIR}/logs/error.log"
	TransferLog "{PROJECT_DIR}/logs/access.log"
	SSLEngine on
	SSLCertificateFile "conf/ssl.crt/server.crt"
	SSLCertificateKeyFile "conf/ssl.key/server.key"
	CustomLog "{PROJECT_DIR}/logs/ssl_request.log" \
			  "%t %h %{SSL_PROTOCOL}x %{SSL_CIPHER}x \"%r\" %b"
</VirtualHost>
```

## CRIANDO CERTIFICADO (CRT e KEY)

- https://www.youtube.com/watch?v=JfRy5coRcCE
