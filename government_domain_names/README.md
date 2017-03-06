`domains.csv` bevat een lijst met domeinnamen van Nederlandse overheidswebsites. Momenteel zitten er domeinen van de rijksoverheid, provincies, gemeenten, waterschappen en gemeenschappelijke regelingen in.

De lijst met domeinen voor provincies en waterschappen houden we zelf bij (zie `Provincies.csv` en `Waterschappen.csv`). De domeinen voor de rijksoverheid, gemeenten en gemeenschappelijke regelingen halen we uit lijsten van de overheid zelf. Hieronder staat beschreven hoe dat in z'n werk gaat.

Download de meest recente bestanden van de volgende websites en sla handmatig de kolom met de domainnamen op (zonder kolomnaam) op als `.csv` onder de bestandsnaam beschreven achter de URL:
- https://www.communicatierijk.nl/vakkennis/r/rijkswebsites-verplichte-richtlijnen/inhoud/websiteregister selecteer zowel de `domeinnaam`-kolom als de `organisatie`-kolom en sla op als `Rijksoverheid.csv`
- https://www.waarstaatjegemeente.nl/jive?sel_guid=56f41a2d-072b-43ac-9d21-d74e16e2d7b9 klik op de downloadknop, selecteer `Microsoft Excel werkblad` (ODS en CSV werken niet goed) en hernoem het naar `Gemeenten.csv` (kijk of er inmiddels een recentere periode dan 2014 geselecteerd kan worden)
- https://data.overheid.nl/data/dataset/gemeenschappelijke-regelingen velden zijn gescheiden door een puntkomma, sla de 'Link naar site'-kolom op als `Gemeenschappelijke-Regelingen.csv`

Run het converteer script dat de websites uit de individuele bestanden haalt en samenvoegd en opslaat als `domains.csv`:

    ./create_list.py
