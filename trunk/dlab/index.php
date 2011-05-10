<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
    <html><body>
            <h4>Print server first try</h4>
            <?php
            $file = fopen("/var/log/cups/page_log", "r") or exit("Unable to open file!");

            $beginReading = TRUE;
            $lines = 0;
            $printJob = NULL;
            //Output a line of the file until the end is reached
            while (!feof($file)) {
                $thisLine = fgets($file);
                //echo $thisLine . "<br />";
                if ($beginReading) {
                    $tempPrintJob = explode(" ", $thisLine);
                }
                if (strpos($thisLine, "Name") && strpos($thisLine, "User") && strpos($thisLine, "Name") && strpos($thisLine, "Size") && strpos($thisLine, "Pages") && strpos($thisLine, "Control")) {
                    $beginReading = TRUE;
                }
                $printJob[$lines] = $tempPrintJob;
                $lines++;
            }
            fclose($file);
            //print_r($printJob);

            $permanentPrintJob[0][1] = NULL;
            $previousLine = 0;
            $col = 0;
            $row = -1;
            //Remove repeat entries from the array
            foreach ($printJob as $currentLine) {
                if ($permanentPrintJob[$row][1] != $currentLine[1]) {
                    $row++;
                    $permanentPrintJob[$row] = $currentLine;
                } else {
                    $permanentPrintJob[$row] = $currentLine;
                }
            }
            //print_r($permanentPrintJob);

            // Make a MySQL Connection
            mysql_connect("localhost", "root", "root") or die(mysql_error());
            mysql_select_db("printServer") or die(mysql_error());

            //Check if the entries have already been captured


            
            // Insert a row of information into the table
           /* foreach ($permanentPrintJob as $currentJob) {
                mysql_query("INSERT INTO pageLog
              (userName, pageCount, copies, printTime, printerName, hostIP) VALUES('$currentJob[2]', '$currentJob[5]', '$currentJob[5]', '$currentJob[3]', '$currentJob[0]', '$currentJob[7]') ")
                        or die(mysql_error());
            }*/


            echo "Data Inserted!";

            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
//            //Using HTTP requests
//            $r = new HttpRequest('http://localhost:631/jobs?which_jobs=all', HttpRequest::METH_GET);
//            $r->send();
//            echo $r->getResponseBody(); // this will display the page of www.google.com
//            ?>

            <form action="process.php" method="post">
                <select name="item">
                    <option>Check users printer usage</option>
                    <option>Read from database</option>
                    <option>Say hi to the Ntsakzin</option>
                </select>
                Quantity: <input type="text" name="quantity"/>
                <input type="submit" value="theXunayinayi"/>
            </form>
        </body></html>
<?php
            //echo "You are here";
?>
</body>
</html>

