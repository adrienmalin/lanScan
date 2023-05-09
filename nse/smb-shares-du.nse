  local stdnse = require "stdnse"
  local smb = require "smb"
  local msrpc = require "msrpc"
  local msrpctypes = require "msrpctypes"

  hostrule = function(host)
      return smb.get_port(host) ~= nil
  end

  action = function(host)
    local status, shares, extra
    local response = stdnse.output_table()

    -- Try and do this the good way, make a MSRPC call to get the shares
    stdnse.debug1("SMB: Attempting to log into the system to enumerate shares")
    status, shares = msrpc.enum_shares(host)
    if(status == false) then
      return stdnse.format_output(false, string.format("Couldn't enumerate shares: %s", shares))
    end

    -- Get more information on each share
    for i = 1, #shares, 1 do
      local share = shares[i]
      stdnse.debug1("SMB: Getting information for share: %s", share)

      status, result = get_share_info(host, share)
      response[share] = result
    end

    return response
  end


  ---Attempts to retrieve additional information about a share. Will fail unless we have
  -- administrative access.
  --
  --@param host The host object.
  --@return Status (true or false).
  --@return A table of information about the share (if status is true) or an an error string (if
  --        status is false).
  function get_share_info(host, name)
    local response = {}

    -- Create the SMB session
    local status, smbstate = msrpc.start_smb(host, msrpc.SRVSVC_PATH)
    if(status == false) then
      return false, smbstate
    end

    -- Bind to SRVSVC service
    local status, bind_result = msrpc.bind(smbstate, msrpc.SRVSVC_UUID, msrpc.SRVSVC_VERSION, nil)
    if(status == false) then
      smb.stop(smbstate)
      return false, bind_result
    end

    -- Call NetShareGetInfo

    local status, netsharegetinfo_result = srvsvc_netsharegetinfo(smbstate, host.ip, name, 2)
    stdnse.debug2("NetShareGetInfo status:%s result:%s", status, netsharegetinfo_result)
    if(status == false) then
      if(string.find(netsharegetinfo_result, "NT_STATUS_WERR_ACCESS_DENIED")) then
        stdnse.debug2("Calling NetShareGetInfo with information level 1")
        status, netsharegetinfo_result = srvsvc_netsharegetinfo(smbstate, host.ip, name, 1)
        if status then
          smb.stop(smbstate)
          return true, netsharegetinfo_result
        end
      end
      smb.stop(smbstate)
      return false, netsharegetinfo_result
    end

    smb.stop(smbstate)

    return true, netsharegetinfo_result
  end


  ---Call the MSRPC function <code>netsharegetinfo</code> on the remote system. This function retrieves extra information about a share
  -- on the system.
  --
  --@param smbstate The SMB state table
  --@param server   The IP or Hostname of the server (seems to be ignored but it's a good idea to have it)
  --@return (status, result) If status is false, result is an error message. Otherwise, result is a table of values, the most
  --        useful one being 'shares', which is a list of the system's shares.
  function srvsvc_netsharegetinfo(smbstate, server, share, level)
    stdnse.debug2("Calling NetShareGetInfo(%s, %s, %d)", server, share, level)

    --NetGetShareInfo seems to reject FQPN and reads the server value from the request
    --If any function called this function using a FQPN, this should take care of it.
    local _, _, sharename = string.find(share, "\\\\.*\\(.*)")
    if sharename then
      share = sharename
    end
    --    [in]   [string,charset(UTF16)] uint16 *server_unc,
    local arguments = msrpctypes.marshall_unicode_ptr("\\\\" .. server, true)

    --    [in]   [string,charset(UTF16)] uint16 share_name[],
    .. msrpctypes.marshall_unicode(share, true)

    --    [in]   uint32 level,
    .. msrpctypes.marshall_int32(level)

    --    [out,switch_is(level)] srvsvc_NetShareInfo info


    -- Do the call
    local status, result = msrpc.call_function(smbstate, smb.command_codes.SMB_COM_QUERY_INFORMATION_DISK, arguments)
    if(status ~= true) then
      return false, result
    end

    stdnse.debug3("MSRPC: NetShareGetInfo() returned successfully")

    -- Make arguments easier to use
    arguments = result['arguments']
    local pos = 1

    --    [in]   [string,charset(UTF16)] uint16 *server_unc,
    --    [in]   [string,charset(UTF16)] uint16 share_name[],
    --    [in]   uint32 level,
    --    [out,switch_is(level)] srvsvc_NetShareInfo info
    pos, result['info'] = msrpctypes.unmarshall_srvsvc_NetShareInfo(arguments, pos)
    if(pos == nil) then
      return false, "unmarshall_srvsvc_NetShareInfo() returned an error"
    end

    -- The return value
    pos, result['return'] = msrpctypes.unmarshall_int32(arguments, pos)
    if(result['return'] == nil) then
      return false, "Read off the end of the packet (srvsvc.netsharegetinfo)"
    end
    if(result['return'] ~= 0) then
      return false, smb.get_status_name(result['return']) .. " (srvsvc.netsharegetinfo)"
    end

    return true, result
  end