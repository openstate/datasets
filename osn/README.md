OSN datasets

Github URL: https://github.com/openstate/datasets/tree/master/osn

Team: Lex Arjan TimdH?, ChristiaanSchouten, StefandeK? / ChristophK?,LEX + ARJAN Van te voren data op halen. 

GENERAL IDEA
We want to identify the governments with a unique name or ID. However, there
is no central database of governments. Our ambition is to have an
updatable list of governments in the Netherlands, that is completely
transparant. This means a count of ALL governments can be done on a regular
basis by rebuilding the list based on all sources. OSN is short for
overheids service nummer, which means government service nummer ;-)

A DEFINITION OF GOVERNMENT 
This is a though question... Or current definition is that a part of
government does not equal a whole government. Meaning that city departments
do not map onto 1 city(!) The list we try to gather, however can contain city
departments as partial governments. In a next phase we envision allowing
more complex relations between the seperate governments; such as
'department of' and so on.


PROCESS OF ADDING OR UPDATING SOURCES
This document describes a LIST of SOURCES. If you happen to know a
new source, either web or file or API which identifies governments then
please do PUSH your suggestion to this LIST OF SOURCES.  Together with this
list a STATUS is provided: 'to-do', 'manual' or 'automated'. To move a SOURCE on the LIST from 'to-do'  to 'done'. We foresee the
following procedure (first assuming an manual addition or update of a source):

1) Gather all raw fetchable data from the source. Put this data in
preferably in a subfolder ;-)

2) Put your data in a OSN SOURCE TABLE, with one government per row, containing some
unique identifier, specific for the source. 

3) Extend this table with the OSN MASTER TABLE. This table contains per
colom an identifier per government per source. You need to 'join' your list
with the master table.

4) Export from this table a new OSN MASTER TABLE, now with your colom as a new
colom, updated with you new records as rows.

5) Push the new OSN MASTER TABLE, together with your OSN SOURCE TABLE

6) From there we will figure things out :-D


