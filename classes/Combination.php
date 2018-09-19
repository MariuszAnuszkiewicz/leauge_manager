<?php namespace MariuszAnuszkiewicz\classes\Combination;

class Combination
{
    public static function init($items, $perms = [])
    {
        for ($i = count($items) - 1; $i >= 0; --$i) {
            $newItems = $items;
            $newPerms = $perms;
            list($append) = array_splice($newItems, $i, 1);
            array_unshift($newPerms, $append);
            Combination::init($newItems, $newPerms);
        }
    }
}