<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Theme;

class Page extends \Canopy3\AbstractConstruct
{

    /**
     * Filename of template
     * @var string
     */
    public string $filename;

    /**
     * Names of other sections besides "main" content may be displayed in.
     * @var array
     */
    public array $altSections;

    /**
     * Displayed template title.
     * @var string
     */
    public string $title;

    /**
     * Number of columns in template
     * @var integer
     */
    public int $columns;

    /**
     * The default section to plug content into if none is specified.
     * @var string
     */
    public string $defaultSection;

    /**
     * Longer description of template
     * @var string
     */
    public string $description;

}
