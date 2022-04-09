<?php
/**
 * Artrans 导出操作
 */
class ArtransExporter_Action extends Typecho_Widget implements Widget_Interface_Do {
  public function doExport() {
    $db = Typecho_Db::get();
    $options = Typecho_Widget::widget('Widget_Options');

    $prefix = $db->getPrefix();
    $comment_table = $prefix.'comments';
    $content_table = $prefix.'contents';

    // 获取所有页面
    $pages = $db->fetchAll(
      $db->query("SELECT * FROM {$content_table} WHERE `type` in ('post','page')")
    );

    $pageMap = array();
    foreach ($pages as $i => $p) {
      $type = $p['type'];

      // 获取所有分类
      $p['categories'] = $db->fetchAll($db->select()->from('table.metas')
        ->join('table.relationships', 'table.relationships.mid = table.metas.mid')
        ->where('table.relationships.cid = ?', $p['cid'])
        ->where('table.metas.type = ?', 'category')
        ->order('table.metas.order', Typecho_Db::SORT_ASC));

      // 第一个 slug 作为该文章的分类
      $p['category'] = current(Typecho_Common::arrayFlatten($p['categories'], 'slug'));
      
      // 文章固定链接
      $routeExists = (NULL != Typecho_Router::get($type));
      $pages[$i]['rela_path'] = $routeExists ? Typecho_Router::url($type, $p) : '#';
      $pages[$i]['permalink'] = Typecho_Common::url($pages[$i]['rela_path'], $options->index);

      $pageMap[$p["cid"]] = $pages[$i];
    }

    // 获取所有评论
    $comments = $db->fetchAll(
      $db->query("SELECT * FROM {$comment_table} WHERE `status` != 'spam'")
    );

    $artrans = array();
    foreach ($comments as $c) {
      $p = $pageMap[$c["cid"]];
      $datetime = date(DATE_ATOM, $c["created"]);
        
      $artrans[] = array(
        "id"              => $c["coid"],
        "rid"             => $c["parent"],
        "content"         => $c["text"],
        "ua"              => $c["agent"],
        "ip"              => $c["ip"],
        "is_collapsed"    => false,
        "is_pending"      => $c["status"] != "approved",
        "created_at"      => $datetime,
        "updated_at"      => $datetime,
        "nick"            => $c["author"],
        "email"           => $c["mail"],
        "link"            => $c["url"],
        "page_key"        => $p["permalink"],
        "page_title"      => $p["title"],
        "page_admin_only" => $p["allowComment"] == "0",
        "vote_up"         => intval($c["likes"]),
        "vote_down"       => intval($c["dislikes"]),
      );
    }
    
    // 下载文件
    $fileName = 'typecho-'.date('Y-m-d').'.artrans';
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo json_encode($artrans, JSON_UNESCAPED_UNICODE);
  }

  /** 绑定动作 */
  public function action() {
    $this->widget('Widget_User')->pass('administrator');
    $this->on($this->request->is('export'))->doExport();
  }
}
