#!/usr/bin/env python
# -*- coding: utf-8 -*-

import csv, codecs, cStringIO
import json
import re

class UTF8Recoder:
    """
    Iterator that reads an encoded stream and reencodes the input to UTF-8
    """
    def __init__(self, f, encoding):
        self.reader = codecs.getreader(encoding)(f)

    def __iter__(self):
        return self

    def next(self):
        return self.reader.next().encode("utf-8")

class UnicodeReader:
    """
    A CSV reader which will iterate over lines in the CSV file "f",
    which is encoded in the given encoding.
    """

    def __init__(self, f, dialect=csv.excel, encoding="utf-8", **kwds):
        f = UTF8Recoder(f, encoding)
        self.reader = csv.reader(f, dialect=dialect, **kwds)

    def next(self):
        row = self.reader.next()
        return [unicode(s, "utf-8") for s in row]

    def __iter__(self):
        return self

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


# For csv's specify delimiter, encoding and the column containing the domains
filenames = [
    'Rijksoverheid.csv',
    'Provincies.csv',
    'Gemeenten.csv',
    'Waterschappen.csv',
    'Gemeenschappelijke-Regelingen.csv'
]

# Mapping for 2012-2017 ministerie names
#rijksoverheid_mapping = {
#    'AR': u'Algemene Rekenkamer',
#    'AZ': u'Ministerie van Algemene Zaken',
#    'BUZA': u'Ministerie van Buitenlandse Zaken',
#    'BZK': u'Ministerie van Binnenlandse Zaken',
#    'DEF': u'Ministerie van Defensie',
#    'EK': u'Eerste Kamer',
#    'EZ': u'Ministerie van Economische Zaken',
#    'FIN': u'Ministerie van Financiën',
#    'HRvA': u'Hoge Raad van Adel',
#    'IenM': u'Ministerie van Infrastructuur en Milieu',
#    'Inspectieraad': u'Inspectieraad',
#    'NO': u'Nationale Ombudsman',
#    'NVAO': u'Nederlands-Vlaamse Accreditatieorganisatie',
#    'OCW': u'Ministerie van Onderwijs, Cultuur en Wetenschap',
#    'RvS': u'Raad van State',
#    'SZW': u'Ministerie van Sociale Zaken en Werkgelegenheid',
#    'TK': u'Tweede Kamer',
#    'VenJ': u'Ministerie van Veiligheid en Justitie',
#    'VWS': u'Ministerie van Volksgezondheid, Welzijn en Sport'
#}

# Mapping for 2017-now ministerie names
rijksoverheid_mapping = {
    'AR': u'Algemene Rekenkamer',
    'AZ': u'Ministerie van Algemene Zaken',
    'BUZA': u'Ministerie van Buitenlandse Zaken',
    'BZK': u'Ministerie van Binnenlandse Zaken',
    'CBS': u'Centraal Bureau voor de Statistiek',
    'DEF': u'Ministerie van Defensie',
    'EK': u'Eerste Kamer',
    'EZK': u'Ministerie van Economische Zaken en Klimaat',
    'FIN': u'Ministerie van Financiën',
    'HRvA': u'Hoge Raad van Adel',
    'IenW': u'ministerie van Infrastructuur en Waterstaat',
    'Inspectieraad': u'Inspectieraad',
    'NO': u'Nationale Ombudsman',
    'NVAO': u'Nederlands-Vlaamse Accreditatieorganisatie',
    'OCW': u'Ministerie van Onderwijs, Cultuur en Wetenschap',
    'RvS': u'Raad van State',
    'SZW': u'Ministerie van Sociale Zaken en Werkgelegenheid',
    'TK': u'Tweede Kamer',
    'JenV': u'Ministerie van Justitie en Veiligheid ',
    'VWS': u'Ministerie van Volksgezondheid, Welzijn en Sport'
}

# Cleanup for ugly gemeenschappelijke regelingen data
remove = [
    'niet aanwezig',
    'niet',
    'nvt',
    'N.v.t.',
    'N.v.t',
    'geen',
    '-',
    '---',
    '0',
    'De Callenburgh 2\n5701 PA\nHelmond'
]
remove_i = [x.upper() for x in remove]

all_domains = []
known_domains = []

def extract_csv(filename):
    domains = []
    with open(filename) as IN:
        domain_type = filename.split('.')[0].replace('-', ' ')
        reader = UnicodeReader(IN)
        for row in reader:
            # Skip empty rows
            if row:
                domain = []
                # Strip whitespace (and some ugly ']"' stuff found in
                # gemeenschappelijke regelingen data)
                url = row[0].strip().strip(']"').strip().rstrip('.')
                # Remove more ugly stuff gemeenschappelijke regelingen
                # data
                if url.upper() in remove_i:
                    continue
                if url:
                    # Remove scheme
                    path = re.sub('https?://', '', url)
                    # Remove path
                    subdomain_name = re.sub('/.*', '', path)
                    # Remove www
                    domain_name = re.sub(
                        r'^www\.(.*\..*)', r'\1', subdomain_name
                    )
                    if domain_name in known_domains:
                        continue
                    domain.append(domain_name)
                    known_domains.append(domain_name)
                else:
                    continue

                if len(row) == 2 and row[1]:
                    if filename == 'Rijksoverheid.csv':
                        domain.append('Rijksoverheid')
                        domain.append(rijksoverheid_mapping[row[1]])
                    
                else:
                    # Add it twice as both the 'Domain Type' and 'Agency'
                    # columns are currently the same
                    domain.append(domain_type)
                    domain.append(domain_type)
                domains.append(domain)

    return domains

for filename in filenames:
    all_domains += extract_csv(filename)

with open('domains.csv', 'w') as OUT:
    writer = UnicodeWriter(OUT)
    writer.writerow(['Domain Name', 'Domain Type', 'Agency'])
    writer.writerows(all_domains)
