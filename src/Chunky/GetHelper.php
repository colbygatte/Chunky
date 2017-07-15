<?php

namespace ColbyGatte\Chunky;

trait GetHelper
{
    /**
     * @return \ColbyGatte\Chunky\Helper
     */
    public function getHelper()
    {
        return Helper::instance();
    }
}