------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
    id         bigserial    PRIMARY KEY
  , nombre     varchar(255) NOT NULL UNIQUE
  , email      varchar(255) NOT NULL
  , password   varchar(255) NOT NULL
  , created_at timestamp(0) NOT NULL /* DEFAULT current_timestamp */
  , updated_at timestamp(0)
);

CREATE INDEX idx_usuarios_email ON usuarios (email);

DROP TABLE IF EXISTS envios CASCADE;

CREATE TABLE envios
(
    id         bigserial     PRIMARY KEY
  , url        varchar(255)  NOT NULL
  , titulo     varchar(255)  NOT NULL
  , entradilla varchar(1000) NOT NULL
  , usuario_id bigint        NOT NULL REFERENCES usuarios (id)
                             ON DELETE NO ACTION ON UPDATE CASCADE
  , created_at timestamp(0)  NOT NULL DEFAULT current_timestamp
  , updated_at timestamp(0)
);

DROP TABLE IF EXISTS comentarios CASCADE;

CREATE TABLE comentarios
(
    id         bigserial    PRIMARY KEY
  , texto      varchar(255) NOT NULL
  , envio_id   bigint       NOT NULL REFERENCES envios (id)
                            ON DELETE NO ACTION ON UPDATE CASCADE
  , usuario_id bigint       NOT NULL REFERENCES usuarios (id)
                            ON DELETE NO ACTION ON UPDATE CASCADE
  , created_at timestamp(0) NOT NULL DEFAULT current_timestamp
  , updated_at timestamp(0)
);

DROP TABLE IF EXISTS votos CASCADE;

CREATE TABLE votos
(
    usuario_id    bigint       NOT NULL REFERENCES usuarios (id)
                               ON DELETE NO ACTION ON UPDATE CASCADE
  , comentario_id bigint       NOT NULL REFERENCES comentarios (id)
                               ON DELETE NO ACTION ON UPDATE CASCADE
  , positivo      boolean      NOT NULL
  , PRIMARY KEY (usuario_id, comentario_id)
);

DROP TABLE IF EXISTS movimientos CASCADE;

CREATE TABLE movimientos
(
    usuario_id bigint       NOT NULL REFERENCES usuarios (id)
                            ON DELETE NO ACTION ON UPDATE CASCADE
  , envio_id   bigint       NOT NULL REFERENCES envios (id)
                            ON DELETE NO ACTION ON UPDATE CASCADE
  , created_at timestamp(0) NOT NULL DEFAULT current_timestamp
  , PRIMARY KEY (usuario_id, envio_id)
);

INSERT INTO usuarios (nombre, password, email, created_at)
    VALUES ('oscar', crypt('oscar', gen_salt('bf', 13)), 'oscar@oscar.com', current_timestamp(0))
         , ('jose', crypt('jose', gen_salt('bf', 13)), 'jose@jose.com', current_timestamp(0))
         , ('pepe', crypt('pepe', gen_salt('bf', 13)), 'pepe@pepe.com', current_timestamp(0));

INSERT INTO envios (url, titulo, entradilla, usuario_id, created_at)
    VALUES ('http://www.quo.es/ciencia/crean-una-nueva-forma-de-luz',
                'Crean una nueva forma de luz',
                'Seguramente alguna vez has entrado a un cuarto oscuro con otras personas',
                1, current_timestamp - 'P4D'::interval)
         , ('https://www.hibridosyelectricos.com/articulo/actualidad/increible-rendimiento-ferry-electrico-noruego-ampere-propicia-53-nuevos-pedidos/20180212114443017434.html',
                'El increíble rendimiento del ferry eléctrico Ampere propicia 53 nuevos pedidos',
                'Ampere es el primer ferry eléctrico construido en Noruega, y su fabricante asegura que la embarcación reduce las emisiones un 95% y los costes un 80%',
                2, current_timestamp - 'P3D'::interval)
         , ('http://danielmarin.naukas.com/2018/02/16/europa-apuesta-por-pld-space-para-alcanzar-el-espacio/',
                'Europa apuesta por PLD Space para alcanzar el espacio',
                'La empresa española PLD Space no solo sigue adelante con sus planes para lanzar cohetes espaciales desde territorio español, sino que van a toda máquina.',
                3, current_timestamp - 'P2D'::interval)
         , ('https://es.gizmodo.com/hay-un-boeing-737-abandonado-en-un-campo-de-bali-y-nad-1823076674',
                'Hay un Boeing 737 abandonado en un campo de Bali, y nadie sabe quién lo dejó ahí',
                'Cuentan que para encontrar al misterioso aparato, curiosamente muy bien conservado, hay que acudir al sur de la península de Bukit.',
                1, current_timestamp - 'P1D'::interval)
         , ('http://www.cosasrandom.com/musica-idioma-universal/',
                '¿Es la música un idioma universal?',
                'Un reciente estudio de Harvard de amplio alcance con 750 participantes online de 60 países diferentes demostraría que cualquier persona en el mundo, sin importar su origen cultural, puede identificar el tipo de música de entre una de varias categorías.',
                2, DEFAULT);

INSERT INTO comentarios (texto, envio_id, usuario_id)
    VALUES ('Guay tio!!!!', 1, 2)
         , ('Qué bueno el ferry', 2, 1)
         , ('Muy interesante lo del ferry', 2, 3);

INSERT INTO movimientos (usuario_id, envio_id)
    VALUES (1, 1)
         , (1, 2)
         , (2, 1);

INSERT INTO votos (usuario_id, comentario_id, positivo)
    VALUES (1, 2, true)
         , (2, 2, true)
         , (3, 2, false);
