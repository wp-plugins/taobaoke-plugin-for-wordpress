<?php
class Table {
    protected $_controller;

    protected $_columns;

    protected $_sql;

    protected static $_table_index = 0;

    private $__rows_per_page;

    private $__total_pages;

    private $__current_page;

    private $__order_by_field;

    private $__order_by_order;

    private $__default_order_by;

    private $__table_class;

    private $__ajax_display_id;

    private $__show_header;

    private $__show_paging = true;

    private $__div_column_count;

    private $__data_source;

    private $__is_ajax_table = false;

    private $_variables = array();

    private $_zebra = array('zebra_color_1', 'zebra_color_2');

    private $__no_record_label = "<span style='text-align: center; display: block;'>暂无记录</span>";

    const PARAMETER_PAGE_PREFIX = 'table_page_';
    const PARAMETER_FIELD_PREFIX = 'table_field_';
    const PARAMETER_ORDER_PREFIX = 'table_order_';

    /**
     * Initializes the parameters and data will be displayed in the html table.
     * @param Controller $controller page holds the table
     * @param array $columns the columns for the table to display
     * @param string $data_source the datasource to retrive the data to dispaly
     */
    public function __construct($controller, $columns, DataSource $data_source) {
        $this->_controller = $controller;
        $this->_columns = $columns;
        $this->__data_source = $data_source;

        $this->__total_pages = 1;
        $this->__current_page = 1;

        $condition = $this->__data_source->getCondition();//UPDATED BY WYATT 07-05-2009
        $limit = $condition->getLimits();

        $this->__rows_per_page = count($limit) > 0 ? $limit[1] : -1;
        $this->__default_order_by = null;
        $this->__table_class = 'zebra';
        $this->__show_header = true;
        $this->__ajax_display_id = 'div_table_' . self::$_table_index;
    }

    /**
     * Renders a html table.
     * @return void
     */
    public function render() {
        ob_start();

        $get = new GetManager();
        self::$_table_index++;

        //the parameter names for the current table in the url
        $pager = self::PARAMETER_PAGE_PREFIX . self::$_table_index;
        $field = self::PARAMETER_FIELD_PREFIX . self::$_table_index;
        $order = self::PARAMETER_ORDER_PREFIX . self::$_table_index;

        //get the values of the parameters for the current table
        $this->__order_by_field = NULL;
        if (NULL != $get->$pager) {
            $this->__current_page = (1 <= intval($get->$pager)) ? intval($get->$pager) : 1;
        }
        if (NULL != $get->$field) {
            $this->__order_by_field = $get->$field;
        }
        if (NULL != $get->$order) {
            $this->__order_by_order = ($get->$order == 1) ? 'DESC' : 'ASC';
        }

        //prepend and append the table
        $this->set('table_index', self::$_table_index);

        $this->set('table_class', $this->__table_class);
        //get the table header and assign it the template
        $this->_columns = $this->getColumns($this->_columns);

        $top_columns = array ();
        $sub_columns = array ();
        $existing_group = NULL;
        $count = 1;
        $group_on = FALSE;
        foreach ($this->_columns as $column) {
            $column['colspan'] = 1;
            $column['rowspan'] = 1;
            $top_columns[] = $column;
        }

        if (NULL != $this->__div_column_count && $this->__div_column_count > 0) {//TODO
            $column_keys = array_keys($this->_columns);
            for ($index = 0; $index < $this->__div_column_count; $index++) {
                if ($index > 0) {
                    $top_columns[] = $top_columns[0];//TODO:
                    $this->_columns[$column_keys[0] . $index] = $this->_columns[$column_keys[0]];
                }
            }
        }

        if (NULL != $this->__div_column_count && $this->__div_column_count > 0) {
            $this->set('div_column', TRUE);
        }
        else {
            $this->set('div_column', FALSE);
        }

        $this->set('table_columns', $this->_columns);

        $this->set('columns', $top_columns);

        //get the data and assign it the template
        $rows = $this->getRows();
        $this->set('table_rows', $rows);
        if (NULL != $rows) {
            $this->set('no_record', '');
        }
        else {
            $total_columns = count($this->_columns);
            $this->set('no_record', "<tr id=\"noRecordRow\"><td colspan='$total_columns' style='text-align:center' class='tablebackgroundcolor'>" . $this->__no_record_label . '</td></tr>');
        }

        //get the pager and assign it the template
        $pagers = NULL;
        if ($this->__rows_per_page != -1) {
            $pagers = $this->getPagers();
        }
        $this->set('table_pagers', $pagers);

        //set the table header's status
        $this->set('show_header', $this->__show_header);

        //set the table paging status
        $this->set('show_paging', $this->__show_paging);

        //set the page handler
        $this->set('table_handler', get_class($this->_controller));
        $this->set('ajax_display_id', $this->__ajax_display_id);
        $this->set('zebra', $this->_zebra);

        extract($this->_variables);

        require TAO_PATH . 'tpl/table.tpl.php';

        $element_view = ob_get_contents();

        ob_end_clean();

        echo $element_view;
    }

