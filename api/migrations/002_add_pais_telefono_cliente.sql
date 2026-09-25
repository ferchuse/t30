-- México y +52 son los valores iniciales para clientes ya registrados.
ALTER TABLE clientes_frecuentes
    ADD COLUMN pais_cliente VARCHAR(100) NOT NULL DEFAULT 'México' AFTER apellidom_cliente,
    ADD COLUMN clave_pais_cliente VARCHAR(10) NOT NULL DEFAULT '+52' AFTER telefono_cliente,
    ADD UNIQUE KEY uq_clientes_frecuentes_telefono_pais (clave_pais_cliente, telefono_cliente);
