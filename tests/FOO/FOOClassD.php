<?php

/**
 * Linna Framework
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2016, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 */

namespace Linna\FOO;

class FOOClassD
{
    public function __construct(FOOClassE $e, FOOClassF $f, FOOClassG $g)
    {
        echo 'D';
    }
}