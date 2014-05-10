if not ULib then Msg( "\n ** ULX MySQL REQUIRES ULib v2.4+ .. Aborting\n\n") return end

local MySQLOO = require( 'mysqloo' )
require( 'mysqloo' )

Msg( "**Loading OpenSteam Setup**\n")

include('os_config.lua')

ULXDB = mysqloo.connect(ULX_HOST, ULX_USERNAME, ULX_PASSWORD, ULX_DATABASE, ULX_PORT)

if ( GEOIP.EnableGeoIP == 1 ) then 
hook.Add("Initialize", "LoadBannedCountries", function()
   		local queryQ = ULXDB:query("SELECT `config_value` FROM `"..ULX_CONFIG_TABLE.."` WHERE `config_name` = 'country_bans' ;")
		queryQ.onData = function(Q,D)
		  queryQ.onSuccess = function(q)
            local c = 0
			str = D.config_value
			Msg( "[GeoIP] Banned Countries: "..tostring(str).."\n" )
            for v in str:gmatch("[^,]+") do
              GEOIP.BannedCountries[c] = v
		      c = c+1
            end
		  end
	    end
	    queryQ.onError = function(Q,E) print("'country_bans' threw an error:") print(E) end
	    queryQ:start()
end)

hook.Add("Initialize", "LoadGeoIPDatabase", function()
		started = os.clock()
		Msg("[GeoIP] Loading GeoIP Database..")
		geolist = {}

		local geo = file.Read("GeoIPCountryWhois.csv")
		
		if geo == nil then
			print("[GeoIP] ERROR: GeoIPCountryWhois.csv not found!")
			GEOIP.NoTxt = true
			return
		end
		
		local geo = string.Explode("\n", geo)
		local lim = table.Count(geo)
		local exp = string.Explode
		local cmm = ","
		
		prcnt = math.floor(lim / 100)

		local function SetG(i)
			return exp(cmm, geo[i]) -- so it won't struggle with infinite loop errors.
		end
		
		for i=1, lim do
			geolist[i] = SetG(i)
			if i == prcnt then
				Msg(".") prcnt = prcnt + prcnt
			end
		end
		
		Msg("Done!\n[GeoIP] Loaded in "..os.clock() - started.."\n")
end)
	
	
	hook.Add("PlayerConnect", "PlayerIPCheck", function(name, address)
		if GEOIP.NoTxt then
			--GEOIP.InformAll("Player "..name.." has joined the game")
			return
		end
		
		local ip = string.sub(address,1,string.find(address,":") - 1)
		local ip = string.Explode(".",ip)
		local decimal = (ip[1]*16777216) + (ip[2]*65536) + (ip[3]*256) + ip[4]
		
		-- Localizing makes it faster.
		local tonum = tonumber
		local sub = string.sub
		local len = string.len
		
		local function glist(b, n)
			return geolist[b][n]
		end
		
		local function between(b)
			return decimal >= tonum(sub(glist(b,3),2,tonum(len(glist(b,3))-1))) && decimal <= tonum(sub(glist(b,4),2,tonum(len(glist(b,4))-1)))
		end
		
		for k in pairs(geolist) do
			if between(k) then
				from  = sub(glist(k,6),2,len(glist(k,6))-1)
				code  = ""
				if from != nil then
				    code  = ""..sub(glist(k,5),2,len(glist(k,5))-1)..""
					local BanCount = table.Count(GEOIP.BannedCountries)
					local ShowMessage = 1
					   for i=0, BanCount do
					     if ( GEOIP.BannedCountries[i] == code ) then
						     RunConsoleCommand( "kick", name )
							 print("[GeoIP] Kicking "..name..". Country ban ["..from.."] " )
							 ShowMessage = 0
						 end
					   end
					if ShowMessage == 1 then
					print("[GeoIP] Player "..name.." has joined from "..from.." ("..code..")" )
					end
					GEOIP.PlayerIP[ip] = from
				end
				
				break -- no need for further check.
			end
		end
		
		if from == nil then 
			print("[GeoIP] Player "..name.." has joined the game")
			GEOIP.PlayerIP[ip] = "N/A"
		end
		
		from = nil
		
		return
	end)
	
	hook.Add("PlayerInitialSpawn", "GEOIP.PLinit", function(pl)
		
		pl.From = GEOIP.PlayerIP[string.sub(pl:IPAddress(),1,string.find(pl:IPAddress(),":") - 1)]
		
	end)

