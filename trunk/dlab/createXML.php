<?php

//Read from the database
if (!$dbconnect = mysql_connect('localhost', 'root', 'root')) {
    echo "Connection failed to the host 'localhost'.";
    exit;
} // if
if (!mysql_select_db('printServer')) {
    echo "Cannot connect to database 'test'";
    exit;
} // if



$table_id = 'pageLog';
$query = "SELECT * FROM $table_id";
$dbresult = mysql_query($query, $dbconnect);
// create a new XML document
$doc = new DomDocument('1.0', 'UTF-8');

// create root node
$root = $doc->createElement('root');
$root = $doc->appendChild($root);

// process one row at a time
while ($row = mysql_fetch_assoc($dbresult)) {
    // add node for each row
    $occ = $doc->createElement($table_id);
    $occ = $root->appendChild($occ);

    // add a child node for each field
    foreach ($row as $fieldname => $fieldvalue) {
        $child = $doc->createElement($fieldname);
        $child = $occ->appendChild($child);
        $fieldvalue = mb_convert_encoding($fieldvalue, 'UTF-8', 'ISO-8859-1'); //<<-- new line
        $value = $doc->createTextNode($fieldvalue);
        $value = $child->appendChild($value);
    }
}

// get completed xml document
$xml_string = $doc->saveXML();

$filenamepath .= "/home/support/Documents/printserver/printserver.xml";
$fp = fopen($filenamepath, 'w');
$write = fwrite($fp, $xml_string);

echo $xml_string;
?> 