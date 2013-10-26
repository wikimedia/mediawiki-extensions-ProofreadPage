<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;

/**
 * @licence GNU GPL v2+
 *
 * An exception thrown if the page is not in the pagination
 */
class PageNotInPaginationException extends InvalidArgumentException {
}