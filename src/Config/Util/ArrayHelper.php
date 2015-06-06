<?php

namespace OctoLab\Cilex\Config\Util;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ArrayHelper
{
    /**
     * Merges two or more arrays into one recursively.
     *
     * Based on yii\helpers\BaseArrayHelper::merge.
     * @params ... array
     *
     * @return array
     *
     * @author Qiang Xue <qiang.xue@gmail.com>
     */
    public static function merge()
    {
        $args = func_get_args();
        $res = array_shift($args);
        while ($args) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if (is_int($k)) {
                    if (isset($res[$k])) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = self::merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }
        return $res;
    }
}
