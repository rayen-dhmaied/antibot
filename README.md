# Antibot
Restrict clients (mostly bots) from accessing your web pages using specific IPs, hostnames and useragents.

## How to use

Add IP address or IP range that you want to block in remote_address.txt (For example: 192.168.1.1 , 192.168 will block every ip address that start with 192.168).

Add hostname (or a part of it) that you want to block in hostname.txt (For example: amazonaws will block all clients that have Amazon web services hostname).

Add useragent (or a part of it) that you want to block in useragent.txt (For example: Linux x86_64 will block all Clients that use or pretend to use Linux, Chrome/7 will block all clients using chrome version 7*).
## Execution
Use this in case you don't know how to use .htaccess or it doesn't work on your apache server.

require_once 'antibot.php' at the first of every web page you want to protect.

After each block operation a log of the blocked ip will be added to Blocked_IPs.txt

PS: Make sure the file is in the same directory as the web page or put the right path after require_once.
