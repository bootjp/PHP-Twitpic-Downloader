PHP-Twitpic-Downloader
======================

Twitpic Downloader.
  
Use some unofficial API.
    
PHP Version 5.3 Over.
  
Use ZIP, XML.

  --Check--  
```shell
# php -i | grep xml  
Simplexml support => enabled    
OK  
# php -i | grep zip  
zip  
Libzip version => 0.10.1  
OK  
```
$CLI_FLG    CLI mode is change to TRUE.
  
$username   Type you Twitter screenname. No Type "@". Ex. @hogehoge is "hogehoge"; 
  
$TmpDir     Temporary file directory. Permission 777  Directory end "/" required. 
  
$count      Count up to get next page. Twitpic side API LIMIT 20.
  
