local shortport = require "shortport"

description = [[
Get and return a page info
]]

---
-- @args http-get.path Path to get. Default /.
--
-- @usage nmap -p80 --script http-get.nse --script-args http-get.path=/ <target>
--
-- @output
-- body:<html>...</html>
-- status: 200
-- status-line: HTTP/1.1 200 OK\x0D
-- header: ...
-- rawheader: ...
-- cookies: 

-- ssl: false
-- version: 1.1
---

categories = {"discovery", "intrusive"}
author = "Adrien Malingrey"
license = "Same as Nmap--See https://nmap.org/book/man-legal.html"

portrule = shortport.http


local http = require "http"
local stdnse = require "stdnse"

action = function(host, port)
  local path = ""

  if(stdnse.get_script_args('http-get.path')) then
    path = "/" .. stdnse.get_script_args('http-get.path')
  end

  return http.get( host, port, "/" .. path )
end
