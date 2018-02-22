------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios (
      id         BIGSERIAL      PRIMARY KEY
    , nombre     VARCHAR(255)   NOT NULL UNIQUE
    , email      VARCHAR(255)   NOT NULL UNIQUE
    , password   VARCHAR(255)   NOT NULL
    , created_at TIMESTAMP(0)   NOT NULL DEFAULT current_timestamp
    , updated_at TIMESTAMP(0)
    , auth_key   VARCHAR(255)
);

DROP TABLE IF EXISTS envios CASCADE;

CREATE TABLE envios (
      id          BIGSERIAL      PRIMARY KEY
    , url         VARCHAR(255)   NOT NULL
    , titulo      VARCHAR(255)   NOT NULL
    , entradilla  VARCHAR(255)   NOT NULL
    , soft_delete BOOLEAN        NOT NULL DEFAULT FALSE
    , usuario_id  BIGINT         NOT NULL REFERENCES usuarios (id)
                                 ON UPDATE CASCADE
                                 ON DELETE NO ACTION
    , created_at  TIMESTAMP(0)   NOT NULL DEFAULT current_timestamp
    , updated_at  TIMESTAMP(0)
);

DROP TABLE IF EXISTS comentarios CASCADE;

CREATE TABLE comentarios (
      id         BIGSERIAL      PRIMARY KEY
    , texto      VARCHAR(255)   NOT NULL
    , usuario_id BIGINT         NOT NULL REFERENCES usuarios (id)
                                ON UPDATE CASCADE
                                ON DELETE NO ACTION
    , envio_id   BIGINT         NOT NULL REFERENCES envios (id)
                                ON UPDATE CASCADE
                                ON DELETE NO ACTION
    , created_at TIMESTAMP(0)   NOT NULL DEFAULT current_timestamp
    , updated_at TIMESTAMP(0)
);

DROP TABLE IF EXISTS movimientos CASCADE;

CREATE TABLE movimientos (
      id         BIGSERIAL      PRIMARY KEY
    , usuario_id BIGINT         NOT NULL REFERENCES usuarios (id)
                                ON UPDATE CASCADE
                                ON DELETE NO ACTION
    , envio_id   BIGINT         NOT NULL REFERENCES envios (id)
                                ON UPDATE CASCADE
                                ON DELETE NO ACTION
    , created_at TIMESTAMP(0)   NOT NULL DEFAULT current_timestamp
);

--- DATOS DE PRUEBA

INSERT INTO usuarios (nombre, email, password)
     VALUES ('pepe', 'pepe@gmail.com', crypt('pepe',gen_salt('bf',13))),
            ('juan', 'juan@gmail.com', crypt('juan',gen_salt('bf',13))),
            ('pepito', 'pepito@gmail.com', crypt('pepito',gen_salt('bf',13)));

INSERT INTO envios (url, titulo, entradilla, usuario_id)
     VALUES (  'http://www.marca.com/futbol/real-madrid/2018/02/15/5a84c45e268e3e86498b4647.html'
             , 'Asensio es la revolución'
             , 'Zidane le sacó en el minuto 79 y en siete minutos puso patas arriba al PSG, el Bernabéu y la Champions. Desde la banda izquierda, desde su franja de poder, decantó el partido hacia el área de Areola.'
             , 1
            ),
            (  'http://www.marca.com/futbol/champions-league/2018/02/14/5a84af8646163fd4708b462b.html'
             , 'Asensio no es la revolución'
             , 'Zidane le sacó en el minuto 79asdfadsfdasf y en siete minutos puso patas arriba al PSG, el Bernabéu y la Champions. Desde la banda izquierda, desde su franja de poder, decantó el partido hacia el área de Areola.'
             , 2
            ),
            (  'http://www.marca.com/futbol/champions-league/2018/02/14/5a84af8646163fd4708b462b.html'
             , 'Asensio no es la revolución'
             , 'Zidane le sacó en el minuto 79asdfadsfdasf y en siete minutos puso patas arriba al PSG, el Bernabéu y la Champions. Desde la banda izquierda, desde su franja de poder, decantó el partido hacia el área de Areola.'
             , 1
            );

INSERT INTO comentarios (texto, usuario_id, envio_id)
     VALUES ('asdhjbfasdf', 1, 1),
            ('TO MAL', 2, 1),
            ('TO BIEN', 2, 2);
