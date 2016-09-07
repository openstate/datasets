#Werkenbijdeoverheid.nl

Heeft een lijst met alle deelnemende overheidsorganisaties op deze vacaturesite. 

API: http://docs.api.cso20.net/

Je kunt gewoon die informatie makkelijk opvragen met de volgende zaken:

curl --request POST --header "Content-Type:application/json" --data '{"username":"werkenvoornederland", "password":"wvnl"}' https://sandbox.api.cso20.net/v1/JobAPI/getApiKey.json

{"result":"7c3e62d6-3e3c-4df4-8944-ebe36cf95a3f"}

curl --request POST --header "Content-Type:application/json" --data '{"apiKey":"7c3e62d6-3e3c-4df4-8944-ebe36cf95a3f"}' https://sandbox.api.cso20.net/v1/JobAPI/getOrganisations.json

###getOrganisation

{
    "result": [{
            "__type__": "fly.cso.api.v1.data.job.RemoteOrganisation",
            "name": "Academic Transfer",
            "code": "00300",
            "abbreviation": "AT",
			"slug": "academictransfer",
            "HPKdays": 0
        }, {
            "__type__": "fly.cso.api.v1.data.job.RemoteOrganisation",
            "name": "Algemene Rekenkamer",
            "code": "01020",
            "abbreviation": "AR",
			"slug": "algemene-rekenkamer",
            "HPKdays": 5        
        },
        ...
   ]
}
