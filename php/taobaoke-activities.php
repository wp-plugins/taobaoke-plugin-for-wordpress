<?php
class ActivityController {
    public function getColumns() {
        return array(
               'id' => array(
                    'header' => 'id',
                    'function' => 'showCatsDetail',
                    'sortable' => false,
                    ),
            );
    }

    public function getDatasource() {
        return new TaobaokeActivityDataSource();
    }

    public function showCatsDetail($id, $row) {
        $pic_url = $row['picURL'];
        $title = $row['name'];
        $url = str_replace('$pid', var_get('pid'), $row['targetURL']);
        return "<div><div><a href='$url' target='_blank'><img src='{$pic_url}' style='width:150px;height:150px;' /></a></div><div><a href='$url' target='_blank'>$title</a></div></div>";
    }
}

function display_page() {
    $vars = array();

    $controller = new ActivityController();

    $taobaoke_activities_table = new Table($controller, $controller->getColumns(), $controller->getDatasource());
    $taobaoke_activities_table->setGridTableColumn(4);
    $taobaoke_activities_table->setNoRecordLabel('当前没有活动推广');

    $vars['taobaoke_activity_table'] = $taobaoke_activities_table;
   
    return $vars;
}
?>
