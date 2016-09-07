#OSN datasets

Github URL: https://github.com/openstate/datasets/tree/master/osn

Team: Lex, Arjan, Tim e.a. 

###GENERAL IDEA

To identify governments with a unique identifier. However, there is no central database of governments. Our ambition is to have an
updatable list of governments in the Netherlands, that is completely transparant. This means that a count of all governments can be done on a regular basis by rebuilding the list based on all sources. OSN is short for Overheids Service Nummer, which means government service number.

###A DEFINITION OF GOVERNMENT
This is a though question... Or project definition is that a part of government does not equal a whole government. Meaning that city departments do not map onto 1 city(!) The list we try to gather, however can contain city  -departments as partial governments. In a next phase we envision allowing more complex relations between the seperate governments; such as 'department of' and so on. This is an important notice as for instance some governments have multiple chambre of commerce numbers for seperate divisions. Is such a case a Chambre of Commerce id is not an unique identifier of (the entire) goverment.

###PROCESS OF ADDING OR UPDATING SOURCES

This document describes a LIST of SOURCES. If you happen to know a
new source, either web or file or API which identifies governments then
please do PUSH your suggestion to this LIST OF SOURCES.  

Together with this list a STATUS is provided: 'to-do', 'manual' or 'automated'. 
To move a SOURCE on the LIST from 'to-do' to 'done'. 
We foresee the following procedure (first assuming an manual addition or update of a source):

1) Gather all raw fetchable data from the source. Put this data in
preferably in a subfolder.

2) Put your data in a OSN SOURCE TABLE, with one government per row, containing some
unique identifier, specific for the source.

3) Extend this table with the OSN MASTER TABLE. This table contains per
column an identifier per government per source. You need to 'join' your list
with the master table.

4) Export from this table a new OSN MASTER TABLE, now with your column as a new
colom, updated with you new records as rows.

5) Push the new OSN MASTER TABLE, together with your OSN SOURCE TABLE

6) From there we will figure things out.

###LIST OF SOURCES

