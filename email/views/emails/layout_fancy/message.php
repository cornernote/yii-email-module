<?php
/**
 * Message for Email Fancy Layout
 *
 * @var $heading string
 * @var $contents string
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */
?>

<div marginwidth="0" marginheight="0">
    <div style="background-color:#eeeeee;width:100%;margin:0;padding:30px 0 30px 0">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center">
                        <tr>
                            <td>
                                <img src="http://placehold.it/150x50&text=logo"/><br/>
                                <br/>
                            </td>
                        </tr>
                    </table>
                    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="border-radius:6px!important;background-color:#fdfdfd;border:1px solid #d6d6d6;border-radius:6px!important">
                        <tbody>
                        <?php if ($heading) { ?>
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#394755;color:#ffffff;border-top-left-radius:6px!important;border-top-right-radius:6px!important;border-bottom:0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle" bgcolor="#557da1">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <h1 style="color:#ffffff;margin:0;padding:28px 24px;display:block;font-family:Arial;font-size:30px;font-weight:bold;text-align:left;line-height:150%"><?php echo $heading; ?></h1>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td align="center" valign="top">
                                <table border="0" cellpadding="0" cellspacing="0" width="600">
                                    <tbody>
                                    <tr>
                                        <td valign="top" style="background-color:#fdfdfd;border-radius:6px!important">
                                            <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                <tbody>
                                                <tr>
                                                    <td valign="top">
                                                        <div style="color:#4d4d4d;font-family:Arial;font-size:14px;line-height:150%;text-align:left">
                                                            <?php echo $contents; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center">
                        <tr>
                            <td>
                                <div style="color:#737373;font-family:Arial;font-size:12px;line-height:150%;text-align:left">
                                    <br/>
                                    <span style="font-size:16px;font-weight:bold;">App Name</span><br/>
                                    <b>01 2345 6789<br/>
                                    <a href="mailto:example@dom.ain">example@dom.ain</a><br/>
                                    <a href="http://www.dom.ain">www.dom.ain</a><br/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>