<?php

use Cpapdotcom\Asendia\Manifest;

require_once __DIR__.'/bootstrap.php';

it('serializes data using the Manifest facade.', test_xml_serialization_using_manifest_facade());
it('serializes data using an array of properties.', test_xml_serialization_using_array_input());

function test_xml_serialization_using_manifest_facade()
{
    $xml_fixture_filename = 'manifest/Your_Company_Name_2011-02-20_1.xml';
    $expected_xml = get_normalized_xml_fixture_as_string($xml_fixture_filename);

    $manifest = get_manifest_from_facade();
    $manifest_xml_element = Manifest::createXmlFromManifest($manifest);
    $manifest_xml = pretty_print_simple_xml_element($manifest_xml_element);

    return assert($expected_xml === $manifest_xml, 'xml mismatch');
}

function test_xml_serialization_using_array_input()
{
    $xml_fixture_filename = 'manifest/Your_Company_Name_2011-02-20_1.xml';
    $expected_xml = get_normalized_xml_fixture_as_string($xml_fixture_filename);

    $manifest_xml = pretty_print_simple_xml_element(Manifest::createXmlFromProperties(get_manifest_from_properties()));

    return assert($expected_xml === $manifest_xml, 'xml mismatch');
}

