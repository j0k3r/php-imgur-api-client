<?php

namespace Imgur\Pager;

/**
 * Basic Pager.
 *
 * @see https://api.imgur.com/#paging_results
 *
 * @author Adrian Ghiuta <adrian.ghiuta@gmail.com>
 */
class BasicPager implements PagerInterface
{
    private int $page;
    private int $resultsPerPage;

    public function __construct(int $page = 1, int $resultsPerPage = 10)
    {
        $this->setPage($page ?: 1);
        $this->setResultsPerPage($resultsPerPage ?: 10);
    }

    /**
     * Get the page number to be retrieved.
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Get the number of results per page.
     */
    public function getResultsPerPage(): int
    {
        return $this->resultsPerPage;
    }

    /**
     * Set the page number to be retrieved.
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set the number of results per page.
     */
    public function setResultsPerPage(int $resultsPerPage): self
    {
        $this->resultsPerPage = $resultsPerPage;

        return $this;
    }
}
