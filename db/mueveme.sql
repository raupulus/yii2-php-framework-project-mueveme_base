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
  , created_at timestamp    NOT NULL
  , updated_at timestamp
);

DROP TABLE IF EXISTS categorias CASCADE;

CREATE TABLE categorias
(
    id        bigserial    PRIMARY KEY,
    nombre    varchar(255) NOT NULL
);

DROP TABLE IF EXISTS envios CASCADE;

CREATE TABLE envios
(
    id            bigserial    PRIMARY KEY
  , url           varchar(255) NOT NULL
  , titulo        varchar(255) NOT NULL
  , entradilla    varchar(255) NOT NULL
  , usuario_id    bigint       NOT NULL REFERENCES usuarios (id)
                            ON DELETE NO ACTION
  , categoria_id  bigint    NOT NULL REFERENCES categorias (id)
                            ON DELETE NO ACTION
  , url_img       varchar(255)
  , created_at    timestamp    NOT NULL
  , updated_at    timestamp
);

DROP TABLE IF EXISTS comentarios CASCADE;

CREATE TABLE comentarios
(
    id         bigserial    PRIMARY KEY
  , texto      varchar(255) NOT NULL
  , usuario_id bigint       NOT NULL REFERENCES usuarios (id)
                            ON DELETE NO ACTION
  , envio_id   bigint       NOT NULL REFERENCES envios (id)
                            ON DELETE NO ACTION
  , created_at timestamp    NOT NULL
  , updated_at timestamp
);

DROP TABLE IF EXISTS movimientos CASCADE;

CREATE TABLE movimientos
(
    usuario_id bigint    NOT NULL REFERENCES usuarios (id)
                         ON DELETE NO ACTION
  , envio_id   bigint    NOT NULL REFERENCES envios (id)
                         ON DELETE NO ACTION
  , created_at timestamp NOT NULL
  , PRIMARY KEY (usuario_id,envio_id)
);

DROP TABLE IF EXISTS votos CASCADE;

CREATE TABLE votos
(
    usuario_id    bigint     NOT NULL REFERENCES usuarios (id)
                             ON DELETE NO ACTION
  , comentario_id bigint     NOT NULL REFERENCES comentarios (id)
                             ON DELETE NO ACTION
  , voto          boolean    NOT NULL
  , created_at    timestamp  NOT NULL
  , PRIMARY KEY (usuario_id, comentario_id)
);


-- Datos de prueba

INSERT INTO usuarios (nombre, email, password, created_at)
    VALUES ('rafa', 'rafa@rafa.com', crypt('rafa', gen_salt('bf', 13)), current_timestamp - 'P1D'::interval)
    , ('narvaez', 'narvaez@narvaez.com', crypt('narvaez', gen_salt('bf', 13)), current_timestamp - 'P2D'::interval)
    , ('pepe', 'pepe@pepe.com', crypt('pepe', gen_salt('bf', 13)), current_timestamp - 'P2D'::interval)
    , ('juan', 'juan@juan.com', crypt('juan', gen_salt('bf', 13)), current_timestamp - 'P2D'::interval);

INSERT INTO categorias (nombre)
    VALUES ('Deporte'), ('Tecnología'), ('Actualidad'), ('Política');

INSERT INTO envios (url, titulo, entradilla, usuario_id, categoria_id, url_img, created_at)
    VALUES (
        'http://www.marca.com/motor/motogp/2018/02/17/5a87eae9ca4741073f8b469a.html',
        'Márquez se regala el primer lugar en el segundo día en Buriram',
        'Después de una primera jornada en la que acabó mandando Crutchlow,
        aunque con Márquez y Dovizioso con mejor ritmo que nadie.',
        2,
        1,
        'https://www.dropbox.com/s/w5xf0ay946uxoub/1.jpg?dl=1',
        localtimestamp - 'P3D'::interval
        ),
        (
        'https://www.xataka.com/espacio/control-fronterizo-y-estudio-medioambiental-asi-es-el-primer-satelite-que-espana-lanzara-con-spacex',
        'Así es el primer satélite espía español, que será lanzado este domingo por SpaceX',
        'Desde el principio se le ha considerado como un satélite espía.',
        1,
        2,
        'https://www.dropbox.com/s/fnqih4yfrd6tffc/2.jpg?dl=1',
        localtimestamp - 'P2D'::interval
        ),
        (
        'http://www.elmundotoday.com/2018/02/la-piramide-alimenticia-ya-es-solo-un-trozo-de-toblerone-gigante/',
        'La pirámide alimenticia ya es sólo un trozo de Toblerone gigante',
        'Tras muchos años sin prestarle atención, un equipo de nutricionistas
        ha comprobado esta semana que la pirámide alimenticia ya es sólo un
        trozo de Toblerone gigante. ',
        2,
        3,
        'https://www.dropbox.com/s/09odb0eebvuqga1/3.jpg?dl=1',
        localtimestamp
        );

INSERT INTO movimientos (usuario_id, envio_id, created_at)
    VALUES (1, 1, localtimestamp)
         , (2, 1, localtimestamp)
         , (3, 1, localtimestamp)
         , (4, 1, localtimestamp)
         , (1, 2, localtimestamp)
         , (2, 2, localtimestamp)
         , (3, 2, localtimestamp);


INSERT INTO comentarios (texto, usuario_id, envio_id, created_at)
    VALUES ('Me encanta la notica', 1, 1, localtimestamp - 'P1D'::interval)
         , ('Me gustaaa!', 2, 1, localtimestamp - 'P3D'::interval)
         , ('No me gusta la noticia', 2, 2, localtimestamp - 'P2D'::interval);

INSERT INTO votos (usuario_id, comentario_id, voto, created_at)
    VALUES (1, 1, true, current_timestamp);
