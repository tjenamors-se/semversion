<?php

namespace Tjenamors\Semversion;

enum PreReleaseType: string
{
    case ALPHA = 'alpha.1';
    case BETA = 'beta.1';
    case NONE = '';
}
