# FREEGEO FOR [DOLIBARR ERP CRM](https://www.dolibarr.org)

## Features

This module allows to automatically geocode adresses of thirdparties, contacts and users.

For the moment, it works only in France (because it calls a frech gov API https://api-adresse.data.gouv.fr/search/?)

It creates 3 extrafields (lat, lon, geocaddress) in this 3 objects

When an address is created / updated, trough triggers, the API is called, and the 3 extrafields are (eventually) updated.

(@see function addressGeocode in freegeo.lib.php)

geocaddress is the text address corrected/normalized by the API

### Bonus 1

add a mysql function to calculate an approx distance in km between 2 adresses 

(@see sql/dolibarr_allversions.sql )

### Bonus 2

if you need to geocode existing addresses in yout database, have a look to zdivress/readme.md (in french) who explains how to do this




Other external modules are available on [Dolistore.com](https://www.dolistore.com).


## Licenses

### Main code

GPLv3 or (at your option) any later version. See file COPYING for more information.

### Documentation

All texts and readmes are licensed under GFDL.
