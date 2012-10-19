<?php


class Order extends QDB_ActiveRecord_Abstract
{
    function billingcycle()
    {
        switch($this->billingcycle)
        {
            case 'monthly':
                $str = '每月';
                break;
            case 'quarterly':
                $str = '每季';
                break;
            case 'semi-annually':
                $str = '半年';
                break;
            default:
                $str = '每年付';
                break;
        }

        return $str;
    }

    function name()
    {
        return '<a href="'. url('default::service/detail',array('id'=>$this->id())) .'">'. $this->name .'</a>';
    }
    
    function expired()
    {
        $dt = 30 * 24 * 3600;
        if($this->invoice->trade_time)
        {
            switch($this->billingcycle)
            {
                case 'monthly':
                    $style_it = $dt;
                    break;
                case 'quarterly':
                    $style_it = 3 * $dt;
                    break;
                case 'semi-annually':
                    $style_it = 6 * $dt;
                    break;
                default:
                    $style_it = 12 * $dt;
                    break;
            }
            
            return date('Y-m-d H:i',$this->invoice->trade_time + $style_it );
        }

        return '尚未生效';
    }
    
    function cost()
    {
        return '￥' . money_format('%.2n',$this->cost);
    }

    function status($show_btn = false)
    {

        $e2c = array
        (
            'unpaid'        => '<span class="unpaid">未付款</span>',
            'pending'       => '<span class="pending">处理中</span>',
            'approve'       => '<span class="approve">已生效</span>',
            'canceled'      => '<span class="canceled">已取消</span>',
            'expired'       => '<span class="expired">已过期</span>',
        );
        
        if($this->status == 'unpaid' && $show_btn)
        {
            return '<a class="link" target="_blank" href="'. url('default::payment/alipay',array('order_number'=>$this->invoice->order_number)) .'">立即付款</a>';
        }

        return isset($e2c[$this->status]) ? $e2c[$this->status] : $this->status;

    }
    
    function created()
    {
        return date('Y-m-d H:i:s',$this->created);
    }

    function account($format = null)
    {
        $account = $this->account;
        if($format == 'username')
        {
            return '<a href="'. url('manage::account/modify',array('id'=>$account->id())) .'" >' . $this->username . '</a>';
        }
        return '<a href="'. url('manage::account/modify',array('id'=>$account->id())) .'" >' . $this->username . '(' . $account->user_mail . ')</a>';
    }



    /**
     * 返回对象的定义
     *
     * @static
     *
     * @return array
     */
    static function __define()
    {
        return array
        (
            // 指定该 ActiveRecord 要使用的行为插件
            'behaviors' => '',

            // 指定行为插件的配置
            'behaviors_settings' => array
            (
                # '插件名' => array('选项' => 设置),
            ),

            // 用什么数据表保存对象
            'table_name' => 'vpn_order',

            // 指定数据表记录字段与对象属性之间的映射关系
            // 没有在此处指定的属性，QeePHP 会自动设置将属性映射为对象的可读写属性
            'props' => array
            (
                // 主键应该是只读，确保领域对象的“不变量”
                'order_id' => array('readonly' => true),


                /**
                 *  可以在此添加其他属性的设置
                 */
                # 'other_prop' => array('readonly' => true),

                /**
                 * 添加对象间的关联
                 */
                # 'other' => array('has_one' => 'Class'),
                  'invoice' => array(QDB::HAS_ONE=>'Invoice','target_key'=>'order_id','source_key'=>'order_id','on_find_order'=>'trade_time DESC'),
                  'account' => array(QDB::HAS_ONE=>'Account','target_key'=>'user_id','source_key'=>'user_id'),

            ),

            /**
             * 允许使用 mass-assignment 方式赋值的属性
             *
             * 如果指定了 attr_accessible，则忽略 attr_protected 的设置。
             */
            'attr_accessible' => '',

            /**
             * 拒绝使用 mass-assignment 方式赋值的属性
             */
            'attr_protected' => 'order_id',

            /**
             * 指定在数据库中创建对象时，哪些属性的值不允许由外部提供
             *
             * 这里指定的属性会在创建记录时被过滤掉，从而让数据库自行填充值。
             */
            'create_reject' => '',

            /**
             * 指定更新数据库中的对象时，哪些属性的值不允许由外部提供
             */
            'update_reject' => '',

            /**
             * 指定在数据库中创建对象时，哪些属性的值由下面指定的内容进行覆盖
             *
             * 如果填充值为 self::AUTOFILL_TIMESTAMP 或 self::AUTOFILL_DATETIME，
             * 则会根据属性的类型来自动填充当前时间（整数或字符串）。
             *
             * 如果填充值为一个数组，则假定为 callback 方法。
             */
            'create_autofill' => array
            (
                # 属性名 => 填充值
                # 'is_locked' => 0,
                'created' => self::AUTOFILL_TIMESTAMP ,
            ),

            /**
             * 指定更新数据库中的对象时，哪些属性的值由下面指定的内容进行覆盖
             *
             * 填充值的指定规则同 create_autofill
             */
            'update_autofill' => array
            (
                #'updated' => self::AUTOFILL_TIMESTAMP ,
            ),

            /**
             * 在保存对象时，会按照下面指定的验证规则进行验证。验证失败会抛出异常。
             *
             * 除了在保存时自动验证，还可以通过对象的 ::meta()->validate() 方法对数组数据进行验证。
             *
             * 如果需要添加一个自定义验证，应该写成
             *
             * 'title' => array(
             *        array(array(__CLASS__, 'checkTitle'), '标题不能为空'),
             * )
             *
             * 然后在该类中添加 checkTitle() 方法。函数原型如下：
             *
             * static function checkTitle($title)
             *
             * 该方法返回 true 表示通过验证。
             */
            'validations' => array
            (


            ),
        );
    }


/* ------------------ 以下是自动生成的代码，不能修改 ------------------ */

    /**
     * 开启一个查询，查找符合条件的对象或对象集合
     *
     * @static
     *
     * @return QDB_Select
     */
    static function find()
    {
        $args = func_get_args();
        return QDB_ActiveRecord_Meta::instance(__CLASS__)->findByArgs($args);
    }

    /**
     * 返回当前 ActiveRecord 类的元数据对象
     *
     * @static
     *
     * @return QDB_ActiveRecord_Meta
     */
    static function meta()
    {
        return QDB_ActiveRecord_Meta::instance(__CLASS__);
    }


/* ------------------ 以上是自动生成的代码，不能修改 ------------------ */

}