end	 -- GeoIP enabled/disabled

local function ConnectDB()
	ULXDB = mysqloo.connect(ULX_HOST, ULX_USERNAME, ULX_PASSWORD, ULX_DATABASE, ULX_PORT)
	ULXDB:connect()

	function ULXDB:onConnectionFailed( err )
		Msg("Database Connection Error: "..err.."\n")
	end

	function ULXDB:onConnected()
		Msg("Connected to ULX OpenSteam database\n")
	end
end

hook.Add("PlayerConnect", "PlayerIPBanCheck", function(name, address)
   local player_ip = string.sub(address,1,string.find(address,":") - 1)
   local queryQ = ULXDB:query("SELECT * FROM `"..ULX_BANS_TABLE.."` WHERE `ip` LIKE ('"..ULXDB:escape(player_ip).."%') AND (`expire`>NOW() OR `expire` = '0000-00-00 00:00:00' ) ;")
	queryQ.onData = function(Q,D)
		queryQ.onSuccess = function(q)
		    local reason = tostring(D.reason)
			local expires = tostring(D.expire)
			local ip = tostring(D.ip);
			if (expires == "0000-00-00 00:00:00" ) then expires = '[permanent]' end
			RunConsoleCommand( "kick", name )
			Msg("Kicking "..name.." [BANNED] IP: "..ip.."\n")
			load = true
		end
	end
	queryQ.onError = function(Q,E) print("'CheckUserBan' threw an error:") print(E) end
	queryQ:start()
end)

local function CheckUserBan( ply, stid, unid )
	local load = false
	local player_ip = string.sub(ply:IPAddress(),1,string.find(ply:IPAddress(),":") - 1)
	local queryQ = ULXDB:query("SELECT * FROM `"..ULX_BANS_TABLE.."` WHERE `steam`='"..ULXDB:escape(ply:SteamID()).."' AND (`expire`>NOW() OR `expire` = '0000-00-00 00:00:00') ;")
	queryQ.onData = function(Q,D)
		queryQ.onSuccess = function(q)
		    local reason = tostring(D.reason)
			local expires = tostring(D.expire)
			if (expires == "0000-00-00 00:00:00" ) then expires = '[permanent]' end
			--RunConsoleCommand( "kick", ply:GetName() )
			ply:Kick( "You have been banned! Reason: "..reason.." Expire: " ..expires.."" )
			Msg("Kicking "..ply:Name().." ("..stid.." - "..unid..") [BANNED]\n")
			load = true
		end
	end
	queryQ.onError = function(Q,E) print("'CheckUserBan' threw an error:") print(E) end
	queryQ:start()
end

local function DBBanUser(admin_user, steam, player_name, banTime)

		--ULib.tsayError( calling_ply, "Invalid IP.", true )
end

