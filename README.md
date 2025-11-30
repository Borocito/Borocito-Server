# Borocito-Server
Server-Side things

## Usage
Just download this as a ZIP file and upload it on your server via FTP.  
Then extract the ZIP inside a Borocito/ folder.  

That't it! You should see this file from your domain/ip like https://example.com/Borocito/README.md  

After that, update the URLS on the files for your URL/IP.  
Like in `Globals.ini`:  
```ini
# Borocito Configuration File
[General]
Enabled=True
[Assembly]
Name=Borocito
Version=0.2.3.0
[Binaries]
Borocito=http://borocito.local/Borocitos.zip # CHANGE THIS
[boro-get]
Configuration=http://borocito.local/Boro-Get/config.ini # AND THIS
```
> This is a pain in the ass, but should be done.  
The files are:  
 - Client.ini
 - Globals.ini
 - GlobalSettings.ini
 - Boro-Get:  
    - EVERY .inf FILE...  

_You can use the `url-changer.py` Python script file. It will ask for the directory where to found files and then the URL/IP to replace `borocito.local` with. Read the comments (in spanish) for help_  
