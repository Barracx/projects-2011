<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');

        function formatRow($thisRow) {
            $formattedRow = array();

            //Get the printer name without the "-printjob number"
            $formattedRow['printerName'] = substr($thisRow[0], 0, strpos($thisRow[0], "-"));
            $formattedRow['documentName'] = substr($thisRow[1], 0, strlen($thisRow[1])-2);
            $formattedRow['user'] = substr($thisRow[2], 0, strlen($thisRow[2])-2);
            $formattedRow['documentSize'] = substr($thisRow[3], 0, strlen($thisRow[3])-2);

            //If the application has failed to count the number of pages then mark it as 1
            if ($thisRow[4] != "Unknown") {
                $formattedRow['pages'] = $thisRow[4];
            } else {
                $formattedRow['pages'] = 1;
            }

            $startLength = strpos($thisRow[5], "at") + 5;
            $stringLength = $startLength - 5;
            $theDate = substr($thisRow[5], $startLength, $stringLength);
            $formattedRow['date'] = $theDate;

            //The time that the print job was submitted
            $startLength = strpos($thisRow[5], "SAST") - 13;
            $stringLength = 12;
            $theTime = substr($thisRow[5], $startLength, $stringLength);
            $formattedRow['time'] = $theTime;

            //The status of the print job
            $theStatus = $theString = substr($thisRow[5], 0, strpos($thisRow[5], " "));
            $formattedRow['status'] = $theStatus;

            //print_r($formattedRow);
            return $formattedRow;
        }

        function writeToDataBase($printData) {
            // Make a MySQL Connection
            mysql_connect("localhost", "root", "root") or die(mysql_error());
            mysql_select_db("printServer") or die(mysql_error());

            //Check if the entries have already been captured
            
            
            // Insert a row of information into the table
            foreach ($printData as $currentJob) {
              //Use variables
              $userName =  $currentJob['user'];
              $pageCount = $currentJob['pages'];
              $jobStatus = $currentJob['status'];
              $documentName = $currentJob['documentName'];
              $printerName = $currentJob['printerName'];
              $printDate = $currentJob['date'];
              $printTime = $currentJob['time'];
              $documentSize = $currentJob['documentSize'];

              mysql_query("INSERT INTO printInfo
              (user, pages, status, documentName, printerName, date, time, documentSize)
              VALUES('$userName', '$pageCount', '$jobStatus', '$documentName', '$printerName', '$printDate',
                      '$printTime', '$documentSize') ")
                        or die(mysql_error());
            }

            return NULL;
        }

        //Using HTTP requests
        $r = new HttpRequest('http://localhost:631/jobs?which_jobs=all', HttpRequest::METH_GET);
        $r->send();

        //echo $r->getResponseBody();

        $htmlContent = $r->getResponseBody();

        $dom = new DOMDocument;
        $dom->loadHTML($htmlContent);
        $data = array();
        $myIndex = 0;
        $lastCall = array();

        $stopString = "CUPS and the CUPS logo";

        foreach ($dom->getElementsByTagName('tr') as $tr) {
            $cells = array();
            foreach ($tr->getElementsByTagName('td') as $td) {
                $cells[] = $td->nodeValue;
            }
            $myIndex++;

            //Only get the data that is about printing
            if ($myIndex > 4) {
                //The status of the print job
                $theStatus = substr($cells[5], 0, strpos($cells[5], " at"));
                //echo $theStatus;
                $completed = "completed";
                if (strpos($cells[5], "completed") != FALSE) { //Only store completed jobs
                    //echo 'status pass';
                    //Do not store the last line which is about CUPS the product
                    if (substr($cells[0], 0, strlen($stopString)) != $stopString) {
                        //echo formatRow($cells);
                        $data[] = formatRow($cells); //$cells;
                    }
                }
            }
        }

        //print_r($data);
        $tester = writeToDataBase($data);
        ?>
    </body>
</html>
