#Actorenregister Nationaal Archief

De data bevinden zich in de database van het Actorenregister:

http://actorenregister.nationaalarchief.nl 

De data is (extern) aan te roepen en te harvesten via een OAI-PMH endpoint:

https://actorenregister.nationaalarchief.nl/oai-pmh?

Via de call:

https://actorenregister.nationaalarchief.nl/oai-pmh?verb=ListMetadataFormats

Geeft aan dat het <metadataformat> EAC-CPF is

Met die prefix kunnen de individuele records aangeroepen worden: 


https://actorenregister.nationaalarchief.nl/oai-pmh?verb=ListRecords&metadataPrefix=eac-cpf

Deze komen er vervolgens in XML uit, per keer met 100 records. Elke output eindigt met een resumptiontoken waarmee de volgende 100 records opgevraagd kunnen worden. Die call ziet er als volgt uit:


Vervolgens:  https://actorenregister.nationaalarchief.nl/oai-pmh?verb=ListRecords&resumptionToken

