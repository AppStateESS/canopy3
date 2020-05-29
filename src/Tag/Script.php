<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Tag;

class Script extends \Canopy3\Tag
{

    public function __construct(array $attributes = null, string $tagName = null)
    {
        parent::__construct(['src' => $attributes['src'] ?? '', 'type' => $attributes['type'] ?? null]);
        $this->isDefer($attributes['defer'] ?? false);
        $this->singleton = false;
    }

    public function isDefer(bool $defer = true)
    {
        $this->setBoolAttribute('async', !$defer);
        $this->setBoolAttribute('defer', $defer);
    }

}