    /** 设置在模板中用到的变量 **/
    public function set($name, $value) {
        $this->_variables[$name] = $value;
    }

    public function ajaxRender() {
        $this->__is_ajax_table = TRUE;
        $this->render();
        //TODO
    }

    public function setAjaxDisplayId($display_id) {
        $this->__ajax_display_id = $display_id;
    }

    /**
     * Gets all the data to be displayed.
     * @return array in 2 dimension of data, the first dimension is row and the second is column
     */
    private function getRows() {
        $rows = array ();
        $start_row = 0;

        //prepare the total records number, the start row and end row according to the paging
        $start_row = ($this->__current_page - 1) * $this->__rows_per_page;

        $total_rows = $this->__data_source->getTotal();

        if (NULL != $this->__div_column_count && $this->__div_column_count > 0) {//TODO
           $start_row = ($this->__current_page - 1) * $this->__rows_per_page * $this->__div_column_count;
           $this->__total_pages = ceil($total_rows / $this->__div_column_count / $this->__rows_per_page);
        }
        else {
            $this->__total_pages = ceil($total_rows / $this->__rows_per_page);
        }

        if (1 == $this->__total_pages) {
            $this->__rows_per_page = -1;
        }

        $condition = $this->__data_source->getCondition();

        //prepare the order by clause and limit clause according to the paging and sorting
        $order_by = (NULL == $this->__order_by_field) ? '' : $condition->setSortMode(array($this->__order_by_field => $this->__order_by_order));
        if (('' == $order_by) && (!is_null($this->__default_order_by))) {
            $order_by = $condition->setSortMode($this->__default_order_by);
        }

        $limit = ($this->__rows_per_page == -1) ? '' : $condition->setLimits(array($start_row, $this->__rows_per_page));
        if (NULL != $this->__div_column_count && $this->__div_column_count > 0) {//TODO
             $limit = ($this->__rows_per_page == -1) ? $condition->setLimits(array($start_row, $total_rows)) : $condition->setLimits(array($start_row, $this->__rows_per_page * $this->__div_column_count));
         }

        //make up the sql to retrieve records for current page
        $this->__data_source->setCondition($condition);

        //adjust the total page number
         if (NULL != $this->__div_column_count && $this->__div_column_count > 0) {//TODO
             if ($this->__total_pages * $this->__rows_per_page *  $this->__div_column_count < $total_rows) {
                 $this->__total_pages++;
             }
         }
         else {
             if ($this->__total_pages * $this->__rows_per_page < $total_rows) {
                $this->__total_pages++;
             }
        }

        $data_from_data_source = $this->__data_source->getData();
        $data_index = -1;

        //get all the data and modify them by the input functions
        if (NULL != $this->__div_column_count && $this->__div_column_count > 0) {//TODO
            $total_rows = $this->__data_source->getDataCount();

            while ($total_rows > 0) {
                $new_row = array();

                for ($index = 0; $index < $this->__div_column_count; $index++) {
                    $new_column = array ();
                    $data_index++;

                    $row = isset($data_from_data_source[$data_index]) ? $data_from_data_source[$data_index] : NULL;
                    $key_prefix = '';
                    //TODO: Support multipal columns
                    foreach ($this->_columns as $key => $column) {
                       if (isset($column['function']) && ('' != $column['function'])) {
                           $funtion = $column['function'];

                           $row_key = isset($row[$key]) ? $row[$key] : $key;

                           if ($index > 0) {

                               if ($row == NULL) {
                                   $new_row[$key . $index] = '&nbsp;';
                               }
                               else {
                                   $new_row[$key . $index] = $this->_controller->$funtion($row_key, $row);
                               }
                           }
                           else {

                               if ($row == NULL) {
                                   $new_row[$key] = '&nbsp;';
                               }
                               else {
                                   $new_row[$key] = $this->_controller->$funtion($row_key, $row);
                               }
                           }
                       }
                       else {
                           if ($index > 0) {
                               $new_row[$key . $index] = $row[$key];
                           }
                           else {
                               $new_row[$key] = $row[$key];
                           }
                       }

                       break;
                    }
                }
                $total_rows = (int)$total_rows-(int)$index;

                $rows[] = $new_row;
            }
            //echo count($rows);
        }
        else {
            $total_rows = $this->__data_source->getDataCount();
            $data_index++;

            while ($data_index < $total_rows) {
                $new_row = array ();

                $row = $data_from_data_source[$data_index];

                foreach ($this->_columns as $key => $column) {
                   if (isset($column['function']) && ('' != $column['function'])) {
                       $funtion = $column['function'];

                       $row_key = array_key_exists($key, $row) ? $row[$key] : $key;
                       $new_row[$key] = $this->_controller->$funtion($row_key, $row);
                   }
                   else {
                       $new_row[$key] = $row[$key];
                   }
                }
                $rows[] = $new_row;

                $data_index++;
            }
        }

        return $rows;
    }