local function GetPlayerFromDB(player)
	local load = false
	local userInfo = ULib.ucl.authed[ player:UniqueID() ]
	local id = ULib.ucl.getUserRegisteredID( player )
	if not id then id = player:SteamID() end
	local user_ip = string.sub(player:IPAddress(),1,string.find(player:IPAddress(),":") - 1)
	
	player_name = tostring( player:GetName() )
	id = id:upper()
	
	local queryQ = ULXDB:query("SELECT p.`playerName`, p.`rank`, a.`group` as user_group, a.commands as user_commands, a.`denies` as disallowed_commands FROM `"..ULX_PLAYERS_TABLE.."` as p LEFT JOIN `"..ULX_GROUPS_TABLE.."` as a ON a.`group` = p.`rank` WHERE p.`steam`='"..ULXDB:escape(player:SteamID()).."';")
	queryQ.onData = function(Q,D)
		queryQ.onSuccess = function(q)
			Msg("Found "..tostring(D.playerName).." ["..tostring(D.user_group).."] in the database!\n")
			
			str = D.user_commands
            allowed = {}
		    c = 0
            for v in str:gmatch("[^\r\n]+") do
              allowed[c] = v
		      c = c+1
            end
			
			strD = D.disallowed_commands
            denies = {}
		    c = 0
            for v in strD:gmatch("[^\r\n]+") do
              denies[c] = v
		      c = c+1
            end
           
			ULib.ucl.addUser( id, allowed , denies, "user" )
			load = true
		end
	end
	queryQ.onError = function(Q,E) print("GetPlayerFromDB threw an error:") print(E) end
	queryQ:start()
	
	
	if (load == false) then

		ULib.ucl.addUser( id, allows, denies, "user" )
		local DateTime = tostring( os.date("%Y-%m-%d %H:%M:%S", os.time() ) )
		--Msg("Adding "..player_name.." "..id.." [user] in the database!\n")
		local InsertQ = ULXDB:query("INSERT INTO `"..ULX_PLAYERS_TABLE.."`( `steam`, `playerName`, `rank`, `connections`, `last_connection`, `user_ip` ) VALUES('"..ULXDB:escape(id).."', '"..ULXDB:escape(player_name).."', 'user', '1', '"..ULXDB:escape(DateTime).."', '"..ULXDB:escape(user_ip).."') ON DUPLICATE KEY UPDATE `connections` = connections+1, last_connection = '"..ULXDB:escape(DateTime).."', `playerName` = '"..ULXDB:escape(player_name).."', `user_ip` = '"..ULXDB:escape(user_ip).."' ")
		
		InsertQ.onError = function(Q,E) print("GetPlayerFromDB threw an error:") print(E) end
		InsertQ:start()

	end
end

hook.Add( "PlayerAuthed", "playerauthed",        CheckUserBan )
hook.Add( "PlayerAuthed", "GetPlayerDataFromDB", GetPlayerFromDB)

hook.Add("ULibPlayerTarget", "HookBanRemoveUserFromDatabase", function(player, cmd, target)
	if (cmd == "ulx banuser" && target) then
		Msg(""..target:GetName().." banned\n")
	end
end)


hook.Add("ULibCommandCalled", "BanRemoveUserFromDatabase", function(player, cmd, args)
	if (cmd == "ulx banuser" && ( args[1] ) ) then
	end
end)

function ulx.banuser( calling_ply, id, banTime, banReason )
	local id = id:upper()
	local player_name = id
	
	 	if not ULib.isValidSteamID( id ) then -- Assume steamid and check
			ULib.tsayError( calling_ply, "Invalid SteamID.", true )
			return false
		end
	local queryQ = ULXDB:query("SELECT * FROM `"..ULX_PLAYERS_TABLE.."` WHERE `steam`='"..ULXDB:escape(id).."';")
	queryQ.onData = function(Q,D)
		queryQ.onSuccess = function(q)
		local player_name = D.playerName
		local player_ip   = D.user_ip
	    --DBBanUser( tostring(calling_ply:GetName() ), id, player_name, banTime)
		
		local InfoDate = tostring( os.date("%Y-%m-%d %H:%M:%S") )
		local Expire = tostring( os.date("%Y-%m-%d %H:%M:%S", os.time()+(banTime*60) ) )
		banReason = tostring(banReason)
		  if banTime<=0 && ALLOW_PERM_BANS == 1 then
		    Expire = tostring( '0000-00-00 00:00:00' )
		  end
		  if banTime<=0 && ALLOW_PERM_BANS == 0 then
		    banTime = BAN_TIME
		    Expire = tostring( os.date("%Y-%m-%d %H:%M:%S", os.time()+(banTime*60) ) )
		  end
		  if not IsValid(calling_ply) then
		    local admin = "console" 
		  else
		    local admin = tostring( calling_ply:GetName() )
		  end
		
        Msg(""..id.." "..player_name.." banned by ["..admin.."]\n")
		local InsertQ = ULXDB:query("INSERT INTO `"..ULX_BANS_TABLE.."` (`steam`, `name`, `admin`, `reason`, `bantime`, `expire`, `ip`) VALUES ('"..ULXDB:escape(id).."', '"..ULXDB:escape(player_name).."', '"..ULXDB:escape(admin).."', '"..ULXDB:escape(banReason).."', '"..ULXDB:escape(InfoDate).."', '"..ULXDB:escape(Expire).."', '"..ULXDB:escape(player_ip).."') ON DUPLICATE KEY UPDATE `steam` = '"..ULXDB:escape(id).."', `name` = '"..ULXDB:escape(player_name).."', `admin` = '"..ULXDB:escape(admin).."', `bantime` = '"..ULXDB:escape(InfoDate).."', expire = '"..ULXDB:escape(Expire).."', `ip` = '"..ULXDB:escape(player_ip).."' ")
		
		InsertQ.onError = function(Q,E) print("'banuser' threw an error:") print(E) end
		InsertQ:start()
		ulx.fancyLogAdmin( calling_ply, "#A banned "..tostring(player_name).." ["..tostring(banTime).." min.]" )
		RunConsoleCommand( "kick", player_name )
		return true
		end
	end
	queryQ.onError = function(Q,E) print("updateuser threw an error:") print(E) end
	queryQ:start()
	
