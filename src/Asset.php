<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

class Asset
{

    protected int $id;
    protected string $callView;
    protected int $createdOn;

    /**
     * Title sent to the <title> tag in the header
     * @var string
     */
    protected string $browserTitle;
    protected string $site;
    protected string $summary;
    protected string $thumbnail;

    /**
     * Full title of asset
     * @var string
     */
    protected string $title;

    /**
     * URL dashed title used to access asset.
     * @var string
     */
    protected string $urlTitle;

}
