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
     *
     * @return array
     *
     * @author Qiang Xue <qiang.xue@gmail.com>
     */
    public static function merge()
    {
        $args = func_get_args();
        $res = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                $keyExists = isset($res[$k]);
                if (is_int($k)) {
                    if ($keyExists) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && $keyExists && is_array($res[$k])) {
                    $res[$k] = self::merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }
        return $res;
    }
}