end

function ulx.removeban( calling_ply, id )
	id = id:upper()
	player_name = id
	
	if not ULib.isValidSteamID( id ) then -- Assume steamid and check
		ULib.tsayError( calling_ply, "Invalid SteamID.", true )
		return false
	end
	
	local queryQ = ULXDB:query("DELETE FROM `"..ULX_BANS_TABLE.."` WHERE `steam`='"..ULXDB:escape(id).."';")

	Msg("Removed Ban "..id.." \n")
	ulx.fancyLogAdmin( calling_ply, "#A removed ban "..tostring(id).." " )

	queryQ.onError = function(Q,E) print("GetPlayerFromDB threw an error:") print(E) end
	queryQ:start()
	return true

end

function ulx.updateuser( calling_ply, id, group )
	id = id:upper()
	group = tostring(group)
	group = group:lower()
	player_name = id
	
	 	if not ULib.isValidSteamID( id ) then -- Assume steamid and check
			ULib.tsayError( calling_ply, "Invalid SteamID.", true )
			return false
		end

	local queryQ = ULXDB:query("SELECT * FROM `"..ULX_PLAYERS_TABLE.."` WHERE `steam`='"..ULXDB:escape(id).."';")
	queryQ.onData = function(Q,D)
		queryQ.onSuccess = function(q)
		player_name = D.playerName
	    local queryUpd = ULXDB:query("UPDATE `"..ULX_PLAYERS_TABLE.."` SET `rank` = '"..ULXDB:escape(tostring(group)).."' WHERE `steam`='"..ULXDB:escape(id).."' LIMIT 1;")
	    queryUpd.onError = function(Q,E) print("updateuser threw an error:") print(E) end
	    queryUpd:start()
	
	    ULib.ucl.addUser( id, allows, denies, group )
	    Msg("Updating: "..tostring(player_name).." to ["..tostring(group).."]\n")
		ulx.fancyLogAdmin( calling_ply, "#A updated "..tostring(player_name).." ["..tostring(group).."]" )
		return true
		end
	end
	queryQ.onError = function(Q,E) print("updateuser threw an error:") print(E) end
	queryQ:start()
	
	Msg("Player: "..tostring(id).." not found in the database.\n")

end


function ulx.sid(calling_ply, target)

	local queryQ = ULXDB:query("SELECT * FROM `"..ULX_PLAYERS_TABLE.."` WHERE `playerName`='"..ULXDB:escape(tostring(target)).."';")
	queryQ.onData = function(Q,D)
		queryQ.onSuccess = function(q)
		ulx.fancyLogAdmin( calling_ply, "#A".." | "..tostring(D.steam).." ("..tostring(D.playerName)..") ["..tostring(D.rank).."]" )
		end
	end
	queryQ.onError = function(Q,E) print("ulx sid threw an error:") print(E) end
	queryQ:start()
