<?php
error_reporting(-1);
$CLI_FLG = FALSE;                                 // CLI mode is change to TRUE.
$username = "Type here you Twitter screenname";   // No Type "@". Ex. @hogehoge is "hogehoge";   
$TmpDir = './tmp/';                               // Directory end "/" required. 
$count = 1;                                       // Count up to get next page. Twitpic side API LIMIT 20.
switch ($CLI_FLG) {
    case FALSE:
        $zip = new ZipArchive();
        $zipFileName = "{$username}_ImageFile.zip";
        $result = $zip->open($TmpDir.$zipFileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
        if ($result !== true) {
            exit("FIle Open Error.");
        }

        $xml = @file_get_contents("http://api.twitpic.com/2/users/show.xml?username={$username}&page{$count}");
        $xmlfile = new SimpleXMLElement($xml);
        foreach ($xmlfile->images->image as $row) {
            $time = strtotime($row->timestamp);
            $data = @file_get_contents("https://d3j5vwomefv46c.cloudfront.net/photos/large/{$row->id}.{$row->type}?{$time}");
            if (!empty($row->message)){
                $zip->addFromString($row->message.".".$row->type ,$data );
            } else {
                $zip->addFromString($time.".". $row->type ,  $data);
            }
        }
        $zip->close();
        header('Content-Type: application/zip; name="' . $zipFileName . '"');
        header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
        header('Content-Length: '.filesize($TmpDir.$zipFileName));
        echo file_get_contents($TmpDir.$zipFileName);
        unlink($TmpDir.$zipFileName);
        exit(0);
        break;

    case TRUE:
        $xml = @file_get_contents("http://api.twitpic.com/2/users/show.xml?username={$username}&page{$count}");
        $xmlfile = new SimpleXMLElement($xml);
        foreach ($xmlfile->images->image as $row) {
            $time = strtotime($row->timestamp);
            $data = @file_get_contents("https://d3j5vwomefv46c.cloudfront.net/photos/large/{$row->id}.{$row->type}?{$time}");
            if (!empty($row->message)){
                $filepointer=fopen($TmpDir.$row->message.".".$row->type, "w");
            } else {
                $filepointer=fopen($TmpDir.$time.".".$row->type, "w");
            }
            flock($filepointer, LOCK_EX);
            fputs($filepointer,$data);
            flock($filepointer, LOCK_UN);
            fclose($filepointer);
        }
        exit(0);
        break;
    default:
        exit('Exception!!');
        break;
}
