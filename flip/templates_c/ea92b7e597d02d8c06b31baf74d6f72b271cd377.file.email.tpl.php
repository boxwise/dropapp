<?php /* Smarty version Smarty-3.1.18, created on 2015-11-17 11:12:54
         compiled from "/var/www/vhosts/cultuur-ondernemen.nl/httpdocs/site/templates/email.tpl" */ ?>
<?php /*%%SmartyHeaderCode:195489391556dcb2b601914-19703782%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ea92b7e597d02d8c06b31baf74d6f72b271cd377' => 
    array (
      0 => '/var/www/vhosts/cultuur-ondernemen.nl/httpdocs/site/templates/email.tpl',
      1 => 1447692517,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '195489391556dcb2b601914-19703782',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dcb2b636de7_35404234',
  'variables' => 
  array (
    'domain' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dcb2b636de7_35404234')) {function content_556dcb2b636de7_35404234($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0">    <!-- So that mobile webkit will display zoomed in -->
    <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->

    <title>Cultuur+Ondernemen</title>
    <style type="text/css">
	
        /* Resets: see reset.css for details */
        .ReadMsgBody { width: 100%; background-color: #ebebeb;}
        .ExternalClass {width: 100%; background-color: #ebebeb;}
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height:100%;}
        body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}
        body {margin:0; padding:0;}
        table {border-spacing:0;}
        table td {border-collapse:collapse;}
        .yshortcuts a {border-bottom: none !important;}

		.mobile-sidemargin {
			background: #fff;
		}

        /* Constrain email width for small screens */
        @media screen and (max-width: 620px) {
			td[class="mobile-sidemargin"] {
				width: 20px;
			}
            table[class="container"] {
                width: 100% !important;
                max-width: 620px;
                min-width: 320px;
            }
            .scale  img {
	            width: 100% !important;
	            height: auto !important;
            }
            td[class="header-logo"] img {
            	width: 100% !important;
	            max-width: 403px !important;
	            height: auto !important;
            }
        }

        /* Give content more room on mobile */
        @media screen and (max-width: 480px) {
			td[class="mobile-sidemargin"] {
				width: 10px;
			}
            table[class="container"] {
                width: 100% !important;
                min-width: 320px;
            }
            td[class="force-col"] {
                display: block;
                padding-right: 0 !important;
            }
			td[class="mobile-hide"] {
				display: none;
			}
			td[class="header-logo"] {
				width: 150px !important;
			}
			td[class="small"] span {
				font-size: 9px !important; line-height: 12px !important; color: #f0f !important;
			}
			td[class="mobile-social"] span {
				font-size: 16px !important;
			}
			td[class="intro"] span {
				font-size: 16px !important; line-height: 18px !important; color: #f0f !important;
			}
        }
	
    </style>
</head>
<body bgcolor="#fffffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin: 0; padding: 0; background: #ffffff;">
	<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="background: #ffffff;">
		<tr>
			<td align="center" valign="top">
				<table border="0" cellpadding="0" cellspacing="0" class="container" width="600" bgcolor="#ffffff">
					<tr>
						<td width="20" class="mobile-sidemargin"></td>
						<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr><td height="35"></td></tr>
								<tr>
									<td align="left" valign="top" class="header-logo"><img src="http://<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
/site/img/logo@2x.png" alt="Cultuur+Ondernemen" width="340" /></td>
								</tr>
							</table>
						</td>
						<td width="20" class="mobile-sidemargin"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center" valign="top">
				<table border="0" cellpadding="0" cellspacing="0" class="container" width="600" bgcolor="#ffffff">
					<tr>
						<td width="20" class="mobile-sidemargin"></td>
						<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr><td height="35"></td></tr>
								<tr>
									<td align="left" valign="top">
										<span style="color: #000000; font-family: arial,sans serif; font-size: 14px; line-height: 140%;">
<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

									</td>
								</tr>
								<tr><td height="40"></td></tr>
							</table>
						</td>
						<td width="20" class="mobile-sidemargin"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center" valign="top">
				<table border="0" cellpadding="0" cellspacing="0" class="container" width="600" bgcolor="#ffffff">
					<tr>
						<td width="20" class="mobile-sidemargin"></td>
						<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr><td height="10"></td></tr>
								<tr>
									<td align="left" valign="top">
										<span style="color: #999999; font-family: arial,sans serif; font-size: 12px; line-height: 120%;">Dit bericht is automatisch verstuurd door de website van Cultuur+Ondernemen<br /><a href="http://www.cultuur-ondernemen.nl" style="color:#2eb6fa">Cultuur+Ondernemen</a></span>
									</td>
								</tr>
								<tr><td height="40"></td></tr>
							</table>
						</td>
						<td width="20" class="mobile-sidemargin"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

</body>
</html><?php }} ?>
