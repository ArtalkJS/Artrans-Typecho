<?php
include_once 'common.php';
include 'header.php';
include 'menu.php';
?>

<div class="main">
  <div class="body container">
    <div class="row typecho-page-main" role="form">
      <div class="col-mb-12 col-tb-8 col-tb-offset-2">
        <div id="typecho-welcome">
            <form action="<?php $options->index('/action/ArtransAction?export'); ?>" method="post">
                <h3>( ๑´•ω•)ノ 导出评论数据为 Artrans 格式</h3>
                <p>Artrans (数据行囊) 是 <a href="https://artalk.js.org/" target="_blank">Artalk</a> 持久化数据保存规范格式，点击下方的按钮开始导出。</p>
                <p><button class="btn primary" type="submit" id="AtExportBtn">芜湖~ 起飞 🚀</button></p>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
setInterval(function() { document.querySelector('#AtExportBtn').removeAttribute('disabled'); }, 1000);
</script>

<?php
include 'copyright.php';
include 'common-js.php';
include 'table-js.php';
include 'footer.php';
?>
