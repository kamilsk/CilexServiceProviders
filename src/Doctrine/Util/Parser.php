<?php

namespace OctoLab\Cilex\Doctrine\Util;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class Parser
{
    /**
     * @param string $text
     *
     * @return string[] sql instructions
     *
     * @api
     */
    public function extractSql($text)
    {
        $text = preg_replace('/\s*--.*$/um', '', $text);
        $text = preg_replace('/\s*#.*$/um', '', $text);
        $text = preg_replace('/\/\*(?:[^*]|\n)*\*\//uim', '', $text);
        $text = preg_replace('/\n/', ' ', $text);
        while (preg_match('/\s{2,}/', $text)) {
            $text = preg_replace('/\s{2,}/', ' ', $text);
        }
        $text = trim($text, '; ');
        return preg_split('/\s*;\s*/', $text);
    }
}
