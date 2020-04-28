<?php
include "./lib.inc";
$remove = 0;
$opn = $vlans = '';
# MUST provide: port, secret, deployment_id, inst_id
# MAY provide: operatorname, vlanno, vlanrealms[], remove
error_log('MGW '.print_r($_REQUEST, true));
if (isset($_REQUEST['port']) && isset($_REQUEST['secret']) &&
    isset($_REQUEST['country']) &&
    isset($_REQUEST['instid']) && isset($_REQUEST['deploymentid'])) {
  if (isset($_REQUEST['remove'])) {
    $remove = 1;
  } else {
    if (isset($_REQUEST['operatorname'])) {
      $opn = trim($_REQUEST['operatorname']);
    } else {
      $opn = OPNAME_PREFIX . trim($_REQUEST['instid']) . '-' . trim($_REQUEST['deploymentid']) . OPNAME_SUFFIX;
    }
    if (isset($_REQUEST['vlan']) && isset($_REQUEST['realmforvlan']) &&
        is_array($_REQUEST['realmforvlan'])) {
      $vlans = $_REQUEST['vlan'] . '#' . implode('#', $_REQUEST['realmforvlan']);
    }
  }
  # arguments 5-7 are Base64 encoded
  $res = cat_socket(implode(':', array($_REQUEST['country'],
                                 $_REQUEST['instid'], $_REQUEST['deploymentid'],
                                 $_REQUEST['port'],
                                 base64_encode($_REQUEST['secret']),
                                 base64_encode($opn), 
                                 base64_encode($vlans), $remove)));
  echo $res;
} else {
  echo "FAILURE";
}
