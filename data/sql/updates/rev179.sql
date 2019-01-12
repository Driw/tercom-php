
-- Ajuste de Constraints

ALTER TABLE order_item_products ADD CONSTRAINT order_item_products_request_fk FOREIGN KEY (idOrderRequest) REFERENCES order_requests(id);

-- Tabelas Renomeadas

ALTER TABLE quoted_order_product RENAME quoted_order_products
