local shortport = require "shortport"

description = [[
Get root page and return status code
]]

---
-- @usage nmap -p80 --script http-status.nse <target>
--
-- @output
-- 404
---

categories = {"discovery", "intrusive"}
author = "Adrien Malingrey"
license = "Same as Nmap--See https://nmap.org/book/man-legal.html"

portrule = shortport.http


local http = require "http"

action = function(host, port)
  return http.get( host, port, "/" ).status
end