* to-do: Almanak Rijksoverheid 
ftp://oorg.asp4all.nl/20160815220000.xml (logius)
Rijksoverheid almanak (link https://almanak.overheid.nl/)

* done (manual): De Overheid.nl Web Metadata Standaard (OWMS) is de metadatastandaard voor informatie van de Nederlandse overheid op internet. De standaard is gebaseerd op de internationale metadatastandaard van het Dublin Core Metadata Initiative (DCMI). Er zijn twee groepen van waardelijsten die vergelijkbare typen van waarden bevatten: waardelijsten met namen van organisaties en waardelijsten met namen van bestuursorganen. http://standaarden.overheid.nl/owms/4.0/doc/waardelijsten
Overheidsorganisatie is een samenvoeging van de waardelijsten die per organisatietype worden bijgehouden. http://standaarden.overheid.nl/owms/terms/Overheidsorganisatie.html

* done (manual): Nationale ombudsman
https://www.nationaleombudsman.nl/overheidsinstanties
Nationale Ombudsman heeft een lijst van organisaties. De Nationale ombudsman behandelt klachten over de overheid. Hieronder  vallen bijna alle overheidsorganisaties: ministeries, waterschappen,  provincies, gemeentes, het UWV, de Sociale Verzekeringsbank (SVB) en de  politie. Ook particuliere instellingen met een overheidstaak, zoals de  Stichting Centraal Bureau Rijvaardigheidsbewijzen (CBR), vinden wij een  overheidsorganisatie. 

* to-do: Erfgoedinspectie
https://www.erfgoedinspectie.nl/toezichtvelden/archieven/inhoud/geinspecteerde-instellingen
Erfgoedinspectie heeft een lijst van organisaties met archiefwettelijk toezicht. Dit zijn overheidsorganisaties die vallen onder het toezicht van de Erfgoedinspecties. In het overzicht staat ook een verwijzing naar de wettelijke grondslag. 

* done (manual): RWT register
http://www.rekenkamer.nl/Publicaties/Dossiers/B/Bestuur_op_afstand/Overzicht_met_rechtspersonen_met_een_wettelijke_taak_RWT
Overzicht met rechtspersonen met een wettelijke taak (RWT). Rechtspersonen met een wettelijke taak zijn instellingen op afstand van het Rijk. Naast rechtspersonen met een wettelijke taak (rwt’s) zijn er andere instellingen op afstand van het Rijk, bijvoorbeeld de zelfstandige bestuursorganen (zbo’s). De categorieën zbo's en rwt’s komen grotendeels overeen, maar er zijn ook belangrijke verschillen. De Comptabiliteitswet definieert rechtspersonen met een wettelijke taak (rwt’s) als 'rechtspersonen voor zover die een bij of krachtens de wet geregelde taak uitoefenen en daartoe geheel of gedeeltelijk worden bekostigd uit de opbrengst van bij of krachtens de wet ingestelde heffingen'. De definiërende kenmerken van een rwt zijn: rechtspersoon; wettelijke taak; wettelijke heffing. Een instelling op afstand van het Rijk die aan alle bovenstaande criteria voldoet kan beschouwd worden als een rwt. RWT’s hebben een eigen bestuurlijke verantwoordelijkheid.

* done (manual): WNT Register
https://www.topinkomens.nl/voor-wnt-instellingen/inhoud/wnt-register
WNT register wordt door het ministerie van Binnenlandse Zaken en  Koninkrijksrelaties (BZK) bijgehouden om instellingen die onder de WNT (Wet normering topinkomens) vallen te kunnen informeren over deze wet en om toezicht op de naleving  van de WNT te houden. Een instelling of rechtspersoon valt onder de WNT  als één of meer van de criteria uit de WNT van toepassing zijn. 

* done (manual): Website register
https://www.communicatierijk.nl/vakkennis/r/rijkswebsites-verplichte-richtlijnen/inhoud/websiteregister
Websiteregister Rijksoverheid geeft voor *alle* websites van de Rijksoverheid aan welke organisatie én evt. subafdeling eigenaar is. Iedere laatste donderdag van de maand wordt het Websiteregister (ODS, 49 KB) bijgewerkt en gepubliceerd. https://www.communicatierijk.nl/binaries/communicatierijk/documenten/publicaties/2016/05/26/websiteregister/webregister-rijksoverheid-20160825.ods

* done (manual): OIN register
https://register.digikoppeling.nl/overview/index
OIN register geeft een overzicht van alle uitgegeven openbare overheidsidentificatienummers. Elke overheidsorganisatie die digitaal zaken doet kan een uniek Overheidsidentificatienummer (OIN) krijgen. Het OIN is een uniek identificerend nummer dat gebruikt wordt in de digitale communicatie tussen overheden.

* done (manual): ZBO register - 
https://almanak.zboregister.overheid.nl/

* done (manual): Agentschappen - Rijksoverheid
https://www.rijksoverheid.nl/onderwerpen/rijksoverheid/inhoud/agentschappen/lijst-agentschappen

* done (manual / api entry): Werken bij de overheid
http://docs.api.cso20.net/
Een site voor vacatures bij de overheid. Hier leveren zo'n 303 overheidsinstellingen vacatures aan. In een vacature staat meestal ook naam en adres van overheidsinstelling. 

* done (manual): Mijn overheid
https://mijn.overheid.nl/contact/overzicht
Lijst met aangesloten overheidsorganisaties in MijnOverheid - 

* done (manual): Kennisbank Openbaar Bestuur
http://kennisopenbaarbestuur.nl:80/tnglite/olap?guid=1e8a0ab4-f368-4d5e-9cff-348a294f0634
Kennisbank Openbaar Bestuur - informatie over sociale zekerheid en werknemers bij overheid, openbaar bestuur, veiligheid en onderwijs. Tabellen zijn uit te klappen vaak onderverdeeld per provincie en gemeente. In ieder geval komt hiermee de naam van de gemeente/plaats bij de organisatie. 

* to-do: Nationaal Archief TOCO / Actorenregister
http://www.gahetna.nl/over-ons/open-data/archiefinventarissen-en-scans-archieven#TOCO
Datadump hier: https://www.gahetna.nl/sites/default/files/bijlagen/ibro_dump_13012015.sql_.zip
Database online te raadplegen hier: https://ibro.nationaalarchief.nl/
https://ibro.nationaalarchief.nl/index.php/;actor/browse?page=2&type=suborganisation&limit=1000
https://ibro.nationaalarchief.nl/index.php/directie-consulaire-zaken-en-visumbeleid;isaar
https://ibro.nationaalarchief.nl/index.php/ministerie-van-buitenlandse-zaken;isaar
https://ibro.nationaalarchief.nl/index.php/ministerie-van-buitenlandse-zaken;eac?sf_format=xml

* done (manual): Overheidsdiensten
Dit is een niet-geharmoniseerde subset in het kader van INSPIRE annex III, thema Administratieve en sociale en overheidsdiensten.
https://data.overheid.nl/data/dataset/overheidsdiensten (bron: http://geodata.nationaalgeoregister.nl/overheidsdiensten/atom/overheidsdiensten.xml)

* done (manual): VNG ORCA
Lijst met gemeenten, adressen, uit ORCA database VNG. Bestandsnaam: Adres 2014 - Gemeenten (indeling 2015)
http://www.waarstaatjegemeente.nl/jive

* to-do: CBS
http://dataderden.cbs.nl/ODataApi/OData/45006NED/Gemeenten
 (namen van gemeenten, cbs unieke identifier van gemeenten)
- bv. Gemeente Amsterdam id:ams0001
- ook voor GRA
- lijst van gemeenten in CBS : 
Key: "GM0363 ",Title: "Amsterdam",Description: "Begindatum voor 1830"
Key: "GM1728 ", Title: "Bladel", Description: "Ontstaan per 01-01-1997"
Key: "GM0063 ",Title: "het Bildt",Description: "Begindatum voor 1830", 
Key: "GM0310 ",Title: "De Bilt", Description: "Gemeentelijke herindeling per 01-01-2001 Begindatum voor 1830"

* to-do: Rijks almanak
- https://almanak.overheid.nl/25698/Gemeente_Amsterdam/ 
- https://almanak.overheid.nl/28270/Gemeente_Amstelveen/
- https://almanak.overheid.nl/68820/Ministerie_van_Financi%C3%ABn/ 
- https://almanak.overheid.nl/68821/Financi%C3%ABn/Algemene_Leiding/
- https://almanak.overheid.nl/68888/Financi%C3%ABn/Directoraat-generaal_Belastingdienst/

De organisaties zijn zelf verantwoordelijk voor de volledigheid, juistheid en actualiteit van de gegevens.
Gemeenten
Provincies
Waterschappen
Regionale samenwerkingsorganen
Openbare lichamen voor beroep en bedrijf
Staten-Generaal
Hoge Colleges van Staat
Ministeries
Adviescolleges
Zelfstandige bestuursorganen (externe link)
Rechterlijke macht
Politie en brandweer
Caribisch Nederland (BES-eilanden)
Aruba, Curaçao en Sint Maarten

* to-do: Rechtspraak
 https://www.rechtspraak.nl/Organisatie-en-contact/Organisatie/Landelijke-diensten

* to-do:Staatsdeelnemingen
https://www.rijksoverheid.nl/onderwerpen/staatsdeelnemingen/inhoud/portefeuille-staatsdeelnemingen

* to-do:Kadaster
https://bagviewer.kadaster.nl/lvbag/bag-viewer/index.html#?searchQuery=Amstel%201,%20Amsterdam&objectId=0363010011872295&geometry.x=121833&geometry.y=486756&zoomlevel=13&detailsObjectId=0363010011872295
Als bron om overheden te identificeren. Bronhouder ID 0363 Naam Amsterdam (dus elk huis in Amsterdam komt van bronhouder Amsterdam met ID 0363

* to-do:Kvk
https://www.kvk.nl/orderstraat/bedrijf-kiezen/?q=gemeente+Amsterdam#!shop?&q=gemeente%20Amsterdam&start=0&prefproduct=&prefpayment=
Als bron om overheden (en vestigingen / organisatie onderdelen, enz)  te identificeren
vb. velden: , Stadhuis , Hoofdvestiging, KvK 34366966 , Vestigingsnr. 000007678460 , Amstel 1 ,Amsterdam, http://www.amsterdam.nl
Stadsarchief Amsterdam, KvK 34366966, Vestigingsnr. 000007679092, Vijzelstraat 32, Amsterdam, Nevenvestiging, http://www.stadsarchief.amsterdam.nl
https://www.kvk.nl/orderstraat/bedrijf-kiezen/?q=gemeente+Amsterdam#!shop?&q=gemeente%20Amstelveen&start=0&prefproduct=&prefpayment= , Raadhuis Amstelveen, Hoofdvestiging, KvK 34365024, Vestigingsnr. 000015305678, Laan Nieuwer-Amstel 1, Amstelveen, http://www.amstelveen.nl

TEMPORARY NOTES
- 1 tabel voor alle organisaties cq. bedrijven. Met unieke identifiers en eventueel een URL naar een formele bron houder + doel organisatie
Naam:' gemeente Amsterdam' , CBSID: Ams001, CBSURL:' http' , ToCo: 020010, toCoURL:' http' , RijksAlmak: 'rijks id=978654' , RijksalamankURL:'http' ,kvk: 8654332, KVKURL: ' ' 
Naam:' stadsregio Amsterdam' , CBSID: null, ToCo: null, RijksAlmak: ' null' , ' rijks id=null' , kvk: 8654332
- 1 tabel voor gerichte relaties tussen organisaties; een relatie moet opwaarts of neerwaarts zijn
'stadsregio Amsterdam' , 'GRA VAN' , 'gemeente Amsterdam' 
'stadsregio Amsterdam' , 'GRA VAN' , 'gemeente Amstelveen' 
'stadsregio Amsterdam' , 'GRA VAN' , 'gemeente Bijlmer' 
Relatie typen: GRA, Deelneming, Nevenvestiging, 'Rijks Almanak organisatie onderdeel' etc.
