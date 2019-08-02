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
r = requests.get('https://almanak.overheid.nl/archive/exportOO_gemeenschappelijke_regelingen.xml', verify=False)
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
#ns = {"p": "https://almanak.overheid.nl/static/schema/oo/export/2.4.10"}
ns = {"p": "https://almanak.overheid.nl/static/schema/oo/export/2.4.11"}

# Load the XML
parser = etree.XMLParser(ns_clean=True)
xml = etree.parse(export_filename, parser)

# Lists where for each organisatie we will add the URL of the
# organisatie and the name of the organisatie
organisaties_list = []

# TODO check of er meerdere URLs of organisaties zijn

# Retrieve all URLs (i.e., the `internet` element) from 'organisaties'
# from the XML and add them to organisaties_list
for organisaties in xml.xpath('/p:overheidsorganisaties/p:gemeenschappelijkeRegelingen', namespaces=ns):
    for internet_el in organisaties.xpath('.//p:internet', namespaces=ns):
        url = internet_el.xpath('./text()')[0]
        organisatie_el = internet_el.xpath('./ancestor::p:gemeenschappelijkeRegeling', namespaces=ns)[0]
        organisatie = ''
        organisatie = organisatie_el.xpath('./p:titel/text()', namespaces=ns)[0]
        # Skip gemeenschappelijke regelingen which don't exist anymore
        # (i.e., have an eindDatum)
        if organisatie_el.xpath('.//p:eindDatum/text()', namespaces=ns):
            continue
        organisaties_list.append([url, organisatie])

# Save organisaties_list to a csv
with open('almanak-organisaties-%s.csv' % (datetime.now().isoformat()[:19]), 'w') as OUT:
    writer = UnicodeWriter(OUT)
    for organisatie in organisaties_list:
        writer.writerow(organisatie)

# Save only unique and cleaned up URLs of gemeenschappelijke regelingen
# to a csv
with open('almanak-gemeenschappelijke-regelingen-unique-%s.csv' % (datetime.now().isoformat()[:19]), 'w') as OUT:
    writer = UnicodeWriter(OUT)
    gemeenschappelijke_regeling_dict = {}
    # Remove duplicates
    for gemeenschappelijke_regeling in organisaties_list:
        gemeenschappelijke_regeling_dict[gemeenschappelijke_regeling[0]] = gemeenschappelijke_regeling
    # Cleanup (e.g., some entries contain ugly stuff like multiple URLs
    # separated by a space or ';') and save
    for gemeenschappelijke_regeling_url in sorted(gemeenschappelijke_regeling_dict):
        # Remove protocol ('http' or 'https') from URL
        gemeenschappelijke_regeling_url = re.sub(r'^https?://', '', gemeenschappelijke_regeling_url)
        # Remove everything following a space from the URL
        gemeenschappelijke_regeling_url = re.sub(r' .*$', '', gemeenschappelijke_regeling_url)
        # Remove everything following a ';' from the URL
        gemeenschappelijke_regeling_url = re.sub(r';.*$', '', gemeenschappelijke_regeling_url)
        # Remove everything following a '/' (i.e., URL paths) from the
        # URL
        gemeenschappelijke_regeling_url = re.sub(r'/.*$', '', gemeenschappelijke_regeling_url)

        writer.writerow([gemeenschappelijke_regeling_url])