LIST OF SOURCES
* to-do:Almanak Rijksoverheid 
ftp://oorg.asp4all.nl/20160815220000.xml (logius)
Rijksoverheid almanak (link https://almanak.overheid.nl/)
Waardelijsten overheidsorganisatie OWMS - http://standaarden.overheid.nl/owms/terms/Overheidsorganisatie.html
Dit zijn de gecontroleerde waardelijsten (controlled vocabularies of vocabulary encoding schemes) van OWMS - http://standaarden.overheid.nl/owms/4.0/doc/waardelijsten
Geimporteerd in Google Sheets: https://docs.google.com/spreadsheets/d/1uwBa7_rH4DUptOS1uCRCb1MmPjzlzQGnHXK_60nW2VA/edit#gid=0
Op tabblad 2 staat uitgebreide data 
* to-do:Nationale ombudsman
https://www.nationaleombudsman.nl/overheidsinstanties
Nationale Ombudsman heeft een lijst van organisaties. De Nationale ombudsman behandelt klachten over de overheid. Hieronder  vallen bijna alle overheidsorganisaties: ministeries, waterschappen,  provincies, gemeentes, het UWV, de Sociale Verzekeringsbank (SVB) en de  politie. Ook particuliere instellingen met een overheidstaak, zoals de  Stichting Centraal Bureau Rijvaardigheidsbewijzen (CBR), vinden wij een  overheidsorganisatie. 


* to-do: Erfgoedinspectie
https://www.erfgoedinspectie.nl/toezichtvelden/archieven/inhoud/geinspecteerde-instellingen
Erfgoedinspectie heeft een lijst van organisaties met archiefwettelijk toezicht. Dit zijn overheidsorganisaties die vallen onder het toezicht van de Erfgoedinspecties. In het overzicht staat ook een verwijzing naar de wettelijke grondslag. 

* to-do:WNT Register
https://www.topinkomens.nl/voor-wnt-instellingen/inhoud/wnt-register
WNT register wordt door het ministerie van Binnenlandse Zaken en  Koninkrijksrelaties (BZK) bijgehouden om instellingen die onder de WNT (Wet normering topinkomens) vallen te kunnen informeren over deze wet en om toezicht op de naleving  van de WNT te houden. Een instelling of rechtspersoon valt onder de WNT  als één of meer van de criteria uit de WNT van toepassing zijn. De  toepasselijkheid van de WNT vloeit voort uit de wet zelf, nl. de  artikelen 1.2 en 1.3 van de WNT. Als een instelling niet in het register  staat, is het mogelijk dat de WNT toch van toepassing is. Zorginstellingen, onderwijs- en emancipatie-instellingen en  woningcorporaties zijn niet in het register opgenomen, omdat van die  deelsectoren reeds aparte registers worden bijgehouden. Volgens artikel 1.2 van de WNT is de wet van toepassing op alle  krachtens publiekrecht ingestelde instellingen. De wet geeft specifiek aan wie bij het rijk, provincies, gemeenten en waterschappen als topfunctionaris wordt aangemerkt en onder de WNT valt. Voor onder meer zorginstellingen, onderwijsinstellingen, woningcorporaties en organisaties op het gebied van ontwikkelingssamenwerking gelden in de WNT specifieke staffels. Deze zijn vastgesteld in ministeriële regelingen, de zogeheten sectorale bezoldigingsnorm. De WNT-verplichtingen (publicatieplicht en meldingsplicht) gelden  ook voor door de overheid gesubsidieerde instellingen. Tot slot is er  nog een categorie instellingen waarop de WNT van toepassing is die is samengevoegd tot de categorie "overige instellingen". Door de overheid gesubsidieerde instellingen ontvangen een subsidie  van minstens € 500.000,-- per jaar. Deze maakt voor ten minste 50% deel  uit van de inkomsten van dat jaar en wordt voor een periode van  tenminste drie jaar verstrekt. Een voorbeeld hiervan is in veel  gemeenten de openbare bibliotheek. Overige instellingen (1) Instellingen die zijn ingesteld op basis van een wet/met een wettelijke taak (bijvoorbeeld Gemeenschappelijke Regelingen); (2) Zelfstandige bestuursorganen (ZBO ’s); (3) Instellingen waarbij de overheid één of meer leden van het bestuur of raad van toezicht benoemt; (4) Openbare lichamen voor beroep en bedrijf. 
data https://docs.google.com/spreadsheets/d/1MeOoDaDYdACmxDoQtilK-0TGf6sNyMeeiLpsiF6niQU/

*to-do:Website register
https://www.communicatierijk.nl/vakkennis/r/rijkswebsites-verplichte-richtlijnen/inhoud/websiteregister
Websiteregister Rijksoverheid geeft voor *alle* websites van de Rijksoverheid aan welke organisatie én evt. subafdeling eigenaar is.

*to-do:
OIN register geeft een overzicht van alle uitgegeven openbare overheidsidentificatienummers. Elke overheidsorganisatie die digitaal zaken doet kan een uniek Overheidsidentificatienummer (OIN) krijgen. Het OIN is een uniek identificerend nummer dat gebruikt wordt in de digitale communicatie tussen overheden.

https://register.digikoppeling.nl/overview/index

*to-do:
ZBO register - 
https://almanak.zboregister.overheid.nl/

*to-do:
Agentschappen - Rijksoverheid - 
https://www.rijksoverheid.nl/onderwerpen/rijksoverheid/inhoud/agentschappen/lijst-agentschappen

*to-do:
Werken bij de overheid - een site voor vacatures bij de overheid. Hier leveren zo'n 303 overheidsinstellingen vacatures aan. In een vacature staat meestal ook naam en adres van overheidsinstelling. Er is een API http://docs.api.cso20.net/

*to-do:
Mijn overheid - lijst met aangesloten overheidsorganisaties in MijnOverheid - 
https://mijn.overheid.nl/contact/overzicht

*to-do:
Kennisbank Openbaar Bestuur - informatie over sociale zekerheid en werknemers bij overheid, openbaar bestuur, veiligheid en onderwijs. Tabellen zijn uit te klappen vaak onderverdeeld per provincie en gemeente. In ieder geval komt hiermee de naam van de gemeente/plaats bij de organisatie. 
http://kennisopenbaarbestuur.nl:80/tnglite/olap?guid=1e8a0ab4-f368-4d5e-9cff-348a294f0634

*to-do:Nationaal Archief TOCO / Actorenregister
http://www.gahetna.nl/over-ons/open-data/archiefinventarissen-en-scans-archieven#TOCO
arjan: Datadump hier: https://www.gahetna.nl/sites/default/files/bijlagen/ibro_dump_13012015.sql_.zip

arjan: Database online te raadplegen hier: https://ibro.nationaalarchief.nl/

https://ibro.nationaalarchief.nl/index.php/;actor/browse?page=2&type=suborganisation&limit=1000
https://ibro.nationaalarchief.nl/index.php/directie-consulaire-zaken-en-visumbeleid;isaar
https://ibro.nationaalarchief.nl/index.php/ministerie-van-buitenlandse-zaken;isaar
https://ibro.nationaalarchief.nl/index.php/ministerie-van-buitenlandse-zaken;eac?sf_format=xml

*to-do:CBS

 (namen van gemeenten, cbs unieke identifieer van gemeenten uit Openspending)
- kan sicco of breyten een voorbeeld copy pasten van wat er in de CBS datafeed zit? ' bv. Gemeente Amsterdam id:ams0001
- tzt. link naar gdrive doc
- natuurlijk ook voor GRA
- lijst van gemeenten in CBS : http://dataderden.cbs.nl/ODataApi/OData/45006NED/Gemeenten
Key: "GM0363 ",Title: "Amsterdam",Description: "Begindatum voor 1830"
Key: "GM1728 ", Title: "Bladel", Description: "Ontstaan per 01-01-1997"
Key: "GM0063 ",Title: "het Bildt",Description: "Begindatum voor 1830", 
Key: "GM0310 ",Title: "De Bilt", Description: "Gemeentelijke herindeling per 01-01-2001 Begindatum voor 1830"

*to-do:Rijks almanak
- https://almanak.overheid.nl/25698/Gemeente_Amsterdam/ 
- https://almanak.overheid.nl/28270/Gemeente_Amstelveen/
https://almanak.overheid.nl/68820/Ministerie_van_Financi%C3%ABn/ 
https://almanak.overheid.nl/68821/Financi%C3%ABn/Algemene_Leiding/
https://almanak.overheid.nl/68888/Financi%C3%ABn/Directoraat-generaal_Belastingdienst/
bevat: 
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
Linkt naar https://www.rechtspraak.nl/ , bevat https://www.rechtspraak.nl/Organisatie-en-contact/Organisatie/Landelijke-diensten, maar ook gerechtshoven. juridische opbouw onduidelijk
Politie en brandweer
Caribisch Nederland (BES-eilanden)
Aruba, Curaçao en Sint Maarten


*to-do:Staatsdeelnemingen
LINK NEEDED

*to-do:Kadaster
als bron om overheden te identificeren
https://bagviewer.kadaster.nl/lvbag/bag-viewer/index.html#?searchQuery=Amstel%201,%20Amsterdam&objectId=0363010011872295&geometry.x=121833&geometry.y=486756&zoomlevel=13&detailsObjectId=0363010011872295
Bronhouder ID 0363 Naam Amsterdam (dus elk huis in Amsterdam komt van bronhouder Amsterdam met ID 0363 (!!!)

*to-do:Kvk
als bron om overheden (en vestigingen / organisatie onderdelen, enz)  te identificeren?
https://www.kvk.nl/orderstraat/bedrijf-kiezen/?q=gemeente+Amsterdam#!shop?&q=gemeente%20Amsterdam&start=0&prefproduct=&prefpayment=, Stadhuis , Hoofdvestiging, KvK 34366966 , Vestigingsnr. 000007678460 , Amstel 1 ,Amsterdam, http://www.amsterdam.nl
Stadsarchief Amsterdam, KvK 34366966, Vestigingsnr. 000007679092, Vijzelstraat 32, Amsterdam, Nevenvestiging, http://www.stadsarchief.amsterdam.nl
https://www.kvk.nl/orderstraat/bedrijf-kiezen/?q=gemeente+Amsterdam#!shop?&q=gemeente%20Amstelveen&start=0&prefproduct=&prefpayment= , Raadhuis Amstelveen, Hoofdvestiging, KvK 34365024, Vestigingsnr. 000015305678, Laan Nieuwer-Amstel 1, Amstelveen, http://www.amstelveen.nl

*to-do: TENDERNED (ARJAN)

TEMPORARY NOTES
- deze bron gebruiken we om via openkvk / christoph van kempen KvK nummers van alle bovengenoemde organisaties op te halen
- onderliggende bv's.Concern relaties ? (Stefan scraapte die vroeger)
- Lex / Arjan stuur groslijst (github link) van overheden naar Christoph / Stefan met de vraag om deze informatie te combineren
Formaat:
- KISS. CSV , eventueel op Github zodat er gepushed kan worden ;-)
- 1 tabel voor alle organisaties cq. bedrijven. Met unieke identifiers en eventueel een URL naar een formele bron houder + doel organisatie
Naam:' gemeente Amsterdam' , CBSID: Ams001, CBSURL:' http' , ToCo: 020010, toCoURL:' http' , RijksAlmak: 'rijks id=978654' , RijksalamankURL:'http' ,kvk: 8654332, KVKURL: ' ' 
Naam:' stadsregio Amsterdam' , CBSID: null, ToCo: null, RijksAlmak: ' null' , ' rijks id=null' , kvk: 8654332
- 1 tabel voor relaties tussen organisaties; een relatie moet opwaarts of neerwaarts zijn (ivm preventie van loops)
'stadsregio Amsterdam' , 'GRA VAN' , 'gemeente Amsterdam' 
'stadsregio Amsterdam' , 'GRA VAN' , 'gemeente Amstelveen' 
'stadsregio Amsterdam' , 'GRA VAN' , 'gemeente Bijlmer' 
(hopelijk komt er 1 identifier voor alles , anders maken we een OSF id)
Relatie typen: GRA, Deelneming, Nevenvestiging, 'Rijks Almanak organisatie onderdeel,