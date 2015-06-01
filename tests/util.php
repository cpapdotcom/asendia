<?php

use Cpap\Asendia\WebApiClient\Adapter\Soap\SoapAsendiaWebApiClient;

function get_fixture_path($path)
{
    return sprintf(__DIR__.'/../resources/fixtures/%s', $path);
}

function get_normalized_xml_fixture_as_string($path)
{
    $xml_filename = get_fixture_path('manifest/Your_Company_Name_2011-02-20_1.xml');
    $xml_string = file_get_contents($xml_filename);

    return pretty_print_xml($xml_string);
}

function pretty_print_simple_xml_element(SimpleXMLElement $xml_element)
{
    return pretty_print_xml($xml_element->asXML());
}

function pretty_print_xml($xml)
{
    $xml_element = simplexml_load_string($xml);
    $dom = dom_import_simplexml($xml_element)->ownerDocument;
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $pretty_xml = $dom->saveXML();

    return $pretty_xml;
}

function get_asendia_wsdl_client($login, $password)
{
    return new DummyAsendiaWsdlClient($login, $password);
}

function get_asendia_web_api_client($login, $password, $configure_wsdl_client = null)
{
    $configure_wsdl_client = $configure_wsdl_client ?: function ($client) { return $client; };
    $asendia_wsdl_client = $configure_wsdl_client(get_asendia_wsdl_client($login, $password));

    return new SoapAsendiaWebApiClient($asendia_wsdl_client, $login, $password);
}
