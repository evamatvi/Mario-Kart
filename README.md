# Kario Mart

## Descripció
Aplicació web amb PHP i Oracle per gestionar curses, vehicles i cerimònies de premis del món de Kario Mart.

## Funcionalitats

### A — Donar d'alta un vehicle
Alta de vehicles amb codi autogenerat (5 primers caràcters de la descripció + dígits aleatoris si hi ha duplicat).

### B — Mostrar vehicles
Llistat de vehicles amb el cost per fer 100 km calculat a partir de preuUnitat × consum.

### C — Inscripcions en una cursa
Inscripció de participants (usuari + personatge + vehicle) en curses obertes, gestionant sessions amb $_SESSION.

### D — Entrar temps dels participants
Entrada de temps en format MM:SS, càlcul del millor temps i actualització a ParticipantsCurses.

### E — Consultar participants d'una cursa
Classificació amb temps ordenats o el text Abandonat si no hi ha temps entrats.

### F — Cerimònia de premis
Inserció automàtica dels tres primers classificats a Cerimònies amb la quantitat de Pinky i la data_hora_cerimonia (2h després del participant més lent).

#### F1 — Quantitat de Pinky per personatge entre dues dates
#### F2 — Historial de cerimònies d'un personatge

