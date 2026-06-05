SET echo off;

set feedback off;
PROMPT Construint les taules per les cerimonies...

--  Taula: Cerimonies                      
DROP TABLE Cerimonies CASCADE CONSTRAINT;
CREATE TABLE Cerimonies(
	codiCursa VARCHAR2(15) NOT NULL,
    alias_personatge VARCHAR2(15) NOT NULL,
    posicio DECIMAL (1,0) NOT NULL CHECK (posicio IN (1, 2, 3)),
    quantitat_pinky DECIMAL(4, 2) NOT NULL,
    data_hora_cerimonia DATE NOT NULL,
    PRIMARY KEY (codiCursa, alias_personatge), -- Clau primària composta
    FOREIGN KEY (codiCursa) REFERENCES Curses(codi),
    FOREIGN KEY (alias_personatge) REFERENCES Personatges(alias)
);

COMMIT;
SET echo on;