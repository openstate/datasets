# EU Whoiswho
A dataset of all 45283 functions listed on the [EU Whoiswho website](http://europa.eu/whoiswho/public/). Available in CSV and JSON. Explanation of some of the columns/fields:
- hierarchy: shows to which department a person belongs and how this department is structured within other EU institutions (stored as a list in `data.json` and stored as a single field with the items separated by '|' in `data.csv`)
- telephone: shows the telephone number(s) of a person (stored as a list in `data.json` and stored as a single field with the items separated by a comma in `data.csv`)
- source: the URL for the EU Whoiswho page describing this person

Check out this [TransparencyCamp Europe datablog](https://transparencycamp.eu/2016/04/13/who-is-who-in-eu-institutions/) for more info on the data.

If you want to run the scraper yourself, then get the code from [this repository](https://github.com/openstate/EU_whoiswho_scraper).
