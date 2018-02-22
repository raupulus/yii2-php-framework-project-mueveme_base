------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
      id         bigserial    PRIMARY KEY
    , nombre     varchar(255) NOT NULL UNIQUE
    , password   varchar(255) NOT NULL
    , email      varchar(255) NOT NULL UNIQUE
    , authKey    varchar(255) UNIQUE
    , created_at timestamp(0) NOT NULL DEFAULT current_timestamp
    , updated_at timestamp(0)
);


DROP TABLE IF EXISTS envios CASCADE;

CREATE TABLE envios
(
      id         bigserial    PRIMARY KEY
    , url        varchar(255) NOT NULL UNIQUE
    , entradilla text         NOT NULL
    , usuario_id bigint       NOT NULL REFERENCES usuarios (id)
                              ON DELETE NO ACTION ON UPDATE CASCADE
    , created_at timestamp(0) NOT NULL DEFAULT current_timestamp
    , updated_at timestamp(0)
);



DROP TABLE IF EXISTS comentarios CASCADE;

CREATE TABLE comentarios (
      id         bigserial    PRIMARY KEY
    , texto      text         NOT NULL
    , usuario_id bigint       NOT NULL REFERENCES usuarios (id)
                              ON DELETE NO ACTION ON UPDATE CASCADE

    , created_at timestamp(0) NOT NULL DEFAULT current_timestamp
    , updated_at timestamp(0)
);


DROP TABLE IF EXISTS movimientos CASCADE;

CREATE TABLE movimientos
(
      usuario_id bigint       REFERENCES usuarios (id)
                              ON DELETE NO ACTION ON UPDATE CASCADE
    , envio_id   bigint       REFERENCES envios (id)
                              ON DELETE NO ACTION ON UPDATE CASCADE
    , created_at timestamp(0) NOT NULL DEFAULT current_timestamp
    , updated_at timestamp(0)
    , primary key(usuario_id,envio_id)
);

-- Datos de prueba

INSERT INTO usuarios (nombre, password, email)
     VALUES ('juan', crypt('juan', gen_salt('bf', 13)), 'juan@juan.com')
          , ('maria', crypt('maria', gen_salt('bf', 13)), 'maria@maria.com')
          , ('edu', crypt('edu', gen_salt('bf', 13)), 'edu@edu.com');

INSERT INTO envios (url, entradilla, usuario_id)
    VALUES ('http://www.publico.es/ciencias/iter-mayor-proyecto-mundo-obtener-energia-alcanza-ecuador.html','De un plácido paisaje de la Provenza francesa al atareadísimo escenario en el que se construye el gigantesco reactor de fusión nuclear ITER, el mayor proyecto de ciencia e ingeniería de la historia, que pretende demostrar que es posible obtener energía por el mismo proceso que funciona en las estrellas. Así ha transformado en pocos años la región cercana a Aix-en-Provence un proyecto que acaba de pasar el ecuador de su construcción, como han anunciado sus responsables.', 2)
         , ('http://www.abc.es/ciencia/abci-falcon-heavy-comienza-nueva-espacial-201802111952_noticia.html', 'El pasado lunes 5 de febrero las redes sociales mostraron casi en tiempo real una rara e hipnótica imagen:un descapotable de color rojo cereza de la marca Tesla surcaba el espacio con un astronauta al volante. La carrocería reflejaba la neblinosa atmósfera de la Tierra y el piloto, un maniquí llamado Starman, miraba hacia el frente con estoicismo en su camino hacia las estrellas. Los colores se veían extrañamente nítidos, a causa de la ausencia de atmósfera, y la panorámica parecía sacada de una película de serie B. Pero todo era resultado de un hecho histórico en la carrera del hombre al espacio: el automóvil fue enviado a las estrellas en el primer vuelo de prueba del cohete Falcon Heavy, de la compañía Space X. Este se convirtió en el lanzador más pesado en décadas, desde los Saturn V y Energiya, y su poder duplicó al que ostentaba la corona hasta ahora, el Delta IVHeavy', 1);

INSERT INTO comentarios (texto, usuario_id)
     VALUES ('Molaaa', 3)
          , ('Mueveme es la nueva meneame', 1);

INSERT INTO movimientos (usuario_id, envio_id)
     VALUES (1, 2)
          , (3, 1)
          , (1, 1);
