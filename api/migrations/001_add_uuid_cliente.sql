-- Identificador permanente para codificar el QR de la tarjeta de lealtad.
ALTER TABLE clientes_frecuentes
    ADD COLUMN uuid_cliente CHAR(36) NULL AFTER id_cliente_frecuente,
    ADD UNIQUE KEY uq_clientes_frecuentes_uuid (uuid_cliente);

UPDATE clientes_frecuentes
SET uuid_cliente = UUID()
WHERE uuid_cliente IS NULL OR uuid_cliente = '';

ALTER TABLE clientes_frecuentes
    MODIFY uuid_cliente CHAR(36) NOT NULL;