    /**
     * Gets the pagers.
     * @return array in 2 dimension of data, the first dimension is number index abd the second is 'text' and 'link'
     */
    private function getPagers() {
        $pagers = array ();
        $start_page = (($this->__current_page - 4 > 0) && ($this->__total_pages > 9)) ? ($this->__current_page - 4) : 1;
        $end_page = ($start_page + 8 > $this->__total_pages) ? $this->__total_pages : ($start_page + 8);
        if ($this->__total_pages > 0) {
            if (1 != $this->__current_page) {
                $pagers[] = $this->constructPager('第一页', 1, 'first-page');
            }
            if ($this->__current_page > 1) {
                $pagers[] = $this->constructPager('上一页', $this->__current_page - 1, 'prev-page');
            }
            for ($i = $start_page; $i <= $end_page; $i++) {
                $link_page = $i;
                $class = ($i == $this->__current_page) ? 'current-page' : 'o';
                $pagers[] = $this->constructPager($i, $link_page, $class);
            }
            if ($this->__current_page < $this->__total_pages) {
                $pagers[] = $this->constructPager('下一页', $this->__current_page + 1, 'next-page');
            }
            if ($this->__current_page != $this->__total_pages) {
                $pagers[] = $this->constructPager('最后一页&nbsp;', $this->__total_pages, 'last-page');
            }
        }
        return $pagers;
    }

    /**
     * Constructs a pager.
     * @param string $text the text to display for the pager
     * @param string $link_page the page number the pager will link to
     * @param boolean $has_separator if there is separater after the page number
     * @return array with keys 'text' and 'link'
     */
    private function constructPager($text, $link_page, $class, $has_separator = FALSE) {
        $pager = array ('text' => $text, 'link' => NULL, 'class' => $class, 'separator' => $has_separator ? '|' : NULL);

        if ($text != $this->__current_page) {
            $pager['link'] = self::PARAMETER_PAGE_PREFIX . self::$_table_index . '='. $link_page;
            $pager['link'] = $this->addParameter($pager['link']);
        }

        return $pager;
    }

    /**
     * Adds a parameter to the query string
     * @param string $parameter the parameter in the format 'key=value'
     * @return string the updated query string
     */
    private function addParameter($parameter) {
        $parameters = NULL;
        $query_string = Common::getQueryString();
        $parameter = explode('=', $parameter);
        list($add_key, $add_value) = $parameter;
        if ('' != $query_string) {
            $parameters = explode('&', $query_string);
        }
        $new_parameters = array();
        for ($i = 0; $i < count($parameters); $i++) {
            $parameter = explode('=', $parameters[$i]);
            $key = $parameter[0];
            $value = isset($parameter[1]) ? $parameter[1] : '';
            if($this->__is_ajax_table) {
                if($key!='sid' && $key!='handler' && $key!='display_id') {
                    $new_parameters[$key] = $value;
                }
            } else {
                $new_parameters[$key] = $value;
            }
        }
        $new_parameters[$add_key] = $add_value;
        foreach ($new_parameters as $key => $value) {
            $result_parameters[] = $key . '=' . $value;
        }
        $result_parameters = implode('&amp;', $result_parameters);

        return $result_parameters;
    }

