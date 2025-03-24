<?php

namespace HexMakina\Traitor;

trait Traitor
{
    public function traitor($method_name)
    {
        $ret = [];

        $regex = sprintf($this->traitorPattern(), $method_name);

        $traits = (new \ReflectionClass($this))->getTraitNames();
        foreach ($traits as $trait_name) {
            $trait_methods = (new \ReflectionClass($trait_name))->getMethods();
            foreach ($trait_methods as $method) {
                if (preg_match($regex, $method->name, $match) === 1) {
                    $callable = current($match);
                    $res = $this->$callable();
                    if(is_array($res) && !empty($res))
                        $ret[$trait_name . '::' . $method->name] = $res;
                }
            }
        }

        return $ret;
    }

    public function traitorPattern()
    {
        return '/.+Traitor_%s$/';
    }
}
