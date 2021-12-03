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
    public function getPage(): int;

    /**
     * Set the page number to be retrieved.
     */
    public function setPage(int $page): self;

    /**
     * Get the number of results per page.
     */
    public function getResultsPerPage(): int;

    /**
     * Set the number of results per page.
     */
    public function setResultsPerPage(int $resultsPerPage): self;
}