    /**
     * Process the columns array with adding the links to the sort fields
     * @param array $columns the orginal columns without link information
     * @return array 'link' has been added to the fields to sort
     */
    private function getColumns($columns) {
        $result_columns = $columns;
        foreach ($columns as $key => $column) {
            $result_columns[$key]['link'] = self::PARAMETER_FIELD_PREFIX . self::$_table_index . '=' . $key;
            $result_columns[$key]['link'] = $this->addParameter($result_columns[$key]['link']);
            $result_columns[$key]['order'] = NULL;
            $order = '0';
            if ($this->__order_by_field == $key) {
                $order = ($this->__order_by_order == 'ASC') ? '1' : '0';
                $result_columns[$key]['order'] = $this->__order_by_order;
            }
            $result_columns[$key]['link'] .= '&amp;' . self::PARAMETER_ORDER_PREFIX . self::$_table_index . '=' . $order;
            $result_columns[$key]['sortable'] = array_key_exists('sortable', $column) ? $column['sortable'] : FALSE;
            $header_style =  $cell_style = array (
                'width' => array_key_exists('width', $column) ? ('width:' . $column['width'] . ';') : ''
            );

            $header_style['header_style'] = array_key_exists('header_style', $column) ? $column['header_style'] . ';' : '';

            $cell_style = array (
                'height' => array_key_exists('height', $column) ? ('height:' . $column['height'] . ';') : '',
                'align' => array_key_exists('align', $column) ? ('text-align:' . $column['align'] . ';') : 'text-align:center;',
                'valign' => array_key_exists('valign', $column) ? ('vertical-align:' . $column['valign'] . ';') : 'vertical-align:middle;',
                'cell_style' => array_key_exists('cell_style', $column) ? $column['cell_style'] . ';' : ''
            );

            $result_columns[$key]['cell_class'] = array_key_exists('cell_class', $column) ? $column['cell_class'] : '';
            $result_columns[$key]['header_class'] = array_key_exists('header_class', $column) ? $column['header_class'] : '';

            $result_columns[$key]['header_style'] = implode('', $header_style);
            $result_columns[$key]['cell_style'] = implode('', $cell_style);
        }

        return $result_columns;
    }

    /**
     * Sets optional paging number.
     * @param int $paging the paging number
     * @return void
     */
    public function setPaging($paging) {
        $this->__rows_per_page = $paging;
    }

    public function setNoRecordLabel($no_record_string) {
        if(NULL != $no_record_string) {
            $this->__no_record_label = $no_record_string;
        }
    }

    /**
     * Sets default and order.
     */
    public function setDefaultOrder($column, $order = 'ASC') {
        $order = strtoupper($order);
        if (('ASC' != $order) && ('DESC' != $order)) {
            $order = 'ASC';
        }
        if (array_key_exists($column, $this->_columns)) {
            $this->__default_order_by = array($column => $order);
            $this->__order_by_field = $column;
            $this->__order_by_order = $order;
        }
    }

    /**
     * Sets the table CSS class.
     */
    public function setTableClass($class) {
        $this->__table_class = $class;
    }

    /**
     * Sets the table header status.
     */
    public function hideHeader() {
        $this->__show_header = FALSE;
    }

    /**
     * 设置分页链接隐藏。调用该函数以后，分页链接会被隐藏。
     */
    public function hidePaging() {
        $this->__show_paging = FALSE;
    }

    public function setZebra($zebra) {
        $this->_zebra = $zebra;
    }

    /**
     * Sets the div style dispay.
     */
    public function setGridTableColumn($column_count) {
        $this->__div_column_count = $column_count;
        $this->__show_header = FALSE; //TODO:
        $this->_zebra = array('zebra_color_1', 'zebra_color_1');
    }

    /**
     * 获取当前页数。
     * @return Int current page number.
     */
    public function getCurrentPage() {
        $table_index = self::$_table_index;
        $table_index++;
        $pager = self::PARAMETER_PAGE_PREFIX . $table_index;
        $get = new GetManager();
        return (1 <= intval($get->$pager)) ? intval($get->$pager) : 1;
    }
}
