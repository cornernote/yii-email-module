<?php
/**
 * EmailSpoolCommand will send emails that are pending in the spool.
 *
 * Use lockrun for overlap protection - https://github.com/pushcx/lockrun
 *
 * Add the following to your crontab:
 * <pre>
 * * * * * /usr/local/bin/lockrun --idempotent --lockfile=/path/to/app/runtime/lockrun/emailSpool loop -- /path/to/yiic emailSpool > /dev/null 2>&1
 * <pre>
 *
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-email-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-email-module/master/LICENSE
 *
 * @package yii-email-module
 */
class EmailSpoolCommand extends CConsoleCommand
{

    /**
     * Sends emails
     */
    public function actionIndex($spoolLimit = 10)
    {
        Yii::app()->emailManager->processSpool($spoolLimit);
    }

    /**
     * Sends emails in a continuous loop
     */
    public function actionLoop($loopLimit = 1000, $spoolLimit = 10)
    {
        for ($i = 0; $i < $loopLimit; $i++) {
            Yii::app()->emailManager->processSpool($spoolLimit);
            sleep(1);
        }
    }

}
