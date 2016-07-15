<?php
/**
 * @var $this EmailWebController
 * @var $content string
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

<?php $this->beginContent('email.views.layouts.main'); ?>
    <div class="container-fluid">
        <?php
        if ($this->pageHeading || $this->menu) {
            if ($this->menu)
                $this->pageHeading .= $this->widget('zii.widgets.CMenu', array(
                    'items' => $this->menu,
                    'htmlOptions' => array('class' => 'list-inline pull-right'),
                ), true);
            if ($this->pageHeading)
                echo CHtml::tag('h1', array(), $this->pageHeading);
        }
        ?>
        <div id="content">
            <?php
            echo $content;
            ?>
        </div>
    </div>
<?php $this->endContent(); ?>