end

function ulx.showgroups(calling_ply)

	local queryQ = ULXDB:query("SELECT `group` FROM `"..ULX_GROUPS_TABLE.."` WHERE `group`!='' ORDER BY FIELD(`group`, 'superadmin') ASC, `group` DESC LIMIT 10;")
	queryQ.onData = function(Q,D)
		queryQ.onSuccess = function(q)
		  ULib.console( calling_ply, "------------" )
          ULib.console( calling_ply, "All Groups:" )
		  ULib.console( calling_ply, "------------" )
          local playerInfo = queryQ:getData()
		  for k,v in pairs(playerInfo) do
		     ULib.console( calling_ply, v.group )
		  end
		end
	end
	queryQ.onError = function(Q,E) print("ulx showgroups threw an error:") print(E) end
	queryQ:start()
end


function ulx.checkban(calling_ply, target)
    
	local queryQ = ULXDB:query("SELECT * FROM `"..ULX_BANS_TABLE.."` WHERE `name`='"..ULXDB:escape(target).."' AND (`expire`>NOW() OR `expire` = '0000-00-00 00:00:00') ;")
	queryQ.onData = function(Q,D)
		queryQ.onSuccess = function(q)
		    local ExpireDate = D.expire
			if(D.expire == "0000-00-00 00:00:00") then ExpireDate = "[PERMANENT]"; end
			ulx.fancyLogAdmin( calling_ply, "#A "..target.." is banned. Expires: "..ExpireDate )
		end
	end
	queryQ.onError = function(Q,E) print("'checkban' threw an error:") print(E) end
	queryQ:start()

end

function ulx.showplayers(calling_ply)
    local allplayers = player.GetAll( )
	for _, pl in pairs( allplayers ) do
	   Msg( tostring( pl:GetName() ).." \n")
	   id = pl:EntIndex()
	   ULib.console( calling_ply, ""..tostring(id)..". "..tostring( pl:GetName() ).." ("..pl:SteamID()..")" )
	end

end

function ulx.banindex(calling_ply, index, banTime, banReason)
    local allplayers = player.GetAll( )
	
	local SqlQuery = "INSERT INTO `"..ULX_BANS_TABLE.."` (`steam`, `name`, `admin`, `reason`, `bantime`, `expire`, `ip`) VALUES "
	
	local CanBan = 0
	for _, pl in pairs( allplayers ) do
	   --Msg( tostring( pl:GetName() ).." \n")
	   local id = pl:EntIndex()
	   local allindex = string.Explode(",", index)
	   local total = table.Count(allindex)
	   
	   local InfoDate = tostring( os.date("%Y-%m-%d %H:%M:%S") )
	   local Expire = tostring( os.date("%Y-%m-%d %H:%M:%S", os.time()+(banTime*60) ) )
	   local banReason = tostring(banReason)
	   
	   if banTime<=0 && ALLOW_PERM_BANS == 1 then
		    Expire = tostring( '0000-00-00 00:00:00' )
	   end
	   
	   if banTime<=0 && ALLOW_PERM_BANS == 0 then
		    banTime = BAN_TIME
		    Expire = tostring( os.date("%Y-%m-%d %H:%M:%S", os.time()+(banTime*60) ) )
	   end
	   
	  if not IsValid(calling_ply) then admin = "console" else admin = tostring( calling_ply:GetName() ) end
	   
	   for i=1, total do
		  local CurrentIndex = tonumber( allindex[i] )
		  local player_name = pl:GetName()
		  local steam = pl:SteamID()
		  
	      if tonumber(id) == tonumber(allindex[i]) then

		  if not pl:IsBot() then
		    local user_ip = string.sub(pl:IPAddress(),1,string.find(pl:IPAddress(),":") - 1)
		  else 
		    local user_ip = '' 
			steam   = player_name
		  end
		  
		  if user_ip == nil then user_ip = ' ' end
		  
		  SqlQuery = SqlQuery.."('"..ULXDB:escape(steam).."', '"..ULXDB:escape(tostring(player_name)).."', '"..ULXDB:escape(admin).."', '"..ULXDB:escape(banReason).."', '"..ULXDB:escape(InfoDate).."', '"..ULXDB:escape(Expire).."', '"..ULXDB:escape(tostring(user_ip)).."'),"
          pl:Kick( "You have been banned! Reason: "..banReason.." Expire: " ..Expire.."" )
		  CanBan = 1
		  end
	   end
	   
	end -- get all players
	
	if CanBan == 1 then
	  SqlQuery = string.sub(SqlQuery,1, (string.len(SqlQuery) - 1) )
	  --Msg( tostring( SqlQuery ).." \n") -- debug qry
	  local InsertQ = ULXDB:query( SqlQuery )
	  InsertQ.onError = function(Q,E) print("'banindex' threw an error:") print(E) end
	  InsertQ:start()
	end -- can ban
