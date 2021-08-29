<?php

namespace HexMakina\Traitor;

trait Traitor
{
    public function traitor($method_name)
    {
        $regex = sprintf('/.+Traitor_%s$/', $method_name);
        $errors = [];
        $traits = (new \ReflectionClass($this))->getTraitNames();

        foreach ($traits as $trait_name) {
            $trait_methods = (new \ReflectionClass($trait_name))->getMethods();
            foreach ($trait_methods as $method) {
                if (preg_match($regex, $method->name, $match) === 1) {
                    $callable = current($match);
                    $errors ["$trait_name::" . $method->name] = $this->$callable();
                }
            }
        }
        return $errors;
    }
}
