<?php

namespace Axen\Sso\Classes;


class Helpers
{
    public function getXgateConfig() {
        $sso = new AxenSso();
        $config = [];
        foreach ($sso->getGlobalConfig()->object() as $key=>$cfg) {
            $config[$key] = (array)$cfg;
        }
        return $config;
    }
    public function getProfissions()
    {
        $sso = new AxenSso();
        $profs = [];
        foreach ($sso->getProfessions()->object() as $prof) {
            $profs[] = (array)$prof;
        }
        return $profs;
    }
    /**
     * Get Prefix options
     */
    function axeecm_prefix_list_values()
    {
        $prefixes = array(
            "Dott." => "Dott.",
            "Dott.ssa" => "Dott.ssa",
            "Sig." => "Sig.",
            "Sig.ra" => "Sig.ra"
        );

        asort($prefixes);

        return $prefixes;
    }
    public function regionlist_array($index = null)
    {
        $regionlist = array(
            "Abruzzo" => "Abruzzo",
            "Basilicata" => "Basilicata",
            "Calabria" => "Calabria",
            "Campania" => "Campania",
            "Emilia-Romagna" => "Emilia Romagna",
            "Friuli-Venezia-Giulia" => "Friuli Venezia Giulia",
            "Lazio" => "Lazio",
            "Liguria" => "Liguria",
            "Lombardia" => "Lombardia",
            "Marche" => "Marche",
            "Molise" => "Molise",
            "Piemonte" => "Piemonte",
            "Puglia" => "Puglia",
            "Sardegna" => "Sardegna",
            "Sicilia" => "Sicilia",
            "Toscana" => "Toscana",
            "Trentino-Alto-Adige" => "Trentino Alto Adige",
            "Umbria" => "Umbria",
            "Valle-d-Aosta" => "Valle d Aosta",
            "Veneto" => "Veneto"
        );

        if (is_null($index)) {
            return $regionlist;
        }

        if (array_key_exists($index, $regionlist)) {
            return $regionlist[$index];
        }

        return $regionlist;
    }

    function get_region_by_city($needle)
    {
        $regionlist = array(
            "Abruzzo" => array('CH', 'AQ', 'PE', 'TE'),
            "Basilicata" => array('MT', 'PZ'),
            "Calabria" => array('CZ', 'CS', 'KR', 'RC', 'VV'),
            "Campania" => array('AV', 'BN', 'CE', 'NA', 'SA'),
            "Emilia-Romagna" => array('BO', 'FE', 'FC', 'MO', 'PR', 'PC', 'RA', 'RE', 'RN'),
            "Friuli-Venezia-Giulia" => array('GO', 'PN', 'TS', 'UD'),
            "Lazio" => array('FR', 'LT', 'RI', 'RM', 'VT'),
            "Liguria" => array('GE', 'IM', 'SP', 'SV'),
            "Lombardia" => array('BG', 'BS', 'CO', 'CR', 'LC', 'LO', 'MN', 'MI', 'MB', 'PV', 'SO', 'VA'),
            "Marche" => array('AN', 'AP', 'FM', 'MC', 'PU'),
            "Molise" => array('CB', 'IS'),
            "Piemonte" => array('AL', 'AT', 'BI', 'CN', 'NO', 'TO', 'VB', 'VC'),
            "Puglia" => array('BA', 'BT', 'BR', 'FG', 'LE', 'TA'),
            "Sardegna" => array('CA', 'CI', 'VS', 'NU', 'OG', 'OT', 'OR', 'SS'),
            "Sicilia" => array('AG', 'CL', 'CT', 'EN', 'ME', 'PA', 'RG', 'SR', 'TP'),
            "Toscana" => array('AR', 'FI', 'GR', 'LI', 'LU', 'MS', 'PI', 'PT', 'PO', 'SI'),
            "Trentino-Alto-Adige" => array('BZ', 'TN'),
            "Umbria" => array('PG', 'TR'),
            "Valle-d-Aosta" => array('AO'),
            "Veneto" => array('BL', 'PD', 'RO', 'TV', 'VE', 'VR', 'VI')
        );
        foreach ($regionlist as $region => $list) {
            if (in_array($needle, $list)) {
                return $this->regionlist_array($region);
            }
        }
        if ($needle == 'Estero') {
            return '(Estero)';
        }

        return false;
    }

    /**
     * Get Regions options
     */
    public function getRegions()
    {
        $regions = array(
            "nd" => "",
            "Abruzzo" => "Abruzzo",
            "Basilicata" => "Basilicata",
            "Calabria" => "Calabria",
            "Campania" => "Campania",
            "Emilia-Romagna" => "Emilia-Romagna",
            "Friuli-Venezia-Giulia" => "Friuli-Venezia Giulia",
            "Lazio" => "Lazio",
            "Liguria" => "Liguria",
            "Lombardia" => "Lombardia",
            "Marche" => "Marche",
            "Molise" => "Molise",
            "Piemonte" => "Piemonte",
            "Puglia" => "Puglia",
            "Sardegna" => "Sardegna",
            "Sicilia" => "Sicilia",
            "Toscana" => "Toscana",
            "Trentino-Alto-Adige" => "Trentino-Alto Adige",
            "Umbria" => "Umbria",
            "Valle-dAosta" => "Valle d'Aosta",
            "Veneto" => "Veneto"
        );

        asort($regions);

        return $regions;
    }

