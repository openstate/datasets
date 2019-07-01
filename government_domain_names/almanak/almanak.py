#!/usr/bin/env python

from datetime import datetime
from lxml import etree
from pprint import pprint

import csv, codecs, cStringIO
import io
import re
import requests

class UnicodeWriter:
    """
    A CSV writer which will write rows to CSV file "f",
    which is encoded in the given encoding.
    """

    def __init__(self, f, dialect=csv.excel, encoding="utf-8", **kwds):
        # Redirect output to a queue
        self.queue = cStringIO.StringIO()
        self.writer = csv.writer(self.queue, dialect=dialect, **kwds)
        self.stream = f
        self.encoder = codecs.getincrementalencoder(encoding)()

    def writerow(self, row):
        self.writer.writerow([s.encode("utf-8") for s in row])
        # Fetch UTF-8 output from the queue ...
        data = self.queue.getvalue()
        data = data.decode("utf-8")
        # ... and reencode it into the target encoding
        data = self.encoder.encode(data)
        # write to the target stream
        self.stream.write(data)
        # empty queue
        self.queue.truncate(0)

    def writerows(self, rows):
        for row in rows:
            self.writerow(row)

# Filename where we will save the downloaded export archive file to
export_filename = 'export-%s.xml' % (datetime.now().isoformat()[:10])

# Download and save the export archive
r = requests.get('https://almanak.overheid.nl/archive/exportOO.xml', verify=False)
with io.open(export_filename, 'w') as OUT:
    OUT.write(r.text)

# Specify the namespace used by the export archive XML
# NOTE: this namespace changes every now and then, so if the output CSVs
# are empty then check if the namespace has changed and update it
#ns = {"p": "http://almanak.overheid.nl/schema/export/2.0"}
#ns = {"p": "https://almanak.overheid.nl/static/schema/oo/export/2.4.1"}
#ns = {"p": "https://almanak.overheid.nl/static/schema/oo/export/2.4.2"}
#ns = {"p": "https://almanak.overheid.nl/static/schema/oo/export/2.4.4"}
#ns = {"p": "https://almanak.overheid.nl/static/schema/oo/export/2.4.5"}
#ns = {"p": "https://almanak.overheid.nl/static/schema/oo/export/2.4.7"}
#ns = {"p": "https://almanak.overheid.nl/static/schema/oo/export/2.4.8"}
#ns = {"p": "https://almanak.overheid.nl/static/schema/oo/export/2.4.9"}
ns = {"p": "https://almanak.overheid.nl/static/schema/oo/export/2.4.10"}

# Load the XML
parser = etree.XMLParser(ns_clean=True)
xml = etree.parse(export_filename, parser)

# Lists where for each organisatie we will add the URL of the
# organisatie and the name of the organisatie
organisaties_list = []
# Lists where for each gemeente we will add the URL of the
# gemeente and the categorie name (which should always be 'Gemeenten')
gemeenten_list = []

# TODO check of er meerdere URLs of organisaties zijn

# Retrieve all URLs (i.e., the `internet` element) from 'organisaties'
# from the XML and add them to organisaties_list
for organisaties in xml.xpath('/p:overheidsorganisaties/p:organisaties', namespaces=ns):
    for internet_el in organisaties.xpath('.//p:internet', namespaces=ns):
        url = internet_el.xpath('./text()')[0]
        organisatie_el = internet_el.xpath('./ancestor::p:organisatie', namespaces=ns)[0]
        organisatie = ''
        if organisatie_el.xpath('./p:categorie/@p:naam', namespaces=ns):
            organisatie = organisatie_el.xpath('./p:categorie/@p:naam', namespaces=ns)[0]
        organisaties_list.append([url, organisatie])

# Retrieve all URLs (i.e., the `internet` element) from 'gemeenten'
# from the XML and add them to gemeenten_list
for gemeenten in xml.xpath('/p:overheidsorganisaties/p:gemeenten', namespaces=ns):
    for internet_el in gemeenten.xpath('.//p:internet', namespaces=ns):
        url = internet_el.xpath('./text()')[0]
        gemeente_el = internet_el.xpath('./ancestor::p:gemeente', namespaces=ns)[0]
        gemeente = ''
        if gemeente_el.xpath('./p:types/p:type/text()', namespaces=ns):
            gemeente = gemeente_el.xpath('./p:types/p:type/text()', namespaces=ns)[0]
        # Skip gemeenten which don't exist anymore (i.e., have an
        # eindDatum)
        if gemeente_el.xpath('./p:eindDatum/text()', namespaces=ns):
            continue
        # There is more stuff than just Gemeenten, so select only the
        # items matching 'Gemeenten'
        if gemeente == 'Gemeente':
            gemeenten_list.append([url, gemeente])

# Save organisaties_list to a csv
with open('almanak-organisaties-%s.csv' % (datetime.now().isoformat()[:19]), 'w') as OUT:
    writer = UnicodeWriter(OUT)
    for organisatie in organisaties_list:
        writer.writerow(organisatie)

# Save gemeenten_list to a csv
with open('almanak-gemeenten-%s.csv' % (datetime.now().isoformat()[:19]), 'w') as OUT:
    writer = UnicodeWriter(OUT)
    for gemeente in gemeenten_list:
        writer.writerow(gemeente)

# Save only unique and cleaned up URLs of gemeenten_list to a csv
with open('almanak-gemeenten-unique-%s.csv' % (datetime.now().isoformat()[:19]), 'w') as OUT:
    writer = UnicodeWriter(OUT)
    gemeente_dict = {}
    # Remove duplicates
    for gemeente in gemeenten_list:
        gemeente_dict[gemeente[0]] = gemeente
    # Cleanup (e.g., some entries contain ugly stuff like multiple URLs
    # separated by a space or ';') and save
    for gemeente_url in sorted(gemeente_dict):
        # Remove protocol ('http' or 'https') from URL
        gemeente_url = re.sub(r'^https?://', '', gemeente_url)
        # Remove everything following a space from the URL
        gemeente_url = re.sub(r' .*$', '', gemeente_url)
        # Remove everything following a ';' from the URL
        gemeente_url = re.sub(r';.*$', '', gemeente_url)
        # Remove everything following a '/' (i.e., URL paths) from the
        # URL
        gemeente_url = re.sub(r'/.*$', '', gemeente_url)

        writer.writerow([gemeente_url])
