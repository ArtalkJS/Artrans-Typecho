<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * ( ๑´•ω•)ノ 导出评论数据为 Artrans (数据行囊) 格式的小工具
 *
 * @package ArtransExporter
 * @author ArtalkJS
 * @version 1.0.0
 * @link https://artalk.js.org/
 */
class ArtransExporter_Plugin implements Typecho_Plugin_Interface {
    /** 激活插件 */
    public static function activate() {
        Helper::addAction('ArtransAction', 'ArtransExporter_Action');
        Helper::addPanel(1, 'ArtransExporter/Panel.php', _t('评论导出 (Artrans)'), _t('评论导出 (Artrans)'), 'administrator');

        return _t('插件启用成功');
    }

    /** 禁用插件 */
    public static function deactivate() {
        Helper::removeAction('ArtransAction');
        Helper::removePanel(1, 'ArtransExporter/Panel.php');
    }
}
