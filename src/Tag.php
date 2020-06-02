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
    protected array $attributes = [];
    protected array $allowed = [];
    protected string $tagName;

    /**
     * If $selfClosing == false, start and end tags are used.
     * @var type
     */
    protected bool $selfClosing = false;

    public function __call(string $attributeName, array $attributeValues)
    {
        if (isset($this->attributes[$attributeName])) {
            $this->attributes[$attributeName] = implode('', $attributeValues);
        }
        return $this;
    }

    public function __construct(array $attributes = null,
            string $tagName = null, string $child = null)
    {
        $this->tagName = $tagName ?? $this->tagName ?? $this->classToTagName();
        $this->attributes = $attributes ?? [];
        if (!empty($child)) {
            $this->child = $child;
        }
    }

    public function __toString()
    {
        return $this->print();
    }

    public function setChild(string $child)
    {
        $this->child = $child;
    }

    public function setAttribute(string $attributeName, string $value)
    {
        $this->attributes[$attributeName] = $value;
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
        if (is_null($this->child) && $this->selfClosing) {
            return <<<EOF
<{$this->tagName}{$this->listAttributes()}/>
EOF;
        } else {
            return
                    <<<EOF
<{$this->tagName}{$this->listAttributes()}>{$this->child}</{$this->tagName}>
EOF;
        }
    }

    private function classToTagName()
    {
        $namespaceArray = explode('\\', get_called_class());
        return strtolower(array_pop($namespaceArray));
    }

    private function listAttributes()
    {
        if (empty($this->attributes)) {
            return null;
        }

        foreach ($this->attributes as $attribute => $value) {
            if (!is_null($value)) {
                if ($value === true) {
                    $attributeList[] = $attribute;
                } else {
                    $attributeList[] = "$attribute=\"$value\"";
                }
            }
        }
        return empty($attributeList) ? null : ' ' . implode(' ', $attributeList);
    }

}
