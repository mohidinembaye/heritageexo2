-- Active: 1785779490330@@127.0.0.1@5432@exo01


CREATE TABLE copies_examen (
    id SERIAL PRIMARY KEY,
    date_depot DATE NOT NULL DEFAULT now(),
    note_brute NUMERIC(4, 2) NOT NULL CHECK (note_brute BETWEEN 0 AND 20),
    note_finale NUMERIC(4, 2) NOT NULL CHECK (note_finale BETWEEN 0 AND 20),
    penalite_appliquee NUMERIC(4, 2) NOT NULL DEFAULT 0,
    date_limite DATE NOT NULL
);

INSERT INTO
    copies_examen (
        date_depot,
        note_brute,
        note_finale,
        penalite_appliquee,
        date_limite
    )
VALUES (
        '2026-02-10 10:00:00+00',
        16.00,
        15.00,
        1.00,
        '2026-02-09 23:59:00+00'
    );

SELECT * FROM copies_examen;