<?php
/* Copyright (C) 2023 Vincent MAURY <vmaury@timgroup.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file    freegeo/lib/freegeo.lib.php
 * \ingroup freegeo
 * \brief   Library files with common functions for Freegeo
 */

/**
 * Prepare admin pages header
 *
 * @return array
 */
function freegeoAdminPrepareHead()
{
	global $langs, $conf;

	$langs->load("freegeo@freegeo");

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/freegeo/admin/setup.php", 1);
	$head[$h][1] = $langs->trans("Settings");
	$head[$h][2] = 'settings';
	$h++;

	/*
	$head[$h][0] = dol_buildpath("/freegeo/admin/myobject_extrafields.php", 1);
	$head[$h][1] = $langs->trans("ExtraFields");
	$head[$h][2] = 'myobject_extrafields';
	$h++;
	*/

	$head[$h][0] = dol_buildpath("/freegeo/admin/about.php", 1);
	$head[$h][1] = $langs->trans("About");
	$head[$h][2] = 'about';
	$h++;

	// Show more tabs from modules
	// Entries must be declared in modules descriptor with line
	//$this->tabs = array(
	//	'entity:+tabname:Title:@freegeo:/freegeo/mypage.php?id=__ID__'
	//); // to add new tab
	//$this->tabs = array(
	//	'entity:-tabname:Title:@freegeo:/freegeo/mypage.php?id=__ID__'
	//); // to remove a tab
	complete_head_from_modules($conf, $langs, null, $head, $h, 'freegeo@freegeo');

	complete_head_from_modules($conf, $langs, null, $head, $h, 'freegeo@freegeo', 'remove');

	return $head;
}

function updateGeo(CommonObject &$object) {
	//print_r($object);
	$resgeo = addressGeocode('', $object->address, $object->zip, $object->town);
	//print_r($resgeo);
//				die();
	if (empty($resgeo->error)) {
		$object->array_options['options_lon'] = $resgeo->lon;
		$object->array_options['options_lat'] = $resgeo->lat;
		$object->array_options['options_geocaddress'] = $resgeo->geocaddress;
		//$r1 = $object->updateExtraField('lon',null,$user);
		$r = $object->insertExtraFields();
		//print_r($object);
		//die("hihi $r $r1");
	}
}
/** geocodage d'une adresse
 * 
 * @param str $bulk adresse en vrac
 * @param str $street
 * @param str $zip
 * @param str $town
 * @param str $country
 * @return class [error, lat, lon, geocaddress]
 */
function addressGeocode($bulk, $street='', $zip='', $town='', $country='') {
	define ('urlApi', 'https://api-adresse.data.gouv.fr/search/?');
	$rep = new stdClass();
	$rep->error = '';
	$country = strtolower($country);
	if ($country == '' || $country == 'fr' || $country == 'france') {
		if (!empty($bulk)) $arg['q'] = $bulk;
		if (!empty($street)) $arg['q'] .= ' '.$street;
		if (!empty($zip)) $arg['q'] .= ' '.$zip;
		if (!empty($town)) $arg['q'] .= ' '.$town;
		if ($zip) $arg['postcode'] = $zip;
		//$arg['type'] = 'point';
		$arg['limit'] = 1;
		foreach ($arg as $k=>$v) $arg[$k] = $k.'='.urlencode ($v);
		$rep->urlcalled = urlApi.implode('&',$arg);
		$repcall = file_get_contents(urlApi.implode('&',$arg));
		$tbrep = json_decode($repcall, true);
		/* print_r($tbrep);	 Array (
    [type] => FeatureCollection
    [version] => draft
    [features] => Array
        (
            [0] => Array
                (
                    [type] => Feature
                    [geometry] => Array
                        (
                            [type] => Point
                            [coordinates] => Array
                                (
                                    [0] => 2.236317
                                    [1] => 48.940037
                                )

                        )

                    [properties] => Array
                        (
                            [label] => 12ter Avenue Jean Jaures 95100 Argenteuil
                            [score] => 0.83427116883117
                            [housenumber] => 12ter
                            [id] => 95018_2800_00012_ter
                            [name] => 12ter Avenue Jean Jaures
                            [postcode] => 95100
                            [citycode] => 95018
                            [x] => 644056.33
                            [y] => 6871388.95
                            [city] => Argenteuil
                            [context] => 95, Val-d'Oise, Île-de-France
                            [type] => housenumber
                            [importance] => 0.81984
                            [street] => Avenue Jean Jaures
                        )

                )

        )

    [attribution] => BAN
    [licence] => ETALAB-2.0
    [query] =>  12 Ter Avenue Jean Jaures 95100 ARGENTEUIL
    [filters] => Array
        (
            [postcode] => 95100
        )

    [limit] => 1
)*/
		if (is_array($tbrep['features']) && count($tbrep['features']) > 0) {
			$feat = $tbrep['features'][0];
			$rep->lon = $feat['geometry']['coordinates'][0];
			$rep->lat = $feat['geometry']['coordinates'][1];
			$rep->geocaddress = $feat['properties']['label'];
		} else $rep->error = 'erreur geocode '.$rep->urlcalled;
	} else {
		$rep->error = 'unupported country';
	}
	return $rep;
}
