<?php

/**
 * A CodedException is one that that the system will automatically show an error
 * specifically dependent on the error code. For example, an exception set with
 * code 404 shows a page not found error page if it is uncaught.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class CodedException extends \Exception
{

}