end -- end

local banuser = ulx.command( "User Management", "ulx banuser", ulx.banuser )
banuser:addParam{ type=ULib.cmds.StringArg, hint="SteamID" }
banuser:addParam{ type=ULib.cmds.NumArg, hint="Ban time (in minutes) (optional)", ULib.cmds.optional }
banuser:addParam{ type=ULib.cmds.StringArg, hint="Ban Reason (optional)", ULib.cmds.optional }
banuser:defaultAccess( ULib.ACCESS_SUPERADMIN )
banuser:help( "Command to ban users and store data in MySQL database." )

local removeban = ulx.command( "User Management", "ulx removeban", ulx.removeban )
removeban:addParam{ type=ULib.cmds.StringArg, hint="SteamID" }
removeban:defaultAccess( ULib.ACCESS_SUPERADMIN )
removeban:help( "Command to remove banned users from the database." )

local updateuser = ulx.command( "User Management", "ulx updateuser", ulx.updateuser )
updateuser:addParam{ type=ULib.cmds.StringArg, hint="SteamID" }
updateuser:addParam{ type=ULib.cmds.StringArg, hint="User Group (superadmin, admin, user..." }
updateuser:defaultAccess( ULib.ACCESS_SUPERADMIN )
updateuser:help( "Update or remove user rank (superadmin, user...)." )

local sid = ulx.command( "User Management", "ulx sid", ulx.sid )
sid:addParam{ type=ULib.cmds.StringArg, hint="Player Name" }
sid:defaultAccess( ULib.ACCESS_SUPERADMIN )
sid:help( "Get SteamID from Player." )

local showgroups = ulx.command( "User Management", "ulx showgroups", ulx.showgroups )
showgroups:defaultAccess( ULib.ACCESS_SUPERADMIN )
showgroups:help( "Get All Groups." )

local checkban = ulx.command( "User Management", "ulx checkban", ulx.checkban )
checkban:addParam{ type=ULib.cmds.StringArg, hint="Player Name" }
checkban:defaultAccess( ULib.ACCESS_SUPERADMIN )
checkban:help( "Check if user is banned." )

local showplayers = ulx.command( "User Management", "ulx showplayers", ulx.showplayers )
showplayers:defaultAccess( ULib.ACCESS_SUPERADMIN )
showplayers:help( "Player list." )

local banindex = ulx.command( "User Management", "ulx banindex", ulx.banindex )
banindex:addParam{ type=ULib.cmds.StringArg, hint="Player index separated by comma" }
banindex:addParam{ type=ULib.cmds.NumArg, hint="Ban time (in minutes) (optional)", ULib.cmds.optional }
banindex:addParam{ type=ULib.cmds.StringArg, hint="Ban Reason (optional)", ULib.cmds.optional }
banindex:defaultAccess( ULib.ACCESS_SUPERADMIN )
banindex:help( "Ban more players using entity index (ulx showplayers)." )

function ConnectDB()
	print('[ULX OS] - Connecting to Database!')

	ULXDB.onConnected = afterConnected
	ULXDB.onConnectionFailed = function(db, msg) print("[ULX OS] connectToDatabase") print(msg) end
	ULXDB:connect()
end

ConnectDB()