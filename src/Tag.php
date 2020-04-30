<?php

/**
 * MIT License
 * Copyright (c) 2020 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

class Tag
{

    protected $child;
    protected $params = [];
    protected $tagName;

    public static function build(string $tagName)
    {
        $tagClass = '\\Canopy3\\Tag\\' . ucfirst($tagName);
        if (!class_exists($tagClass)) {
            throw new \Exception('Tag class not found: ' . $tagClass);
        }
        $tag = new $tagClass;
        return $tag;
    }

    public function print()
    {
        if (is_null($this->child)) {
            return <<<EOF
<{$this->tagName} {$this->parameters()}/>
EOF;
        } else {
            <<<EOF
<{$this->tagName} {$this->parameters()}>{$this->child}</{$this->tagName}>
EOF;
        }
    }

    public function __toString()
    {
        return $this->print();
    }

    public function __call(string $paramName, array $paramValues)
    {
        if (isset($this->params[$paramName])) {
            $this->params[$paramName] = implode('', $paramValues);
        }
        return $this;
    }

    protected function __construct(array $params = null, string $tagName = null)
    {
        $this->tagName = $tagName ?? $this->tagName ?? $this->classToTagName();
        $this->params = $params ?? [];
    }

    private function classToTagName()
    {
        $namespaceArray = explode('\\', get_called_class());
        return strtolower(array_pop($namespaceArray));
    }

    private function parameters()
    {
        if (empty($this->params)) {
            return;
        }
        foreach ($this->params as $param => $value) {
            $paramList[] = "$param=\"$value\"";
        }
        return implode(' ', $paramList) . ' ';
    }

}
