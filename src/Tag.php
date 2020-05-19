<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

class Tag
{

    public ?string $child = null;
    protected array $params = [];
    protected string $tagName;

    public function __call(string $paramName, array $paramValues)
    {
        if (isset($this->params[$paramName])) {
            $this->params[$paramName] = implode('', $paramValues);
        }
        return $this;
    }

    public function __construct(array $params = null, string $tagName = null,
            string $child = null)
    {
        $this->tagName = $tagName ?? $this->tagName ?? $this->classToTagName();
        $this->params = $params ?? [];
        if (!empty($child)) {
            $this->child = $child;
        }
    }

    public function __toString()
    {
        return $this->print();
    }

    public function setParam(string $paramName, string $value)
    {
        $this->params[$paramName] = $value;
    }

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
            if (!is_null($value)) {
                $paramList[] = "$param=\"$value\"";
            }
        }
        return implode(' ', $paramList) . ' ';
    }

}
