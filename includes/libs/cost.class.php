<?php
/**
 * 费用类库
 * @copyright (c) UomgJump All Rights Reserved
 * @time 2019-01-091
 */
class Cost{
    public static function query($data){
        if ($data['type'] == 'recharge') {
            return self::recharge($data['uid'],$data['num'],$data['depict']);
        }else {
            return self::consume($data['uid'],$data['num'],$data['depict']);
        }
    }
    private static function consume($uid,$num,$depict) {
        global $DB;
        $date = date("Y-m-d H:i:s");
        $DB->query("INSERT INTO uomg_bill (`uid`,`usetime`,`money`,`type`,`depict`)value({$uid},'{$date}',{$num},0,'{$depict}')");
        return $DB->query("UPDATE uomg_user SET `money`= money-{$num} WHERE id={$uid}");
    }
    private static function recharge($uid,$num,$depict) {
        global $DB;
        $date = date("Y-m-d H:i:s");
        $DB->query("INSERT INTO uomg_bill (`uid`,`usetime`,`money`,`type`,`depict`)value({$uid},'{$date}',{$num},1,'{$depict}')");
        return $DB->query("UPDATE uomg_user SET `money`= money+{$num} WHERE id={$uid}");
    }
}