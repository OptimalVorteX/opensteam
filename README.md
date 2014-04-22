opensteam
=========

OpenSteam and User Mangement System

This project includes Lua and web application (written in php). Based on ULX MySQL.

Require: 
mysqloo
webserver (mysql with php/PDO enabled)

Features:
- Each player is added to the database
- It is not necessary for a player to be on a server in order to execute a command (for example, you can add/remove/update admin although he is not on the server, playing...same for bans/removing bans)
- Check user from the database (steamID, status etc)
- Temporary bans
- Login via Steam (web)
- Custom loading screen (with caching steam players and random backgrounds)
- Support multiple servers
- GeoIP support (based on GeoIP Locator
- Country Bans
