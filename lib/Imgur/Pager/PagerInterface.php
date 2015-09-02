<?php

namespace Imgur\Pager;

/**
 * Pager interface.
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
interface PagerInterface
{
    /**
     * Get the page number to be retrieved.
     */
    public function getPage();

    /**
     * Set the page number to be retrieved.
     */
    public function setPage($page);

    /**
     * Get the number of results per page.
     */
    public function getResultsPerPage();

    /**
     * Set the number of results per page.
     */
    public function setResultsPerPage($resultsPerPage);
}
