------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
    id  bigserial PRIMARY KEY
   ,nombre varchar(255) NOT NULL UNIQUE
   ,password varchar(255) NOT NULL
   ,email varchar(255) NOT NULL
   ,created_at timestamp(0) NOT NULL DEFAULT current_timestamp
   ,updated_at timestamp(0)

);

CREATE INDEX idx_usuarios_email ON usuarios(email);

DROP TABLE IF EXISTS envios CASCADE;

CREATE TABLE envios
(
    id bigserial PRIMARY KEY
   ,url varchar(255) NOT NULL
   ,titulo varchar(255) NOT NULL
   ,entradilla varchar(255) NOT NULL
   ,usuario_id bigint NOT NULL REFERENCES usuarios (id)
   ,created_at timestamp(0) NOT NULL DEFAULT current_timestamp
   ,updated_at timestamp(0)
);

--Crear indices

DROP TABLE IF EXISTS comentarios CASCADE;

CREATE TABLE comentarios
(
    id bigserial PRIMARY KEY
   ,texto varchar(255) NOT NULL
   ,usuario_id bigint NOT NULL REFERENCES usuarios (id)
   ,envio_id bigint NOT NULL REFERENCES envios (id)
   ,created_at timestamp(0) NOT NULL DEFAULT current_timestamp
   ,updated_at timestamp(0)
);

DROP TABLE IF EXISTS movimientos CASCADE;

CREATE TABLE movimientos
(
    usuario_id bigint NOT NULL REFERENCES usuarios (id)
   ,envio_id bigint NOT NULL REFERENCES envios (id)
   ,created_at timestamp(0) NOT NULL DEFAULT current_timestamp,
   PRIMARY KEY (usuario_id, envio_id)
);

INSERT INTO usuarios (nombre, password, email, created_at, updated_at)
    VALUES ('pepe', crypt('pepe', gen_salt('bf', 13)), 'pepe@pepe.com', current_timestamp, null)
         , ('juan', crypt('juan', gen_salt('bf', 13)), 'juan@juan.com', current_timestamp, null);
