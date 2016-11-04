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
    private $page;
    private $resultsPerPage;

    public function __construct($page = 1, $resultsPerPage = 10)
    {
        $this->setPage($page ?: 1);
        $this->setResultsPerPage($resultsPerPage ?: 10);

        return $this;
    }

    /**
     * Get the page number to be retrieved.
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Get the number of results per page.
     *
     * @return int
     */
    public function getResultsPerPage()
    {
        return $this->resultsPerPage;
    }

    /**
     * Set the page number to be retrieved.
     *
     * @param int $page
     *
     * @return BasicPager
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set the number of results per page.
     *
     * @param int $resultsPerPage
     *
     * @return BasicPager
     */
    public function setResultsPerPage($resultsPerPage)
    {
        $this->resultsPerPage = $resultsPerPage;

        return $this;
    }
}
