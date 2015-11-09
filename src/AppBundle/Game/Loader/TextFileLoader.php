<?php

namespace AppBundle\Game\Loader;

class TextFileLoader implements LoaderInterface
{
    public function load($dictionary)
    {
        $words = array_map('trim', file($dictionary));
        
        $size = count($words);
        $last = $size-1;
        if (empty($words[$last])) {
            unset($words[$last]);
        }

        return $words;
    }
}
