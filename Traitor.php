<?php

namespace HexMakina\Traitor;

trait Traitor
{
    public $traitor_pattern = '/.+Traitor_%s$/';

    public function search_and_execute_trait_methods($method_name)
    {
        $pattern = sprintf($this->traitor_pattern, $method_name);
        $errors = [];
        $traits = (new \ReflectionClass($this))->getTraitNames();
        foreach ($traits as $trait_name) {
            $trait_methods = (new \ReflectionClass($trait_name))->getMethods();
            foreach ($trait_methods as $method) {
                if (preg_match($pattern, $method->name, $match) === 1) {
                    $callable = current($match);
                    $errors ["$trait_name::".$method->name]= $this->$callable();
                }
            }
        }
        return $errors;
    }
}
