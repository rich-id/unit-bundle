<?php declare(strict_types=1);

/**
 * @param mixed $object
 * @param bool  $displayContent
 *
 * @return void
 */
function debug($object, bool $displayContent = false): void
{
    if (!$displayContent && is_object($object)) {
        $object = \get_class($object);
    }

    \var_dump($object);

    try {
        ob_flush();
        flush();
    } catch (\Throwable $e) {
        // Nothing to flush
    }
}

/**
 * @return void
 */
function trace(): void
{
    debug(
        (new \Exception())->getTraceAsString()
    );
}

