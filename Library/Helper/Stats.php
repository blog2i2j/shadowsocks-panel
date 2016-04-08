<?php
/**
 * Project: shadowsocks-panel
 * Author: Sendya <18x@loacg.com>
 * Time: 2016/4/8 22:32
 */


namespace Helper;

use Core\Database as DB;

/**
 * 系统信息统计 模块
 * Class Stats
 *
 * @Authorization
 * @package Helper
 */
class Stats {

    /**
     * Count all user
     * @NoAuthorization
     * @return int
     */
    public static function countUser() {
        $stn = DB::getInstance()->prepare("SELECT count(1) FROM `member`");
        $stn->execute();
        return $stn->fetch(DB::FETCH_NUM)[0]!=null?:0;
    }

    /**
     * Count last connect time f 600s
     * @return int
     */
    public static function countOnline() {
        $stn = DB::getInstance()->prepare("SELECT count(1) FROM `member` WHERE lastConnTime > " . (time() - 600));
        $stn->execute();
        return $stn->fetch(DB::FETCH_NUM)[0]!=null?:0;
    }

    /**
     * Count sign user
     * @return int
     */
    public static function countSignUser() {
        $stn = DB::getInstance()->prepare("SELECT count(1) FROM `member` WHERE lastCheckinTime > " . strtotime(date('Y-m-d 00:00:00', time())));
        $stn->execute();
        return $stn->fetch(DB::FETCH_NUM)[0]!=null?:0;
    }

    /**
     * Usage for all users
     * @return int
     */
    public static function countTransfer() {
        $stn = DB::getInstance()->prepare("SELECT (sum(flow_up) + sum(flow_down)) AS transferAll FROM `member`");
        $stn->execute();
        return $stn->fetch(DB::FETCH_NUM)[0]!=null?:0;
    }

    /**
     * 使用过服务的用户
     * @NoAuthorization
     * @return int
     */
    public static function countUseUser() {
        $stn = DB::getInstance()->prepare("SELECT count(1) FROM `member` WHERE lastConnTime > 0");
        $stn->execute();
        return $stn->fetch(DB::FETCH_NUM)[0]!=null?:0;
    }

    /**
     * 后端统计 <b>使用量区间</b>
     *
     * @param int $type
     * @return int
     */
    public static function dataUsage($type = 0) {
        $querySQL = "SELECT count(1) FROM member WHERE 1=1 ";
        switch($type) {
            case 1:
                $querySQL .= "AND flow_up+flow_down BETWEEN " . (Utils::gb()*10+1) . " AND " . (Utils::gb()*30); // 11GB ~ 30GB
                break;
            case 2:
                $querySQL .= "AND flow_up+flow_down BETWEEN " . (Utils::gb()*30+1) . " AND " . (Utils::gb()*100); // 30GB ~ 100GB
                break;
            case 3:
                $querySQL .= "AND flow_up+flow_down > " . (Utils::gb()*100+1); // 大于 100GB
                break;
            case 0:
            default:
                $querySQL .= "AND flow_up+flow_down < " . (Utils::gb()*10); // 小于10GB
                break;
        }
        $statement = DB::getInstance()->prepare($querySQL);
        $statement->execute();
        $count = $statement->fetch(DB::FETCH_NUM);
        return $count[0]!=null ?:0;
    }

    /**
     * 后端统计 <b>在线时间区间</b>
     *
     * @param int $type
     * @return int
     */
    public static function onlineUsage($type = 0) {
        $querySQL = "SELECT ";

        // sql...

        $stn = DB::getInstance()->prepare($querySQL);
        $stn->execute();
        $count = $stn->fetch(DB::FETCH_NUM);
        return $count[0]!=null ?:0;
    }
}