    public function getCities($province)
    {
        $sso = new AxenSso();
        $cities = [];
        foreach ($sso->getCities($province)->object() as $city) {
            $cities[] = (array)$city;
        }
        return $cities;
    }

    public function getCountries()
    {
        $sso = new AxenSso();
        $countries = [];
        foreach ($sso->getCountries()->object() as $country) {
            $countries[] = (array)$country;
        }
        return $countries;
    }
    /**
     * Get City options
     */
    public function getProvinceValues()
    {
        return array(
            "AG" => "Agrigento",
            "AL" => "Alessandria",
            "AN" => "Ancona",
            "AO" => "Valle Aosta/Vallée d&#39;Aoste",
            "AP" => "Ascoli Piceno",
            "AQ" => "L&#39;Aquila",
            "AR" => "Arezzo",
            "AT" => "Asti",
            "AV" => "Avellino",
            "BA" => "Bari",
            "BG" => "Bergamo",
            "BI" => "Biella",
            "BL" => "Belluno",
            "BN" => "Benevento",
            "BO" => "Bologna",
            "BR" => "Brindisi",
            "BS" => "Brescia",
            "BT" => "Barletta-Andria-Trani",
            "BZ" => "Bolzano/Bozen",
            "CA" => "Cagliari",
            "CB" => "Campobasso",
            "CE" => "Caserta",
            "CH" => "Chieti",
            "CI" => "Carbonia-Iglesias",
            "CL" => "Caltanissetta",
            "CN" => "Cuneo",
            "CO" => "Como",
            "CR" => "Cremona",
            "CS" => "Cosenza",
            "CT" => "Catania",
            "CZ" => "Catanzaro",
            "EN" => "Enna",
            "FC" => "Forlì-Cesena",
            "FE" => "Ferrara",
            "FG" => "Foggia",
            "FI" => "Firenze",
            "FM" => "Fermo",
            "FR" => "Frosinone",
            "GE" => "Genova",
            "GO" => "Gorizia",
            "GR" => "Grosseto",
            "IM" => "Imperia",
            "IS" => "Isernia",
            "KR" => "Crotone",
            "LC" => "Lecco",
            "LE" => "Lecce",
            "LI" => "Livorno",
            "LO" => "Lodi",
            "LT" => "Latina",
            "LU" => "Lucca",
            "MB" => "Monza e della Brianza",
            "MC" => "Macerata",
            "ME" => "Messina",
            "MI" => "Milano",
            "MN" => "Mantova",
            "MO" => "Modena",
            "MS" => "Massa-Carrara",
            "MT" => "Matera",
            "NA" => "Napoli",
            "NO" => "Novara",
            "NU" => "Nuoro",
            "OG" => "Ogliastra",
            "OR" => "Oristano",
            "OT" => "Olbia-Tempio",
            "PA" => "Palermo",
            "PC" => "Piacenza",
            "PD" => "Padova",
            "PE" => "Pescara",
            "PG" => "Perugia",
            "PI" => "Pisa",
            "PN" => "Pordenone",
            "PO" => "Prato",
            "PR" => "Parma",
            "PT" => "Pistoia",
            "PU" => "Pesaro e Urbino",
            "PV" => "Pavia",
            "PZ" => "Potenza",
            "RA" => "Ravenna",
            "RC" => "Reggio di Calabria",
            "RE" => "Reggio nell&#39;Emilia",
            "RG" => "Ragusa",
            "RI" => "Rieti",
            "RM" => "Roma",
            "RN" => "Rimini",
            "RO" => "Rovigo",
            "SA" => "Salerno",
            "SI" => "Siena",
            "SO" => "Sondrio",
            "SP" => "La Spezia",
            "SR" => "Siracusa",
            "SS" => "Sassari",
            "SV" => "Savona",
            "TA" => "Taranto",
            "TE" => "Teramo",
            "TN" => "Trento",
            "TO" => "Torino",
            "TP" => "Trapani",
            "TR" => "Terni",
            "TS" => "Trieste",
            "TV" => "Treviso",
            "UD" => "Udine",
            "VA" => "Varese",
            "VB" => "Verbano-Cusio-Ossola",
            "VC" => "Vercelli",
            "VE" => "Venezia",
            "VI" => "Vicenza",
            "VR" => "Verona",
            "VS" => "Medio Campidano",
            "VT" => "Viterbo",
            "VV" => "Vibo Valentia",
        );
    }
}
