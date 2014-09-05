<?php 

    $zip = new ZipArchive();
    $username = "Type here you Twitter screenname";
    $zipTmpDir = './tmp';
    $zipFileName = "{$username}_ImageFile.zip";
    $result = $zip->open($zipTmpDir.$zipFileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
    if ($result !== true) {
        exit("FIle Open Error.");
    }
    $count = 1;
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
    header('Content-Length: '.filesize($zipTmpDir.$zipFileName));
    echo file_get_contents($zipTmpDir.$zipFileName);
    unlink($zipTmpDir.$zipFileName);
    exit(0);

