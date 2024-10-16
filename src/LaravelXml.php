<?php

namespace PhpJunior\LaravelXml;

use DOMDocument;
use SimpleXMLElement;

class LaravelXml
{
    /**
     * @param array $data
     * @param string $rootElement
     * @param array|string $cdataKeys
     * @return string
     */
    public static function transformArrayToXml(array $data, string $rootElement = 'root', array|string $cdataKeys = []): string
    {
        $cdataKeys = (array) $cdataKeys;
        $xmlData = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><$rootElement></$rootElement>");
        self::arrayToXml($data, $xmlData, $cdataKeys);

        // Format the XML with line breaks and indentation
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlData->asXML());

        return $dom->saveXML();
    }

    /**
     * @param string $xml
     * @return array
     */
    public static function transformXmlToArray(string $xml): array
    {
        $xmlData = simplexml_load_string($xml);
        return json_decode(json_encode($xmlData), true);
    }

    /**
     * @param array $data
     * @param SimpleXMLElement $xmlData
     * @param array $cdataKeys
     * @return void
     */
    private static function arrayToXml(array $data, SimpleXMLElement $xmlData, array $cdataKeys): void
    {
        foreach ($data as $key => $value) {
            $key = is_numeric($key) ? 'item' . $key : $key;

            if (is_array($value)) {
                self::handleArrayValue($key, $value, $xmlData, $cdataKeys);
            } else {
                if (self::shouldWrapInCdata($key, $cdataKeys)) {
                    $child = $xmlData->addChild($key);
                    self::addCData($child, $value);
                } else {
                    $xmlData->addChild($key, htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8'));
                }
            }
        }
    }

    /**
     * @param string $key
     * @param array $value
     * @param SimpleXMLElement $xmlData
     * @param array $cdataKeys
     * @return void
     */
    private static function handleArrayValue(string $key, array $value, SimpleXMLElement $xmlData, array $cdataKeys): void
    {
        if (self::isNumericArray($value)) {
            foreach ($value as $subValue) {
                $subNode = $xmlData->addChild($key);
                self::arrayToXml($subValue, $subNode, $cdataKeys);
            }
        } else {
            $subNode = $xmlData->addChild($key);
            self::arrayToXml($value, $subNode, $cdataKeys);
        }
    }

    /**
     * @param array $array
     * @return bool
     */
    private static function isNumericArray(array $array): bool
    {
        return array_keys($array) === range(0, count($array) - 1);
    }

    /**
     * @param string $key
     * @param array $cdataKeys
     * @return bool
     */
    private static function shouldWrapInCdata(string $key, array $cdataKeys): bool
    {
        foreach ($cdataKeys as $pattern) {
            if (fnmatch($pattern, $key)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param SimpleXMLElement $element
     * @param string $cdataText
     * @return void
     */
    private static function addCData(SimpleXMLElement $element, string $cdataText): void
    {
        $node = dom_import_simplexml($element);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdataText));
    }
}