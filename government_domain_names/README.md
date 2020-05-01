`domains.csv` bevat een lijst met domeinnamen van Nederlandse overheidswebsites. Momenteel zitten er domeinen van de rijksoverheid, provincies, gemeenten, waterschappen en gemeenschappelijke regelingen in.

De lijst met domeinen voor provincies en waterschappen houden we zelf bij (zie `Provincies.csv` en `Waterschappen.csv`). De domeinen voor de rijksoverheid, gemeenten en gemeenschappelijke regelingen halen we uit lijsten van de overheid zelf. Hieronder staat beschreven hoe dat in z'n werk gaat.

Download de meest recente bestanden van de volgende websites en sla handmatig de kolom met de domainnamen op (zonder kolomnaam) op als `.csv` onder de bestandsnaam beschreven achter de URL:
- https://www.communicatierijk.nl/vakkennis/r/rijkswebsites/verplichte-richtlijnen/websiteregister-rijksoverheid selecteer zowel de `URL`-kolom als de `Organisatie`-kolom en sla op als `Rijksoverheid.csv` (Iedere laatste donderdag van de maand wordt het Websiteregister (ODS, 51 KB) bijgewerkt en gepubliceerd.)
    - voer `sort -t: -k2 Rijksoverheid.csv | sponge Rijksoverheid.csv` uit om een schonere diff te krijgen
- `cd almanak` en run `./almanak.py`, kopieer de nieuw aangemaakt `almanak-gemeenten-unique-xxxx-xx-xxTxx:xx:xx.csv` naar `../Gemeenten.csv`
- `cd almanak-gr` en run `./almanak.py`, kopieer de nieuw aangemaakt `almanak-gemeenschappelijke-regelingen-unique-xxxx-xx-xxTxx:xx:xx.csv` naar `../Gemeenschappelijke-Regelingen.csv`

Run het converteer script dat de websites uit de individuele bestanden haalt en samenvoegd en opslaat als `domains.csv`:

    ./create_list.py
